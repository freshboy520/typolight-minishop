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
 * Class BaseShop
 *
 * Provide methods regarding i7Shop.
 * Every shopmodule will be inherited from this class
 * @copyright  Jonas Schnelli / include7 AG 2008
 * @author     Jonas Schnelli / include7 AG <jonas.schnelli@incude7.ch>
 * @package    i7Shop
 */


class BaseShop extends Module {

	protected $currency = "EUR";
	protected $currencyFormat = '{$c} %01.2f';
	protected $discounts = array(0,3,5,10,20);
	
	protected $paymentAccountId;
	protected $paymentUserId;
	protected $paymentTransactionPassword;
	protected $paymentAdminactionPassword;
	
	
	/* CURRENT LOGGED USER */
	protected $logedInClient;
	
	
	public function init() {
		/* Load the Basket */
		
		error_reporting(E_ALL ^ E_NOTICE);
		
		// get all config values from shopSystem
		$this->setConfigByShopSystem($this->i7shop_shopsystem);
		
		
		$this->basketManager = ShopBasketManager::getInstance();
		$this->basketManager->setSessionObject($this->Session);
		$this->basketManager->loadBasket();

		if($this->Session->get('i7shop_client')) {
			$this->logedInClient = unserialize($this->Session->get('i7shop_client'));

		}

	}
	
	public function setConfigByShopSystem($id) {
		$objShop = Database::getInstance()->prepare("SELECT * FROM tl_i7shop WHERE id=?")
									   ->execute($id);
		if($objShop->numRows >= 1) {
			// shop found
			$this->currency = $objShop->maincurrency;
			$this->currencyFormat = $objShop->currencyformat;
		}
	}
	
	
	public function saveState() {
		$this->basketManager->saveBasket();
		$this->Session->set('i7shop_client', serialize($this->logedInClient));

	}
	
	/**
	 * clearOrderAndBasket
	 *
	 * @return void
	 * @author Jonas Schnelli
	 **/
	public function clearOrderAndBasket()
	{
		$this->basketManager->emptyBasket();
		$this->Session->set('i7shop_order', false);
	}
	
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new Template('be_wildcard');
			$objTemplate->wildcard = '### SHOP ###';

