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
 * Class ModulePassword
 *
 * Front end module "lost password".
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class ModulePassword extends Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_password';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new Template('be_wildcard');
			$objTemplate->wildcard = '### LOST PASSWORD ###';

			return $objTemplate->parse();
		}

		// Return if there is a logged in user user already
		if (FE_USER_LOGGED_IN)
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
		$GLOBALS['TL_LANGUAGE'] = $objPage->language;

		$this->loadLanguageFile('tl_member');
		$this->loadDataContainer('tl_member');

		// Set new password
		if (strlen($this->Input->get('token')))
		{
			$this->setNewPassword();
			return;
		}

		// Username widget
		if (!$this->reg_skipName)
		{
			$arrFields['username'] = $GLOBALS['TL_DCA']['tl_member']['fields']['username'];
			$arrFields['username']['name'] = 'username';
		}

		// Email widget
		$arrFields['email'] = $GLOBALS['TL_DCA']['tl_member']['fields']['email'];
		$arrFields['email']['name'] = 'email';

		// Captcha widget
		if (!$this->disableCaptcha)
		{
			$arrFields['captcha'] = array
			(
				'name' => 'lost_password',
				'label' => $GLOBALS['TL_LANG']['MSC']['securityQuestion'],
				'inputType' => 'captcha',
				'eval' => array('mandatory'=>true)
			);
		}

		$row = 0;
		$strFields = '';
		$doNotSubmit = false;

		// Initialize widgets
		foreach ($arrFields as $arrField)
		{
			$strClass = $GLOBALS['TL_FFL'][$arrField['inputType']];
			$strFile = sprintf('%s/system/modules/frontend/%s.php', TL_ROOT, $strClass);

			if (!file_exists($strFile))
			{
				continue;
			}

			$arrField['eval']['required'] = $arrField['eval']['mandatory'];
			$objWidget = new $strClass($this->prepareForWidget($arrField, $arrField['name']));

			$objWidget->storeValues = true;
			$objWidget->rowClass = 'row_'.$row . (($row == 0) ? ' row_first' : '') . ((($row % 2) == 0) ? ' even' : ' odd');
			++$row;

			// Validate widget
			if ($this->Input->post('FORM_SUBMIT') == 'tl_lost_password')
			{
				$objWidget->validate();

				if ($objWidget->hasErrors())
				{
					$doNotSubmit = true;
				}
			}

			$strFields .= $objWidget->parse();
		}

		$this->Template->fields = $strFields;

		// Look for an account and send password link
		if ($this->Input->post('FORM_SUBMIT') == 'tl_lost_password' && !$doNotSubmit)
		{
			$objMember = $this->Database->prepare("SELECT * FROM tl_member WHERE email=?" . (!$this->reg_skipName ? " AND username=?" : ""))
										->limit(1)
										->execute($this->Input->post('email'), $this->Input->post('username'));

			if ($objMember->numRows < 1)
			{
				$this->strTemplate = 'mod_message';
				$this->Template = new Template($this->strTemplate);

				$this->Template->type = 'error';
				$this->Template->message = $GLOBALS['TL_LANG']['MSC']['accountNotFound'];

				return;
			}

			$this->sendPasswordLink($objMember);
		}

		$this->Template->formId = 'tl_lost_password';
		$this->Template->username = specialchars($GLOBALS['TL_LANG']['MSC']['username']);
		$this->Template->email = specialchars($GLOBALS['TL_LANG']['MSC']['emailAddress']);
		$this->Template->action = ampersand($this->Environment->request, ENCODE_AMPERSANDS);
		$this->Template->slabel = specialchars($GLOBALS['TL_LANG']['MSC']['requestPassword']);
		$this->Template->rowLast = 'row_' . count($arrFields) . ' row_last' . ((($row % 2) == 0) ? ' even' : ' odd');
	}


	/**
	 * Set the new password
	 */
	private function setNewPassword()
	{
		$objMember = $this->Database->prepare("SELECT id, username FROM tl_member WHERE activation=?")
									->limit(1)
									->execute($this->Input->get('token'));

		if ($objMember->numRows < 1)
		{
			$this->strTemplate = 'mod_message';
			$this->Template = new Template($this->strTemplate);

			$this->Template->type = 'error';
			$this->Template->message = $GLOBALS['TL_LANG']['MSC']['accountError'];

			return;
		}

		// Define form field
		$arrField = $GLOBALS['TL_DCA']['tl_member']['fields']['password'];
		$objWidget = new FormPassword($this->prepareForWidget($arrField, 'password'));

		// Validate the field
		if (strlen($this->Input->post('FORM_SUBMIT')) && $this->Input->post('FORM_SUBMIT') == $this->Session->get('setPasswordToken'))
		{
			$objWidget->validate();

			// Make sure that the password does not equal the username
			if ($objWidget->value == sha1($objMember->username))
			{
				$objWidget->addError($GLOBALS['TL_LANG']['ERR']['passwordName']);
			}

			// Set the new password and redirect
			if (!$objWidget->hasErrors())
			{
				$this->Session->set('setPasswordToken', '');
				array_pop($_SESSION['TL_CONFIRM']);

				$this->Database->prepare("UPDATE tl_member SET password=?, activation='' WHERE id=?")
							   ->execute($objWidget->value, $objMember->id);

				// HOOK: set new password callback
				if (array_key_exists('setNewPassword', $GLOBALS['TL_HOOKS']) && is_array($GLOBALS['TL_HOOKS']['setNewPassword']))
				{
					foreach ($GLOBALS['TL_HOOKS']['setNewPassword'] as $callback)
					{
						$this->import($callback[0]);
						$this->$callback[0]->$callback[1]($objMember, $objWidget->value);
					}
				}

				// Redirect
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

				// Confirm
				$this->strTemplate = 'mod_message';
				$this->Template = new Template($this->strTemplate);

				$this->Template->type = 'confirm';
				$this->Template->message = $GLOBALS['TL_LANG']['MSC']['newPasswordSet'];

				return;
			}
		}

		$strToken = md5(uniqid('', true));
		$this->Session->set('setPasswordToken', $strToken);

		$this->Template->formId = $strToken;
		$this->Template->fields = $objWidget->parse();
		$this->Template->action = ampersand($this->Environment->request, ENCODE_AMPERSANDS);
		$this->Template->slabel = specialchars($GLOBALS['TL_LANG']['MSC']['setNewPassword']);
	}


	/**
	 * Create a new user and redirect
	 * @param object
	 */
	private function sendPasswordLink(Database_Result $objMember)
	{
		$arrChunks = array();
		$confirmationId = md5(uniqid('', true));

		// Store confirmation ID
		$this->Database->prepare("UPDATE tl_member SET activation=? WHERE id=?")
					   ->execute($confirmationId, $objMember->id);

		$strConfirmation = $this->reg_password;
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
					$strConfirmation = str_replace($strChunk, $this->Environment->base . $this->Environment->request . (($GLOBALS['TL_CONFIG']['disableAlias'] || strpos($this->Environment->request, '?') !== false) ? '&' : '?') . 'token=' . $confirmationId, $strConfirmation);
					break;

				default:
					try
					{
						$strConfirmation = str_replace($strChunk, $objMember->$strKey, $strConfirmation);
					}
					catch (Exception $e)
					{
						$strConfirmation = str_replace($strChunk, '', $strConfirmation);
						$this->log('Invalid wildcard "' . $strKey . '" used in password request email', 'ModulePassword sendPasswordLink()', TL_GENERAL, $e->getMessage());
					}
					break;
			}
		}

		// Send e-mail
		$objEmail = new Email();

		$objEmail->from = $GLOBALS['TL_ADMIN_EMAIL'];
		$objEmail->subject = sprintf($GLOBALS['TL_LANG']['MSC']['passwordSubject'], $this->Environment->host);
		$objEmail->text = $strConfirmation;
		$objEmail->sendTo($objMember->email);

		$this->log('A new password has been requested for user ID ' . $objMember->id . ' (' . $objMember->email . ')', 'ModulePassword sendPasswordLink()', TL_ACCESS);
		$this->jumpToOrReload($this->jumpTo);
	}
}

?>