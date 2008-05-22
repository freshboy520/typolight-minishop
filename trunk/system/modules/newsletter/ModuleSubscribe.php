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
 * @package    Newsletter
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModuleSubscribe
 *
 * Front end module "newsletter subscribe".
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class ModuleSubscribe extends Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'nl_default';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new Template('be_wildcard');
			$objTemplate->wildcard = '### NEWSLETTER SUBSCRIBE ###';

			return $objTemplate->parse();
		}

		return parent::generate();
	}


	/**
	 * Generate module
	 */
	protected function compile()
	{
		if ($this->nl_template)
		{
			$this->Template = new FrontendTemplate($this->nl_template);
		}

		// Activate e-mail address
		if ($this->Input->get('token'))
		{
			$this->activateRecipient();
			return;
		}

		// Subscribe
		if ($this->Input->post('FORM_SUBMIT') == 'tl_subscribe')
		{
			$this->addRecipient();
		}

		// Messages
		if (strlen($_SESSION['SUBSCRIBE_ERROR']))
		{
			$this->Template->mclass = 'error';
			$this->Template->message = $_SESSION['SUBSCRIBE_ERROR'];
			$_SESSION['SUBSCRIBE_ERROR'] = '';
		}

		if (strlen($_SESSION['SUBSCRIBE_CONFIRM']))
		{
			$this->Template->mclass = 'confirm';
			$this->Template->message = $_SESSION['SUBSCRIBE_CONFIRM'];
			$_SESSION['SUBSCRIBE_CONFIRM'] = '';
		}

		// Default template variables
		$this->Template->email = '';
		$this->Template->submit = specialchars($GLOBALS['TL_LANG']['MSC']['subscribe']);
		$this->Template->action = ampersand($this->Environment->request);
		$this->Template->formId = 'tl_subscribe';
	}


	/**
	 * Activate a recipient
	 */
	private function activateRecipient()
	{
		$this->Template = new FrontendTemplate('mod_newsletter');

		$objRecipient = $this->Database->prepare("SELECT * FROM tl_newsletter_recipients WHERE token=?")
									   ->limit(1)
									   ->execute($this->Input->get('token'));

		if ($objRecipient->numRows < 1)
		{
			$this->Template->mclass = 'error';
			$this->Template->message = $GLOBALS['TL_LANG']['ERR']['invalidToken'];

			return;
		}

		$this->Database->prepare("UPDATE tl_newsletter_recipients SET active=?, token=? WHERE token=?")
					   ->execute(1, '', $this->Input->get('token'));

		$this->Template->mclass = 'confirm';
		$this->Template->message = $GLOBALS['TL_LANG']['MSC']['nl_activate'];
	}


	/**
	 * Add a new recipient
	 */
	private function addRecipient()
	{
		if ($this->nl_channel < 1)
		{
			$this->log('No newsletter channel selected', 'ModuleSubscribe addRecipient()', 5);
			$this->reload();
		}

		if (!preg_match('/^\w+([_\.-]*\w+)*@\w+([_\.-]*\w+)*\.[a-z]{2,6}$/i', $this->Input->post('email')))
		{
			$_SESSION['SUBSCRIBE_ERROR'] = $GLOBALS['TL_LANG']['ERR']['email'];
			$this->reload();
		}

		$objEmail = $this->Database->prepare("SELECT * FROM tl_newsletter_recipients WHERE email=? AND pid=?")
								   ->limit(1)
								   ->execute($this->Input->post('email'), $this->nl_channel);

		if ($objEmail->numRows)
		{
			if ($objEmail->active)
			{
				$_SESSION['SUBSCRIBE_ERROR'] = $GLOBALS['TL_LANG']['ERR']['subscribed'];
				$this->reload();
			}

			$this->Database->prepare("DELETE FROM tl_newsletter_recipients WHERE email=? AND pid=?")
						   ->execute($this->Input->post('email'), $this->nl_channel);
		}

		$strToken = md5(uniqid('', true));

		$arrSet = array
		(
			'pid' => $this->nl_channel,
			'tstamp' => time(),
			'email' => $this->Input->post('email'),
			'active' => '',
			'token' => $strToken
		);

		$this->Database->prepare("INSERT INTO tl_newsletter_recipients %s")
					   ->set($arrSet)
					   ->execute();

		// Activation e-mail
		$objEmail = new Email();

		$objChannel = $this->Database->prepare("SELECT title FROM tl_newsletter_channel WHERE id=?")
									 ->limit(1)
									 ->execute($this->nl_channel);

		$strText = str_replace('##channel##', $objChannel->title, $this->nl_subscribe);
		$strText = str_replace('##domain##', $this->Environment->host, $strText);
		$strText = str_replace('##link##', $this->Environment->base . $this->Environment->request . '?token=' . $strToken, $strText);

		$objEmail->from = $GLOBALS['TL_ADMIN_EMAIL'];
		$objEmail->subject = sprintf($GLOBALS['TL_LANG']['MSC']['nl_subject'], $this->Environment->host);
		$objEmail->text = $strText;

		$objEmail->sendTo($this->Input->post('email'));

		// Redirect to jumpTo page
		global $objPage;

		if (strlen($this->jumpTo) && $this->jumpTo != $objPage->id)
		{
			$objNextPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
										  ->limit(1)
										  ->execute($this->jumpTo);

			if ($objNextPage->numRows)
			{
				$this->redirect($this->generateFrontendUrl($objNextPage->fetchAssoc()));
			}
		}

		$_SESSION['SUBSCRIBE_CONFIRM'] = $GLOBALS['TL_LANG']['MSC']['nl_confirm'];
		$this->reload();
	}
}

?>