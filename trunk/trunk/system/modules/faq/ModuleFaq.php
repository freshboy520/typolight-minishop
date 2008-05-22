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
 * @copyright  Leo Feyer 2008
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Faq
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModuleFaq
 *
 * Provide methods regarding FAQs.
 * @copyright  Leo Feyer 2008
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class ModuleFaq extends Frontend
{

	/**
	 * Add FAQs to the indexer
	 * @param array
	 * @param integer
	 * @return array
	 */
	public function getSearchablePages($arrPages, $intRoot=0)
	{
		$arrRoot = array();

		if ($intRoot > 0)
		{
			$arrRoot = $this->getChildRecords($intRoot, 'tl_page', true);
		}

		$objFaq = $this->Database->execute("SELECT id, jumpTo FROM tl_faq_category");

		// Walk through each category
		while ($objFaq->next())
		{
			if (is_array($arrRoot) && count($arrRoot) > 0 && !in_array($objFaq->jumpTo, $arrRoot))
			{
				continue;
			}

			// Get default URL
			$objParent = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=? AND (start=? OR start<?) AND (stop=? OR stop>?) AND published=?")
										->limit(1)
										->execute($objFaq->jumpTo, '', time(), '', time(), 1);

			if ($objParent->numRows < 1)
			{
				continue;
			}

			$strUrl = $this->generateFrontendUrl($objParent->fetchAssoc(), '/items/%s');

			// Get items
			$objItem = $this->Database->prepare("SELECT * FROM tl_faq WHERE pid=? AND published=? ORDER BY sorting")
									  ->execute($objFaq->id, 1);

			// Add items to the indexer
			while ($objItem->next())
			{
				$arrPages[] = sprintf($strUrl, ((strlen($objItem->alias) && !$GLOBALS['TL_CONFIG']['disableAlias']) ? $objItem->alias : $objItem->id));
			}
		}

		return $arrPages;
	}
}

?>