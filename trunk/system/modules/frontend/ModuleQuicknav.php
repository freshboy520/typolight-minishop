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
 * Class ModuleQuicknav
 *
 * Front end module "quick navigation".
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class ModuleQuicknav extends Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_quicknav';


	/**
	 * Redirect to the selected page
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new Template('be_wildcard');
			$objTemplate->wildcard = '### QUICK NAVIGATION ###';

			return $objTemplate->parse();
		}

		if ($this->Input->post('FORM_SUBMIT') == 'tl_quicknav')
		{
			if (strlen($this->Input->post('target')))
			{
				$this->redirect($this->Input->post('target'));
			}

			$this->reload();
		}

		return parent::generate();
	}


	/**
	 * Generate module
	 */
	protected function compile()
	{
		if ($this->includeRoot)
		{
			$this->rootPage = 0;
		}

		$this->Template->button = specialchars($GLOBALS['TL_LANG']['MSC']['go']);
		$this->Template->title = strlen($this->customLabel) ? $this->customLabel : $GLOBALS['TL_LANG']['MSC']['quicknav'];
		$this->Template->request = ampersand($this->Environment->request, ENCODE_AMPERSANDS);
		$this->Template->items = $this->getQuicknavPages($this->rootPage);
	}


	/**
	 * Recursively get all quicknav pages and return them as array
	 * @param integer
	 * @param integer
	 * @return array
	 */
	private function getQuicknavPages($pid, $level=1)
	{
		global $objPage;

		$groups = array();
		$arrPages = array();

		// Get all groups of the current front end user
		if (FE_USER_LOGGED_IN)
		{
			$this->import('FrontendUser', 'User');
			$groups = $this->User->groups;
		}

		// Get all active subpages
		$objSubpages = $this->Database->prepare("SELECT * FROM tl_page WHERE pid=? AND type!=? AND type!=? AND type!=? AND (start=? OR start<?) AND (stop=? OR stop>?) AND hide!=?" . ((FE_USER_LOGGED_IN && !BE_USER_LOGGED_IN) ? ' AND guests!=?' : '') . (!BE_USER_LOGGED_IN ? ' AND published=?' : '') . " ORDER BY sorting")
									  ->execute($pid, 'root', 'error_403', 'error_404', '', time(), '', time(), 1, 1, 1);

		if ($objSubpages->numRows < 1)
		{
			return '';
		}

		++$level;

		while($objSubpages->next())
		{
			$_groups = deserialize($objSubpages->groups);

			// Do not show protected pages unless a back end or front end user is logged in
			if (!$objSubpages->protected || (!is_array($_groups) && FE_USER_LOGGED_IN) || BE_USER_LOGGED_IN || (is_array($_groups) && array_intersect($_groups, $groups)) || $this->showProtected)
			{
				$arrPages[] = array
				(
					'level' => ($level - 2),
					'title' => specialchars($objSubpages->title),
					'pageTitle' => specialchars($objSubpages->pageTitle),
					'href' => $this->generateFrontendUrl($objSubpages->row()),
					'link' => $objSubpages->title
				);

				// Subpages
				if (!$this->showLevel || $this->showLevel >= $level || (!$this->hardLimit && ($objPage->id == $objSubpages->id || in_array($objPage->id, $this->getChildRecords($objSubpages->id, 'tl_page')))))
				{
					$subpages = $this->getQuicknavPages($objSubpages->id, $level);

					if (is_array($subpages))
					{
						$arrPages = array_merge($arrPages, $subpages);
					}
				}
			}
		}

		return $arrPages;
	}
}

?>