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
 * Class Newsletter
 *
 * Provide methods to handle newsletters.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class Newsletter extends Backend
{

	/**
	 * Renturn a form to choose an existing style sheet and import it
	 * @param object
	 * @return string
	 */
	public function send(DataContainer $objDc)
	{
		$objNewsletter = $this->Database->prepare("SELECT * FROM tl_newsletter WHERE id=?")
										->limit(1)
										->execute($objDc->id);

		// Return if there is no newsletter
		if ($objNewsletter->numRows < 1)
		{
			return '';
		}

		$arrAttachments = array();

		// Attachments
		if ($objNewsletter->addFile)
		{
			$files = deserialize($objNewsletter->files);

			if (is_array($files) && count($files) > 0)
			{
				foreach ($files as $file)
				{
					if (is_file(TL_ROOT . '/' . $file))
					{
						$arrAttachments[] = $file;
					}
				}
			}
		}

		// Add default sender address
		if (!strlen($objNewsletter->sender))
		{
			$objNewsletter->sender = $GLOBALS['TL_CONFIG']['adminEmail'];
		}

		$css = '';

		// Add style sheet newsletter.css
		if (file_exists(TL_ROOT . '/newsletter.css'))
		{
			$buffer = file_get_contents(TL_ROOT . '/newsletter.css');
			$buffer = preg_replace('@/\*\*.*\*/@Us', '', $buffer);

			$css  = '<style type="text/css">' . "\n";
			$css .= trim($buffer) . "\n";
			$css .= '</style>' . "\n";
		}

		// Replace insert tags
		$content = $this->replaceInsertTags($objNewsletter->content);

		// Send newsletter
		if (strlen($this->Input->get('token')) && $this->Input->get('token') == $this->Session->get('tl_newsletter_send'))
		{
			// Get total number of recipients
			$objTotal = $this->Database->prepare("SELECT COUNT(*) AS total FROM tl_newsletter_recipients WHERE pid=? AND active=?")
									   ->execute($objNewsletter->pid, 1);

			// Return if there are no recipients
			if ($objTotal->total < 1)
			{
				$this->Session->set('tl_newsletter_send', null);
				$_SESSION['TL_ERROR'][] = $GLOBALS['TL_LANG']['tl_newsletter']['error'];

				$this->redirect($this->getReferer());
			}

			$intStart = $this->Input->get('start') ? $this->Input->get('start') : 0;
			$intPages = $this->Input->get('mpc') ? $this->Input->get('mpc') : 10;

			// Get current recipients
			$objRecipients = $this->Database->prepare("SELECT * FROM tl_newsletter_recipients WHERE pid=? AND active=?")
											->limit($intPages, $intStart)
											->execute($objNewsletter->pid, 1);

			echo '<div style="font-family:Verdana, sans-serif; font-size:11px; line-height:16px; margin-bottom:12px;">';

			// Send newsletter
			if ($objRecipients->numRows > 0)
			{
				$objEmail = new Email();

				$objEmail->from = $objNewsletter->sender;
				$objEmail->subject = $objNewsletter->subject;

				if (strlen($objNewsletter->senderName))
				{
					$objEmail->fromName = $objNewsletter->senderName;
				}

				// Attachments
				if (is_array($arrAttachments) && count($arrAttachments) > 0)
				{
					foreach ($arrAttachments as $strAttachment)
					{
						if (is_file(TL_ROOT . '/' . $strAttachment))
						{
							$objEmail->attachFile(TL_ROOT . '/' . $strAttachment);
						}
					}
				}

				while ($objRecipients->next())
				{
					$strContent = str_replace('##email##', $objRecipients->email, $content);
					$strContent = str_replace('##name##', preg_replace('/@.*$/i', '', $objRecipients->email), $strContent);

					// Send as text
					if ($objNewsletter->sendText)
					{
						$strContent = preg_replace('@<br( /)?>@i', "\n", $strContent);
						$strContent = strip_tags($strContent);
						$strContent = preg_replace('/\n\n/', "\r\n", $strContent);

						$objEmail->text = $strContent;
					}

					// Send as HTML
					else
					{
						$objTemplate = new FrontendTemplate((strlen($objNewsletter->template) ? $objNewsletter->template : 'mail_default'));

						$objTemplate->title = $objNewsletter->subject;
						$objTemplate->charset = $GLOBALS['TL_CONFIG']['characterSet'];
						$objTemplate->body = $strContent;
						$objTemplate->css = $css;

						$objEmail->html = $objTemplate->parse();
						$objEmail->imageDir = TL_ROOT . '/';
					}

					$objEmail->sendTo($objRecipients->email);
					echo 'Sending to <strong>' . $objRecipients->email . '</strong><br />';
				}
			}

			echo '<div style="margin-top:12px;">';

			// Redirect back home
			if ($objRecipients->numRows < 1 || ($intStart + $intPages) >= $objTotal->total)
			{
				$this->Session->set('tl_newsletter_send', null);

				// Update status
				$this->Database->prepare("UPDATE tl_newsletter SET sent=?, date=? WHERE id=?")
							   ->execute(1, time(), $objNewsletter->id);

				$_SESSION['TL_CONFIRM'][] = sprintf($GLOBALS['TL_LANG']['tl_newsletter']['confirm'], $objTotal->total);
				$url = $this->Environment->base . preg_replace('/&(amp;)?(start|mpc|token)=[^&]*/', '', $this->Environment->request);

				echo '<script type="text/javascript">setTimeout(\'window.location="' . $url . '"\', 1000);</script>';
				echo '<a href="' . $url . '">Please click here to proceed if you are not using JavaScript</a>';
			}

			// Redirect to the next cycle
			else
			{
				$url = preg_replace('/&(amp;)?(start|mpc)=[^&]*/', '', $this->Environment->request);
				$url = $this->Environment->base . $url . '&start=' . ($intStart + $intPages) . '&mpc=' . $intPages;

				echo '<script type="text/javascript">setTimeout(\'window.location="' . $url . '"\', 1000);</script>';
				echo '<a href="' . $url . '">Please click here to proceed if you are not using JavaScript</a>';
			}

			echo '</div></div>';
			exit;
		}

		$strToken = md5(uniqid('', true));
		$this->Session->set('tl_newsletter_send', $strToken);
		$sprintf = strlen($objNewsletter->senderName) ? $objNewsletter->senderName . ' &lt;%s&gt;' : '%s';

		// Preview newsletter
		return '
<div id="tl_buttons">
<a href="'.$this->getReferer(ENCODE_AMPERSANDS).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBT']).'">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
</div>

<h2 class="sub_headline">'.$GLOBALS['TL_LANG']['tl_newsletter']['headline'].'</h2>'.$this->getMessages().'

<form action="'.ampersand($this->Environment->script, ENCODE_AMPERSANDS).'" id="tl_newsletter_send" class="tl_form" method="get">
<div class="tl_formbody_edit tl_newsletter_send">
<input type="hidden" name="do" value="' . $this->Input->get('do') . '" />
<input type="hidden" name="table" value="' . $this->Input->get('table') . '" />
<input type="hidden" name="key" value="' . $this->Input->get('key') . '" />
<input type="hidden" name="id" value="' . $this->Input->get('id') . '" />
<input type="hidden" name="token" value="' . $strToken . '" />
<table cellpadding="0" cellspacing="0" class="prev_header" summary="">
  <tr class="row_0">
    <td class="col_0">' . $GLOBALS['TL_LANG']['tl_newsletter']['from'] . '</td>
    <td class="col_1">' . sprintf($sprintf, $objNewsletter->sender) . '</td>
  </tr>
  <tr class="row_1">
    <td class="col_0">' . $GLOBALS['TL_LANG']['tl_newsletter']['subject'][0] . '</td>
    <td class="col_1">' . $objNewsletter->subject . '</td>
  </tr>
  <tr class="row_2">
    <td class="col_0">' . $GLOBALS['TL_LANG']['tl_newsletter']['template'][0] . '</td>
    <td class="col_1">' . $objNewsletter->template . '</td>
  </tr>' . ((is_array($arrAttachments) && count($arrAttachments) > 0) ? '
  <tr class="row_3">
    <td class="col_0">' . $GLOBALS['TL_LANG']['tl_newsletter']['attachments'] . '</td>
    <td class="col_1">' . implode(', ', $arrAttachments) . '</td>
  </tr>' : '') . '
</table>
<div class="preview">
' . $content . '
</div>
<div class="tl_tbox">
  <h3><label for="ctrl_mpc">' . $GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'][0] . '</label></h3>
  <input type="text" name="mpc" id="ctrl_mpc" value="10" class="tl_text" />' . (($GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'][1] && $GLOBALS['TL_CONFIG']['showHelp']) ? '
  <p class="tl_help">' . $GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'][1] . '</p>' : '') . '
