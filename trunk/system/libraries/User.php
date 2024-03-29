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
 * @package    System
 * @license    LGPL
 * @filesource
 */


/**
 * Class User
 *
 * Provide methods to manage users.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Model
 */
abstract class User extends Model
{

	/**
	 * Current object instance (Singleton)
	 * @var object
	 */
	protected static $objInstance;

	/**
	 * Current user ID
	 * @var integer
	 */
	protected $intId;

	/**
	 * IP address of the current user
	 * @var string
	 */
	protected $strIp;

	/**
	 * Authentication hash value
	 * @var string
	 */
	protected $strHash;

	/**
	 * Table
	 * @var string
	 */
	protected $strTable;

	/**
	 * Name of the current cookie
	 * @var string
	 */
	protected $strCookie;


	/**
	 * Prevent cloning of the object (Singleton)
	 */
	final private function __clone() {}


	/**
	 * Authenticate a user
	 * @return boolean
	 */
	public function authenticate()
	{
		// Check the cookie hash
		if ($this->strHash != sha1(session_id().$this->strIp.$this->strCookie))
		{
			return false;
		}

		$objSession = $this->Database->prepare("SELECT * FROM tl_session WHERE hash=? AND name=?")
									 ->execute($this->strHash, $this->strCookie);

		// Try to find the session in the database
		if ($objSession->numRows < 1)
		{
			$this->log('No active session found in database', get_class($this) . ' authenticate()', TL_ACCESS);
			return false;
		}

		// Validate the session
		if ($objSession->sessionID != session_id() || $objSession->ip != $this->strIp || $objSession->hash != $this->strHash || ($objSession->tstamp + $GLOBALS['TL_CONFIG']['sessionTimeout']) < time())
		{
			$this->log('The current session could not be verified', get_class($this) . ' authenticate()', TL_ACCESS);
			return false;
		}

		$this->intId = $objSession->pid;

		// Load the user object
		if ($this->findBy('id', $this->intId) == false)
		{
			$this->log('The user of the current session could not be found in the database', get_class($this) . ' authenticate()', TL_ACCESS);
			return false;
		}

		$intTime = time();
		$this->setUserFromDb();

		$this->Database->prepare("UPDATE tl_session SET tstamp=? WHERE sessionID=?")
					   ->execute($intTime, session_id());

		$this->setCookie($this->strCookie, $this->strHash, ($intTime + $GLOBALS['TL_CONFIG']['sessionTimeout']), $GLOBALS['TL_CONFIG']['websitePath']);
		return true;
	}


