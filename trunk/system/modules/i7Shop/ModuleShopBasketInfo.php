<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * include7 MiniShop – extension for TYPOLight from Leo Feyer
 * Copyright (C) 2008 Jonas Schnelli / include7 AG
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
 * @copyright  Jonas Schnelli / include7 AG 2008
 * @author     Jonas Schnelli / include7 AG <jonas.schnelli@incude7.ch>
 * @package    i7Shop
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModuleShopBasketInfo
 *
 * Module for displaying basketinformation.
 * 
 * @copyright  Jonas Schnelli / include7 AG 2008
 * @author     Jonas Schnelli / include7 AG <jonas.schnelli@incude7.ch>
 * @package    i7Shop
 */

class ModuleShopBasketInfo extends BaseShop
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'shopbasketlink_basic';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$this->Template = new Template('be_wildcard');
			$this->Template->wildcard = '### SHOP BASKETINFO ###';

			return $this->Template->parse();
		}


		return parent::generate();
	}


	/**
	 * Generate module
	 */
	protected function compile() {
		global $objPage;
				
		$this->init();
		
		$this->basketManager->checkModification($this->Input);
		
		// Get current "jumpTo" page
		$objJumpToPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
								  ->limit(1)
								  ->execute($this->jumpTo);

		if ($objJumpToPage->numRows)
		{
			// generate basket link
			$this->Template->strUrl = $this->generateFrontendUrl($objJumpToPage->row())."?a=basket";
		}
		
		$this->Template->articleCount = $this->basketManager->getAllArticleCount();
		if(($this->i7shop_basket_info_always_visible) OR ($this->Template->articleCount > 0)) {
			$this->Template->show = true;
		}
		else {
			$this->Template->show = false;
		}
		
		// make nice text from language file
		// -todo- sould be placed in module / or tl_i7shop
		$this->Template->info_text = sprintf($GLOBALS['TL_LANG']['i7SHOP']['BASKETINFO'], $this->Template->articleCount, "<a href=\"".$this->Template->strUrl."\">", "</a>");
		
		
		// hide if the current page is set to hide
		if($this->i7shop_hide_on_page) {
			$pages = unserialize($this->i7shop_hide_on_page);
			if(is_array($pages)) {
				foreach($pages as $page) {
					if($objPage->id == $page) {
						$this->Template->show = false;
					}
				}
			}
		}
	}
}