</div>
</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
<input type="submit" id="send" class="tl_submit" alt="send newsletter" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG']['tl_newsletter']['send'][0]).'" /> 
</div>

</div>
</form>';
	}


	/**
	 * Return a form to choose a CSV file and import it
	 * @param object
	 * @return string
	 */
	public function importRecipients()
	{
		if ($this->Input->get('key') != 'import')
		{
			return '';
		}

		// Import CSS
		if ($this->Input->post('FORM_SUBMIT') == 'tl_recipients_import')
		{
			if (!$this->Input->post('source') || !is_array($this->Input->post('source')))
			{
				$_SESSION['TL_ERROR'][] = $GLOBALS['TL_LANG']['ERR']['all_fields'];
				$this->reload();
			}

			foreach ($this->Input->post('source') as $strCsvFile)
			{
				$objFile = new File($strCsvFile);

				if ($objFile->extension != 'csv')
				{
					$_SESSION['TL_ERROR'][] = sprintf($GLOBALS['TL_LANG']['ERR']['filetype'], $objFile->extension);
					continue;
				}

				$strFile = $objFile->getContent();
				$arrRecipients = trimsplit('[,;]', $strFile);

				foreach ($arrRecipients as $strRecipient)
				{
					$this->Database->prepare("DELETE FROM tl_newsletter_recipients WHERE pid=? AND email=?")->execute($this->Input->get('id'), $strRecipient);
					$this->Database->prepare("INSERT INTO tl_newsletter_recipients SET pid=?, tstamp=?, email=?, active=?")->execute($this->Input->get('id'), time(), $strRecipient, 1);
				}

				$_SESSION['TL_CONFIRM'][] = sprintf($GLOBALS['TL_LANG']['tl_newsletter_recipients']['confirm'], count($arrRecipients));
			}

			setcookie('BE_PAGE_OFFSET', 0, 0, '/');
			$this->reload();
		}

		$objTree = new FileTree($this->prepareForWidget($GLOBALS['TL_DCA']['tl_newsletter_recipients']['fields']['source'], 'source', null, 'source', 'tl_newsletter_recipients'));

		// Return form
		return '
<div id="tl_buttons">
<a href="'.$this->getReferer(ENCODE_AMPERSANDS).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBT']).'">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
</div>

<h2 class="sub_headline">'.$GLOBALS['TL_LANG']['tl_newsletter_recipients']['import'][1].'</h2>'.$this->getMessages().'

<form action="'.ampersand($this->Environment->request, ENCODE_AMPERSANDS).'" id="tl_recipients_import" class="tl_form" method="post">
<div class="tl_formbody_edit">
<input type="hidden" name="FORM_SUBMIT" value="tl_recipients_import" />

<div class="tl_tbox">
  <h3><label for="source">'.$GLOBALS['TL_LANG']['tl_newsletter_recipients']['source'][0].'</label></h3>'.$objTree->generate().(strlen($GLOBALS['TL_LANG']['tl_newsletter_recipients']['source'][1]) ? '
  <p class="tl_help">'.$GLOBALS['TL_LANG']['tl_newsletter_recipients']['source'][1].'</p>' : '').'
</div>

</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
<input type="submit" name="save" id="save" class="tl_submit" alt="import style sheet" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG']['tl_newsletter_recipients']['import'][0]).'" /> 
</div>

</div>
</form>';
	}


	/**
	 * Synchronize newsletter subscription of new users
	 * @param object
	 * @param array
	 */
	public function createNewUser($userId, $arrData)
	{
		$arrNewsletters = deserialize($arrData['newsletter'], true);

		// Return if there are no newsletters
		if (!is_array($arrNewsletters))
		{
			return;
		}

		foreach ($arrNewsletters as $intNewsletter)
		{
			if ($intNewsletter < 1)
			{
				continue;
			}

			$this->Database->prepare("INSERT INTO tl_newsletter_recipients SET pid=?, tstamp=?, email=?, active=1")
						   ->execute(intval($intNewsletter), time(), $arrData['email']);
		}
	}


	/**
	 * Synchronize newsletter subscription of existing users
	 * @param mixed
	 * @param object
	 * @return mixed
	 */
	public function synchronize($varValue, $objUser)
	{
		// If called from the back end, the second argument is a DataContainer object
		if ($objUser instanceof DataContainer)
		{
			$objUser = $this->Database->prepare("SELECT * FROM tl_member WHERE id=?")
									  ->limit(1)
									  ->execute($objUser->id);

			if ($objUser->numRows < 1)
			{
				return $varValue;
			}
		}

		$this->Database->prepare("DELETE FROM tl_newsletter_recipients WHERE email=?")
					   ->execute($objUser->email);

		$varValue = deserialize($varValue, true);

		if (is_array($varValue))
		{
			foreach ($varValue as $intId)
			{
				if ($intId < 1)
				{
					continue;
				}

				$this->Database->prepare("INSERT INTO tl_newsletter_recipients SET pid=?, tstamp=?, email=?, active=?")
							   ->execute(intval($intId), time(), $objUser->email, 1);
			}
		}

		return serialize($varValue);
	}


	/**
	 * Update a particular member account
	 * @param integer
	 * @param object
	 */
	public function updateAccount()
	{
		$intUser = $this->Input->get('id');

		// Front end call
		if (TL_MODE == 'FE')
		{
			$this->import('FrontendUser', 'User');
			$intUser = $this->User->id;
		}

		// Edit account
		if (TL_MODE == 'FE' || $this->Input->get('act') == 'edit')
		{
			$objUser = $this->Database->prepare("SELECT email FROM tl_member WHERE id=?")
									  ->limit(1)
									  ->execute($intUser);

			if ($objUser->numRows)
			{
				$objSubscriptions = $this->Database->prepare("SELECT pid FROM tl_newsletter_recipients WHERE email=?")
												   ->execute($objUser->email);

				$strNewsletters = serialize($objSubscriptions->fetchEach('pid'));

				$this->Database->prepare("UPDATE tl_member SET newsletter=? WHERE id=?")
							   ->execute($strNewsletters, $intUser);

				// Update the front end user object
				if (TL_MODE == 'FE')
				{
					$this->User->newsletter = $strNewsletters;
				}
			}
		}

		// Delete account
		elseif ($this->Input->get('act') == 'delete')
		{
			$objUser = $this->Database->prepare("SELECT email FROM tl_member WHERE id=?")
									  ->limit(1)
									  ->execute($intUser);

			if ($objUser->numRows)
			{
				$objSubscriptions = $this->Database->prepare("DELETE FROM tl_newsletter_recipients WHERE email=?")
												   ->execute($objUser->email);
			}
		}
	}


	/**
	 * Get all editable newsletters and return them as array
	 * @param object
	 * @return array
	 */
	public function getNewsletters($dc)
	{
		$objNewsletter = $this->Database->execute("SELECT id, title FROM tl_newsletter_channel");

		if ($objNewsletter->numRows < 1)
		{
			return array();
		}

		$arrNewsletters = array();

		// Back end
		if (TL_MODE == 'BE')
		{
			while ($objNewsletter->next())
			{
				$arrNewsletters[$objNewsletter->id] = $objNewsletter->title;
			}

			return $arrNewsletters;
		}

		// Front end
		$newsletters = deserialize($dc->newsletters, true);

		if (!is_array($newsletters) || count($newsletters) < 1)
		{
			return array();
		}

		while ($objNewsletter->next())
		{
			if (in_array($objNewsletter->id, $newsletters))
			{
				$arrNewsletters[$objNewsletter->id] = $objNewsletter->title;
			}
		}

		return $arrNewsletters;
	}
}

?>