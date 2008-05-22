<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Registration
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModuleRegistration
 *
 * Front end module "registration".
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class ModuleRegistration extends Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'member_default';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new Template('be_wildcard');
			$objTemplate->wildcard = '### USER REGISTRATION ###';

			return $objTemplate->parse();
		}

		$this->editable = deserialize($this->editable);

		// Return if there are no editable fields or if there is a logged in user already
		if (!is_array($this->editable) || count($this->editable) < 1 || FE_USER_LOGGED_IN)
		{
			return '';
		}

		return parent::generate();
	}


	/**
	 * Generate module
	 */
	protected function compile()
	{
		global $objPage;

		// Activate account
		if (strlen($this->Input->get('token')))
		{
			$this->activateAcount();
			return;
		}

		$arrUser = array();
		$arrFields = array();
		$doNotSubmit = false;

		$GLOBALS['TL_LANGUAGE'] = $objPage->language;

		$this->loadLanguageFile('tl_member');
		$this->loadDataContainer('tl_member');

		if (strlen($this->memberTpl))
		{
			$this->Template = new Template($this->memberTpl);
		}

		$this->Template->fields = '';

		// Captcha
		if (!$this->disableCaptcha)
		{
			$arrCaptcha = array
			(
				'id'=>'registration',
				'label'=>$GLOBALS['TL_LANG']['MSC']['securityQuestion'],
				'mandatory'=>true,
				'required'=>true
			);

			$objCaptcha = new FormCaptcha($arrCaptcha);

			if ($this->Input->post('FORM_SUBMIT') == 'tl_registration')
			{
				$objCaptcha->validate();

				if ($objCaptcha->hasErrors())
				{
					$doNotSubmit = true;
				}
			}
		}

		// Build form
		foreach ($this->editable as $i=>$field)
		{
			$arrData = $GLOBALS['TL_DCA']['tl_member']['fields'][$field];
			$strGroup = $arrData['eval']['feGroup'];

			$strClass = $GLOBALS['TL_FFL'][$arrData['inputType']];
			$strFile = sprintf('%s/system/modules/frontend/%s.php', TL_ROOT, $strClass);

			if (!file_exists($strFile))
			{
				continue;
			}

			$objWidget = new $strClass($this->prepareForWidget($arrData, $field));

			$objWidget->storeValues = true;
			$objWidget->rowClass = 'row_'.$i . (($i == 0) ? ' row_first' : '') . ((($i % 2) == 0) ? ' even' : ' odd');

			// Validate input
			if ($this->Input->post('FORM_SUBMIT') == 'tl_registration')
			{
				$objWidget->validate();
				$varValue = $objWidget->value;

				// Check whether the password matches the username
				if ($objWidget instanceof Password && $varValue == $this->Input->post('username'))
				{
					$objWidget->addError($GLOBALS['TL_LANG']['ERR']['passwordName']);
				}

				// Convert date formats into timestamps
				if (strlen($varValue) && in_array($arrData['eval']['rgxp'], array('date', 'time', 'datim')))
				{
					$objDate = new Date($varValue, $GLOBALS['TL_CONFIG'][$arrData['eval']['rgxp'] . 'Format']);
					$varValue = $objDate->tstamp;
				}

				// Make sure that unique fields are unique
				if ($GLOBALS['TL_DCA']['tl_member']['fields'][$field]['eval']['unique'])
				{
					$objUnique = $this->Database->prepare("SELECT * FROM tl_member WHERE " . $field . "=?")
												->limit(1)
												->execute($varValue);

					if ($objUnique->numRows)
					{
						$objWidget->addError(sprintf($GLOBALS['TL_LANG']['ERR']['unique'], (strlen($arrData['label'][0]) ? $arrData['label'][0] : $field)));
					}
				}

				if ($objWidget->hasErrors())
				{
					$doNotSubmit = true;
				}

				// Store current value
				elseif ($objWidget->submitInput())
				{
					$arrUser[$field] = $varValue;
				}
			}

			$temp = $objWidget->parse();

			$this->Template->fields .= $temp;
			$arrFields[$strGroup][$field] .= $temp;
		}

		// Captcha
		if (!$this->disableCaptcha)
		{
			++$i;
			$objCaptcha->rowClass = 'row_'.$i . (($i == 0) ? ' row_first' : '') . ((($i % 2) == 0) ? ' even' : ' odd');
			$strCaptcha = $objCaptcha->parse();

			$this->Template->fields .= $strCaptcha;
			$arrFields['captcha'] .= $strCaptcha;
		}

		// Create new user if there are no errors
		if ($this->Input->post('FORM_SUBMIT') == 'tl_registration' && !$doNotSubmit)
		{
			$this->createNewUser($arrUser);
		}

		$this->Template->loginDetails = $GLOBALS['TL_LANG']['tl_member']['loginDetails'];
		$this->Template->addressDetails = $GLOBALS['TL_LANG']['tl_member']['addressDetails'];
		$this->Template->contactDetails = $GLOBALS['TL_LANG']['tl_member']['contactDetails'];
		$this->Template->personalData = $GLOBALS['TL_LANG']['tl_member']['personalData'];
		$this->Template->captchaDetails = $GLOBALS['TL_LANG']['MSC']['securityQuestion'];

		$this->Template->login = $arrFields['login'];
		$this->Template->address = $arrFields['address'];
		$this->Template->contact = $arrFields['contact'];
		$this->Template->personal = $arrFields['personal'];
		$this->Template->captcha = $arrFields['captcha'];

		$this->Template->formId = 'tl_registration';
		$this->Template->slabel = specialchars($GLOBALS['TL_LANG']['MSC']['register']);
		$this->Template->action = ampersand($this->Environment->request, ENCODE_AMPERSANDS);
		$this->Template->rowLast = 'row_' . (count($this->editable) + 1) . ((($i % 2) == 0) ? ' odd' : ' even');

		// HOOK: add newsletter fields
		if (in_array('newsletter', $this->Config->getActiveModules()))
		{
			$this->Template->newsletter = $arrFields['newsletter'];
			$this->Template->newsletterDetails = $GLOBALS['TL_LANG']['tl_member']['newsletterDetails'];
		}
	}


	/**
	 * Activate an account
	 */
	private function activateAcount()
	{
		$this->strTemplate = 'mod_message';
		$this->Template = new Template($this->strTemplate);

		$objMember = $this->Database->prepare("SELECT * FROM tl_member WHERE activation=?")
									->limit(1)
									->execute($this->Input->get('token'));

		if ($objMember->numRows < 1)
		{
			$this->Template->type = 'error';
			$this->Template->message = $GLOBALS['TL_LANG']['MSC']['accountError'];

			return;
		}

		$this->Database->prepare("UPDATE tl_member SET disable='', activation='' WHERE id=?")
					   ->execute($objMember->id);

		$this->log('User account ID ' . $objMember->id . ' (' . $objMember->email . ') has been activated', 'ModuleRegistration activateAccount()', TL_ACCESS);

		// HOOK: post activation callback
		if (array_key_exists('activateAccount', $GLOBALS['TL_HOOKS']) && is_array($GLOBALS['TL_HOOKS']['activateAccount']))
		{
			foreach ($GLOBALS['TL_HOOKS']['activateAccount'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($objMember);
			}
		}

		// Redirect to jumpTo page
		if (strlen($this->reg_jumpTo))
		{
			$objNextPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
										  ->limit(1)
										  ->execute($this->reg_jumpTo);

			if ($objNextPage->numRows)
			{
				$this->redirect($this->generateFrontendUrl($objNextPage->fetchAssoc()));
			}
		}

		// Confirm activation
		$this->Template->type = 'confirm';
		$this->Template->message = $GLOBALS['TL_LANG']['MSC']['accountActivated'];
	}


	/**
	 * Create a new user and redirect
	 * @param array
	 */
	private function createNewUser($arrData)
	{
		$arrData['tstamp'] = time();
		$arrData['groups'] = $this->reg_groups;
		$arrData['activation'] = md5(uniqid('', true));
		$arrData['login'] = $this->reg_allowLogin;

		// Disable account
		$arrData['disable'] = 1;

		// Send activation e-mail
		if ($this->reg_activate)
		{
			$arrChunks = array();

			$strConfirmation = $this->reg_text;
			preg_match_all('/##[^#]+##/i', $strConfirmation, $arrChunks);

			foreach ($arrChunks[0] as $strChunk)
			{
				$strKey = substr($strChunk, 2, -2);

				switch ($strKey)
				{
					case 'domain':
						$strConfirmation = str_replace($strChunk, $this->Environment->host, $strConfirmation);
						break;

					case 'link':
						$strConfirmation = str_replace($strChunk, $this->Environment->base . $this->Environment->request . (($GLOBALS['TL_CONFIG']['disableAlias'] || strpos($this->Environment->request, '?') !== false) ? '&' : '?') . 'token=' . $arrData['activation'], $strConfirmation);
						break;

					default:
						$strConfirmation = str_replace($strChunk, $arrData[$strKey], $strConfirmation);
						break;
				}
			}

			$objEmail = new Email();
			$objEmail->from = $GLOBALS['TL_ADMIN_EMAIL'];
			$objEmail->subject = sprintf($GLOBALS['TL_LANG']['MSC']['emailSubject'], $this->Environment->host);
			$objEmail->text = $strConfirmation;
			$objEmail->sendTo($arrData['email']);
		}

		// Create user
		$objNewUser = $this->Database->prepare("INSERT INTO tl_member %s")->set($arrData)->execute();
		$insertId = $objNewUser->insertId;

		// Assign home directory
		if ($this->reg_assignDir && is_dir(TL_ROOT . '/' . $this->reg_homeDir))
		{
			$this->import('Files');

			$strUserDir = strlen($arrData['username']) ? $arrData['username'] : 'user_' . $insertId;
			new Folder($this->reg_homeDir . '/' . $strUserDir);

			$this->Database->prepare("UPDATE tl_member SET homeDir=?, assignDir=1 WHERE id=?")
						   ->execute($this->reg_homeDir . '/' . $strUserDir, $insertId);
		}

		// HOOK: send insert ID and user data
		if (array_key_exists('createNewUser', $GLOBALS['TL_HOOKS']) && is_array($GLOBALS['TL_HOOKS']['createNewUser']))
		{
			foreach ($GLOBALS['TL_HOOKS']['createNewUser'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($insertId, $arrData);
			}
		}

		// Inform admin
		$objEmail = new Email();

		$objEmail->from = $GLOBALS['TL_ADMIN_EMAIL'];
		$objEmail->subject = sprintf($GLOBALS['TL_LANG']['MSC']['adminSubject'], $this->Environment->host);

		$strData = "\n\n";

		// Add user details
		foreach ($arrData as $k=>$v)
		{
			if ($k == 'password' || $k == 'tstamp' || $k == 'activation')
			{
				continue;
			}

			$v = deserialize($v);
			$strData .= $GLOBALS['TL_LANG']['tl_member'][$k][0] . ': ' . (is_array($v) ? implode(', ', $v) : $v) . "\n";
		}

		$objEmail->text = sprintf($GLOBALS['TL_LANG']['MSC']['adminText'], $insertId, $strData . "\n") . "\n";
		$objEmail->sendTo($GLOBALS['TL_ADMIN_EMAIL']);

		$this->log('A new user (ID ' . $insertId . ') registered on the website', 'ModuleRegistration createNewUser()', TL_ACCESS);
		$this->jumpToOrReload($this->jumpTo);
	}
}

?>