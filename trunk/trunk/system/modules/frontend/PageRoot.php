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
 * Class PageRoot
 *
 * Provide methods to handle a website root page.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class PageRoot extends Frontend
{

	/**
	 * Redirect to the first active regular page
	 * @param integer
	 * @param boolean
	 * @return integer
	 */
	public function generate($pageId, $blnReturn=false)
	{
		$objNextPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE pid=? AND type!=? AND type!=? AND type!=? AND (start=? OR start<?) AND (stop=? OR stop>?)" . (!BE_USER_LOGGED_IN ? ' AND published=?' : '') . " ORDER BY sorting")
									  ->limit(1)
									  ->execute($pageId, 'root', 'error_403', 'error_404', '', time(), '', time(), 1);

		if ($objNextPage->numRows)
		{
			if ($blnReturn)
			{
				return $objNextPage->id;
			}

			$this->redirect($this->generateFrontendUrl($objNextPage->fetchAssoc()));
		}

		if ($pageId === 0)
		{
			$this->log('No root page found (host "' . $this->Environment->host . '", languages "'.implode(', ', $this->Environment->httpAcceptLanguage).'")', 'PageRoot generate()', TL_ERROR);
		}

		else
		{
			$this->log('No active page found under root page "' . $pageId . '" (host "' . $this->Environment->host . '", languages "'.implode(', ', $this->Environment->httpAcceptLanguage).'")', 'PageRoot generate()', TL_ERROR);
		}

		header('HTTP/1.0 404 Not Found');
		die('No pages found');
	}
}

?>