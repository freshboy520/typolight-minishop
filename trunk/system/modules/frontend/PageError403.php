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
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Class PageError403
 *
 * Provide methods to handle an error 403 page.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class PageError403 extends Frontend
{

	/**
	 * Generate an error 403 page
	 * @param integer
	 */
	public function generate($pageId)
	{
		// Add a log entry
		$this->log('Access to page ID "' . $pageId . '"denied', 'PageError403 generate()', TL_ERROR);

		// Look for an error_403 page within the website root
		$obj403 = $this->Database->prepare("SELECT * FROM tl_page WHERE type=? AND pid=? AND (start=? OR start<?) AND (stop=? OR stop>?)" . (!BE_USER_LOGGED_IN ? ' AND published=?' : ''))
								 ->limit(1)
								 ->execute('error_403', $this->getRootIdFromUrl(), '', time(), '', time(), 1);

		// Look for a global error_403 page
		if ($obj403->numRows < 1)
		{
			$obj403 = $this->Database->prepare("SELECT * FROM tl_page WHERE type=? AND pid=? AND (start=? OR start<?) AND (stop=? OR stop>?)" . (!BE_USER_LOGGED_IN ? ' AND published=?' : ''))
									 ->limit(1)
									 ->execute('error_403', 0, '', time(), '', time(), 1);
		}

		// Die if there is no page at all
		if ($obj403->numRows < 1)
		{
			header('HTTP/1.0 403 Forbidden');
			die('Forbidden');
		}

		// Generate the error page
		if (!$obj403->autoforward || $obj403->jumpTo < 1)
		{
			global $objPage;

			$objPage = $this->getPageDetails($obj403->id);
			$objHandler = new $GLOBALS['TL_PTY']['regular']();

			header('HTTP/1.0 403 Forbidden');
			$objHandler->generate($objPage);

			exit;
		}

		// Forward to another page
		$objNextPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
									  ->limit(1)
									  ->execute($obj403->jumpTo);

		if ($objNextPage->numRows)
		{
			$this->redirect($this->generateFrontendUrl($objNextPage->fetchAssoc()), ($obj403->redirect == 'temporary'));
		}

		$this->log('Forward page ID "' . $obj403->jumpTo . '" does not exist', 'PageError403 generate()', TL_ERROR);

		header('HTTP/1.0 403 Forbidden');
		die('Forward page not found');
	}
}

?>