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
 * Class Folder
 *
 * Provide methods to handle folders.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Library
 */
class Folder extends System
{

	/**
	 * Folder name
	 * @var string
	 */
	protected $strFolder;


	/**
	 * Check whether a folder exists
	 * @param string
	 * @throws Expcetion
	 */
	public function __construct($strFolder)
	{
		$this->import('Files');

		if (is_file(TL_ROOT . '/' . $strFolder))
		{
			throw new Exception(sprintf('File "%s" is not a directory', $strFolder));
		}

		$this->strFolder = $strFolder;

		// Create folder if it does not exist
		if (!is_dir(TL_ROOT . '/' . $this->strFolder))
		{
			$strPath = '';
			$arrChunks = explode('/', $this->strFolder);

			foreach ($arrChunks as $strFolder)
			{
				$strPath .= $strFolder . '/'; 
				$this->Files->mkdir($strPath);
			}
		}
	}


	/**
	 * Return an object property
	 * @param string
	 * @return mixed
	 * @throws Exception
	 */
	public function __get($strKey)
	{
		if ($strKey != 'value')
		{
			throw new Exception(sprintf('Unknown or protected property "%s"', $strKey));
		}

		return $this->strFolder;
	}


	/**
	 * Return true if the folder is empty
	 * @return boolean
	 */
	public function isEmpty()
	{
		return count(scan(TL_ROOT . '/' . $this->strFolder)) ? true : false;
	}


	/**
	 * Empty the folder
	 */
	public function clear()
	{
		$this->rmdir($this->strFolder);
		$this->Files->mkdir($this->strFolder);
	}


	/**
	 * Delete the folder
	 */
	public function delete()
	{
		$this->rmdir($this->strFolder);
	}


	/**
	 * Recursively delete folder
	 * @param string
	 */
	private function rmdir($strFolder)
	{
		$arrFiles = scan(TL_ROOT . '/' . $strFolder);

		foreach ($arrFiles as $strFile)
		{
			if (is_dir(TL_ROOT . '/' . $strFolder . '/' . $strFile))
			{
				$this->rmdir($strFolder . '/' . $strFile);
				continue;
			}

			$this->Files->delete($strFolder . '/' . $strFile);
		}

		$this->Files->rmdir($strFolder);
	}
}

?>