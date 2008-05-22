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
 * Class ModuleFaqList 
 *
 * @copyright  Leo Feyer 2008 
 * @author     Leo Feyer <leo@typolight.org> 
 * @package    Controller
 */
class ModuleFaqList extends Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_faqlist';

	/**
	 * Target pages
	 * @var array
	 */
	protected $arrTargets = array();


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$this->Template = new Template('be_wildcard');
			$this->Template->wildcard = '### FAQ LIST ###';

			return $this->Template->parse();
		}

		$this->faq_categories = deserialize($this->faq_categories, true);

		// Return if there are no categories
		if (!is_array($this->faq_categories) || count($this->faq_categories) < 1)
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
		$objFaq = $this->Database->prepare("SELECT tl_faq.id AS id, alias, question, headline, jumpTo FROM tl_faq LEFT JOIN tl_faq_category ON(tl_faq_category.id=tl_faq.pid) WHERE pid IN(" . implode(',', $this->faq_categories) . ")" . (!BE_USER_LOGGED_IN ? " AND published=?" : "") . " ORDER BY headline, sorting")
								 ->execute(1);

		if ($objFaq->numRows < 1)
		{
			return;
		}

		$arrFaq = array();

		// Add FAQs
		while ($objFaq->next())
		{
			$arrFaq[$objFaq->headline][] = array
			(
				'question' => $objFaq->question,
				'title' => htmlspecialchars($objFaq->question),
				'href' => $this->generateFaqLink($objFaq)
			);
		}

		// Add classes
		foreach ($arrFaq as $k=>$v)
		{
			$count = 0;
			$limit = count($v);

			for ($i=0; $i<$limit; $i++)
			{
				$arrFaq[$k][$i]['class'] = trim(((++$count == 1) ? ' first' : '') . (($count == $limit) ? ' last' : '') . ((($count % 2) == 0) ? ' odd' : ' even'));
			}
		}

		$this->Template->faq = $arrFaq;
	}


	/**
	 * Create links and remember pages that have been processed
	 * @param object
	 * @return string
	 */
	private function generateFaqLink(Database_Result $objFaq)
	{
		if (!array_key_exists($objFaq->id, $this->arrTargets))
		{
			if ($objFaq->jumpTo < 1)
			{
				$this->arrTargets[$objFaq->id] = ampersand($this->Environment->request, ENCODE_AMPERSANDS);
			}
			else
			{
				$objTarget = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
									 		->limit(1)
											->execute(intval($objFaq->jumpTo));

				if ($objTarget->numRows < 1)
				{
					$this->arrTargets[$objFaq->id] = ampersand($this->Environment->request, ENCODE_AMPERSANDS);
				}

				$this->arrTargets[$objFaq->id] = ampersand($this->generateFrontendUrl($objTarget->fetchAssoc(), '/items/' . ((strlen($objFaq->alias) && !$GLOBALS['TL_CONFIG']['disableAlias']) ? $objFaq->alias : $objFaq->id)));
			}
		}

		return $this->arrTargets[$objFaq->id];
	}
}

?>