			return $objTemplate->parse();
		}
		$strBuffer = parent::generate();
		return $strBuffer;
	}


	/**
	 * Generate module
	 */
	protected function compile() {
		
	}

	/**
	 * generateList
	 *
	 * @return a array to add to the template
	 * @author Jonas Schnelli
	 **/
	protected function generateList($params) {
		// load articles
		$params = array_merge($this->getSettings(), $params);
		
		$category = ShopCategory::load($params['categorieId']);
		$articles = ShopArticle::loadByParentId($category->id);

		$this->Template->category = $category;

		
		$parsedArticles = array();
		foreach($articles as $article) {
			// parse a template for every article
			$parsedArticles[] = $this->parseHtml($article->render($params), array());
		}
		$this->Template->articles = $parsedArticles;
		return $templateVars;
	}
	
	/**
	 * generateBasketList
	 *
	 * @return a array to add to the template
	 * @author Jonas Schnelli
	 **/
	protected function generateBasketList($params = array()) {
		// load articles
		$vars = array();
		
		if(!isset($params['template'])) $params['template'] = "shoparticle_basket";

		$renderParams = $this->getSettings();
		$renderParams['template'] = $params['template'];
		$renderParams['action'] = 'basket';
		
		$articles = $this->basketManager->getAllArticles();

		$parsedArticles = array();
		foreach($articles as $article) {
			// parse a template for every article
			$parsedArticles[] = $this->parseHtml($article->render($renderParams), array());
		}
		$vars['total'] = ShopHelpers::makePrice(
			$this->currency,
			$this->basketManager->getTotal($renderParams),
			$this->currencyFormat
			
			);
		$vars['articles'] = $parsedArticles;
		return $vars;
	}
	
	
	
	
	
	
	
	
	

	/**
	 * parseHtml
	 *
	 * @return replaced text as string
	 * @author jonas schnelli
	 **/
	public function parseHtml($text, $vars)
	{
		$_REQUEST['tl_i7shop_tempvars'] = $this->getSettings();
		foreach($vars as $key => $value) {
			$_REQUEST['tl_i7shop_tempvars'][$key] = $value;
		}

		$newText = preg_replace_callback('/{\$(.*)}/msiU', create_function(
		            // single quotes are essential here,
		            // or alternative escape all $ as \$
		            '$matches',
		            'return $_REQUEST[tl_i7shop_tempvars][$matches[1]];'
		        ),
		        $text);
		
		unset($_REQUEST['tl_i7shop_tempvars']);
		return $newText;
	}
	
	
	public function getSettings() {
		$settings = array();
		$settings['currency'] = $this->currency;
		$settings['currencyFormat'] = $this->currencyFormat;
		
		$settings['baselink'] = $this->getBaseLink();
		
		$settings['discounts'] = $this->discounts;
		$settings['discount'] = $this->getDiscount();
		
		$settings['payment_account_id'] 							= $this->paymentAccountId;
		$settings['payment_user_id'] 									= $this->paymentUserId;
		$settings['payment_transaction_password'] 		= $this->paymentTransactionPassword;
		$settings['payment_adminaction_password'] 		= $this->paymentAdminactionPassword;
		

		
		return $settings;
	}
	
	/**
	 * getBaseLink
	 *
	 * @return returns the baselink for all actions
	 * @author jonas schnelli
	 **/
	function getBaseLink() {
		list($baseLink) = split("\?", $this->Environment->requestUri);
		$baseLink = ltrim($baseLink, "/");
		return $baseLink;
	}
	
	/**
	 * setLogedInClient
	 *
	 * @return void
	 * @author Jonas Schnelli
	 **/
	public function setLogedInClient($client)
	{
		$this->logedInClient = $client;
	}
	/**
	 * getLogedInClient
	 *
	 * @return logedInClient:ShopClient
	 * @author Jonas Schnelli
	 **/
	public function getLogedInClient()
	{
		return $this->logedInClient;
	}
	
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author /bin/bash: niutil: command not found
	 **/
	public function getDiscount()
	{
		if($this->logedInClient) {
			return $this->discounts[$this->logedInClient->getCustomerGroup()-1];
		}
		return 0;
	}
	
	
	public function buildWidgets($arrFields, $validate) {
		$arrWidgets = array();
		// Initialize widgets
		foreach ($arrFields as $arrField)
		{
			$strClass = $GLOBALS['TL_FFL'][$arrField['inputType']];
			$strFile = sprintf('%s/system/modules/frontend/%s.php', TL_ROOT, $strClass);

			if (!file_exists($strFile))
			{
				continue;
			}

			$arrField['eval']['required'] = $arrField['eval']['mandatory'];
			$objWidget = new $strClass($this->prepareForWidget($arrField, $arrField['name'], $arrField['value']));
			
			// Validate widget
			if ($validate)
			{
				$objWidget->validate();
			}

			$arrWidgets[] = $objWidget;
		}
		
		return $arrWidgets;
	}
	
	public function validateWidgets($arrFields) {
		$arrWidgets = array();
		$noerrors = true;
		
		// Initialize widgets
		foreach ($arrFields as $arrField)
		{
			$strClass = $GLOBALS['TL_FFL'][$arrField['inputType']];
			$strFile = sprintf('%s/system/modules/frontend/%s.php', TL_ROOT, $strClass);

			if (!file_exists($strFile))
			{
				continue;
			}

			$arrField['eval']['required'] = $arrField['eval']['mandatory'];
			$objWidget = new $strClass($this->prepareForWidget($arrField, $arrField['name'], $arrField['value']));
			// Validate widget

			$objWidget->validate();

			if ($objWidget->hasErrors())
			{
				$noerrors = false;
			}
		
			$arrWidgets[] = $objWidget;
		}
		
		return $noerrors;
	}
	
	
	

}



?>