	/**
	 * Try to login the current user
	 * @return boolean
	 */
	public function login()
	{
		$this->loadLanguageFile('default');

		// Do not continue if username or password are missing
		if (!$this->Input->post('username') || !$this->Input->post('password'))
		{
			return false;
		}

		// Load the user object
		if ($this->findBy('username', $this->Input->post('username')) == false)
		{
			$_SESSION['TL_ERROR'][] = $GLOBALS['TL_LANG']['ERR']['invalidLogin'];
			$this->log('Username "' . $this->Input->post('username') . '" could not be found in the database', get_class($this) . ' login()', TL_ACCESS);

			return false;
		}

		$this->setUserFromDb();

		// Set user language
		if ($this->Input->post('language'))
		{
			$this->language = $this->Input->post('language');
		}

		$blnAccountError = false;

		// Lock the account if there are too many login attempts
		if ($this->loginCount < 1)
		{
			$blnAccountError = true;

			// Save current record
			$this->locked = time();
			$this->loginCount = 3;
			$this->save();

			$this->log('The current account has been locked for security reasons', get_class($this) . ' login()', TL_ACCESS);
			$_SESSION['TL_ERROR'][] = sprintf($GLOBALS['TL_LANG']['ERR']['accountLocked'], ceil(($this->locked + $GLOBALS['TL_CONFIG']['lockPeriod'] - time()) / 60));

			// Send admin notification
			if (strlen($GLOBALS['TL_CONFIG']['adminEmail']))
			{
				$objEmail = new Email();

				$objEmail->subject = 'A TYPOlight account has been locked!';
				$objEmail->text = "The following TYPOlight back end account has been locked for security reasons.\n\nUsername: " . $this->username . "\nReal name: " . $this->firstname . " " . $this->lastname . "\nWebsite: " . $this->Environment->base . "\n\nThe account has been locked for " . ceil($GLOBALS['TL_CONFIG']['lockPeriod'] / 60) . " minutes because a user has entered an invalid password three times in a row. After this period of time the account will be unlocked automatically.\n\nThis e-mail has been generated by TYPOlight. You can not reply to it directly.\n";

				$objEmail->sendTo($GLOBALS['TL_CONFIG']['adminEmail']);
			}
		}

		// Check whether account is locked
		if (($this->locked + $GLOBALS['TL_CONFIG']['lockPeriod']) > time())
		{
			$blnAccountError = true;
			$_SESSION['TL_ERROR'][] = sprintf($GLOBALS['TL_LANG']['ERR']['accountLocked'], ceil((($this->locked + $GLOBALS['TL_CONFIG']['lockPeriod']) - time()) / 60));
		}

		// Check whether account is disabled
		elseif ($this->disable)
		{
			$blnAccountError = true;

			$_SESSION['TL_ERROR'][] = $GLOBALS['TL_LANG']['ERR']['invalidLogin'];
			$this->log('The current account has been disabled', get_class($this) . ' login()', TL_ACCESS);
		}

		// Check whether account is not active yet or anymore
		elseif (strlen($this->start) || strlen($this->stop))
		{
			if (strlen($this->start) && $this->start > time())
			{
				$blnAccountError = true;

				$_SESSION['TL_ERROR'][] = $GLOBALS['TL_LANG']['ERR']['invalidLogin'];
				$this->log('The current account was not active yet (activation date: ' . $this->start . ')', get_class($this) . ' login()', TL_ACCESS);
			}

			if (strlen($this->stop) && $this->stop < time())
			{
				$blnAccountError = true;

				$_SESSION['TL_ERROR'][] = $GLOBALS['TL_LANG']['ERR']['invalidLogin'];
				$this->log('The current account was not active anymore (deactivation date: ' . $this->stop . ')', get_class($this) . ' login()', TL_ACCESS);
			}
		}

		// Redirect to login screen if there is an error
		if ($blnAccountError)
		{
			return false;
		}

		// Validate the password
		if ($this->password != sha1($this->Input->post('password')))
		{
			--$this->loginCount;
			$this->save();

			$_SESSION['TL_ERROR'][] = $GLOBALS['TL_LANG']['ERR']['invalidLogin'];
			$this->log('Invalid password submitted for username "' . $this->username . '"', get_class($this) . ' login()', TL_ACCESS);

			return false;
		}

		// Re-set language (in case there was no $_POST variable)
		if (strlen($this->language))
		{
			$GLOBALS['TL_LANGUAGE'] = $this->language;
		}

		// Save current record
		$this->tstamp = time();
		$this->loginCount = 3;
		$this->save();

		$this->strHash = sha1(session_id().$this->strIp.$this->strCookie);

		$this->Database->prepare("INSERT INTO tl_session (pid, tstamp, name, sessionID, ip, hash) VALUES (?, ?, ?, ?, ?, ?)")
					   ->execute($this->intId, $this->tstamp, $this->strCookie, session_id(), $this->strIp, $this->strHash);

		$this->setCookie($this->strCookie, $this->strHash, ($this->tstamp + $GLOBALS['TL_CONFIG']['sessionTimeout']), $GLOBALS['TL_CONFIG']['websitePath']);

		// Clean up old sessions
		$this->Database->prepare("DELETE FROM tl_session WHERE tstamp<?")
					   ->execute(time() - $GLOBALS['TL_CONFIG']['sessionTimeout']);

		$this->log('User "' . $this->username . '" has logged in', get_class($this) . ' login()', TL_ACCESS);
		return true;
	}


	/**
	 * Remove the authentication cookie and destroy the current session
	 * @return boolean
	 */
	public function logout()
	{
		// Return if the user has been logged out already
		if (!$this->Input->cookie($this->strCookie))
		{
			return false;
		}

		$objSession = $this->Database->prepare("SELECT * FROM tl_session WHERE hash=? AND name=?")
									 ->limit(1)
									 ->execute($this->strHash, $this->strCookie);

		if ($objSession->numRows > 0)
		{
			$this->strIp = $objSession->ip;
			$this->strHash = $objSession->hash;
			$intUserid = $objSession->pid;
		}

		$this->setCookie($this->strCookie, $this->strHash, (time() - 86400), $GLOBALS['TL_CONFIG']['websitePath']);

		$this->Database->prepare("DELETE FROM tl_session WHERE hash=?")
					   ->execute($this->strHash);

		$this->strHash = '';

		session_destroy();
		session_write_close();

		setcookie('PHPSESSID', session_id(), (time() - 86400), '/');

		// Add a log entry
		if ($this->findBy('id', $intUserid) != false)
		{
			$GLOBALS['TL_USERNAME'] = $this->username;
			$this->log('User "' . $this->username . '" has logged out', $this->strTable . ' logout()', TL_ACCESS);
		}

		return true;
	}


	/**
	 * Return true if the user is member of a particular group
	 * @param mixed
	 * @return boolean
	 */
	public function isMemberOf($id)
	{
		// ID not numeric
		if (!is_numeric($id))
		{
			return false;
		}

		$groups = deserialize($this->arrData['groups']);

		// No groups assigned
		if (!is_array($groups) || count($groups) < 1)
		{
			return false;
		}

		// Group ID found
		if (in_array($id, $groups))
		{
			return true;
		}

		return false;
	}


	/**
	 * Set all user properties from a database record
	 * @param object
	 */
	abstract protected function setUserFromDb();
}

?>