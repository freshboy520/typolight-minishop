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
 * Class ShopArticle
 *
 * Coreclass for keeping a article – for i7Shop
 * 
 * @copyright  Jonas Schnelli / include7 AG 2008
 * @author     Jonas Schnelli / include7 AG <jonas.schnelli@incude7.ch>
 * @package    i7Shop
 */

class ShopArticle extends Controller {
	protected $dbRow;
	protected $currencyFormat = '{$c} {$p}';
	protected $currency = "USD";
	
	/**
	 * load
	 * peer Methode to grab a article
	 * @return Instance of a ShopArtcile:ShopArticle
	 * @author jonas schnelli 
	 **/
	static function load($id=0) {
		$objArticles = Database::getInstance()->prepare("SELECT * FROM tl_i7shop_articletree WHERE id=?")
									   ->execute($id);
		$articles = $objArticles->fetchAllAssoc();
		if($articles) {
			return self::generateFromRow($articles[0]);
		}
		return false;
	}
	
	
	/**
	 * loadByArticleNumber
	 * peer Methode to grab a article by article number
	 * @return Instance of a ShopArtcile:ShopArticle
	 * @author jonas schnelli 
	 **/
	static function loadByArticleNumber($id=0) {
		$objArticles = Database::getInstance()->prepare("SELECT * FROM tl_i7shop_articletree WHERE artnr=?")
									   ->execute($id);
		$articles = $objArticles->fetchAllAssoc();
		if($articles) {
			return self::generateFromRow($articles[0]);
		}
		return false;	
	}
	
	
	/**
	 * loadByParentId
	 * peer Methode to grab a branch of articles by articleid (for trees / lists)
	 * @return Array with Instances of a ShopArtciles:Array
	 * @author jonas schnelli 
	 **/
	static function loadByParentId($id=0) {
		$objArticles = Database::getInstance()->prepare("SELECT * FROM tl_i7shop_articletree WHERE pid=?")
									   ->execute($id);
		$articleArray = array();
		foreach($objArticles->fetchAllAssoc() as $article) {

			$articleArray[] = self::generateFromRow($article);
		}
		return $articleArray;
	}

	/**
	 * generateFromRow
	 * create a Instance from a DB Row
	 * @return Instance of a ShopArtcile:ShopArtcile
	 * @author jonas schnelli 
	 **/	
	static function generateFromRow($article) {
		$article = new ShopArticle($article);
		return $article;
	}
	
	public function __construct($row) {
		$this->dbRow = $row;
	}
	
	/**
	 * setCurrency
	 * 
	 * @return void
	 * @author jonas schnelli 
	 **/
	public function setCurrency($value) {
		$this->currency = $value;
	}
	
	
	/**
	 * render
	 *
	 * @return template html code of the article
	 * @author jonas schnelli
	 **/
	function render($params = array())
	{
		if(!$params['currency']) $params['currency'] = '{$currency}';
		if(!$params['baselink']) $params['baselink'] = '{$baselink}';
		if(!$params['template']) $params['template'] = 'shoparticle_basic';
		
		if(!$params['action']) $params['action'] = "{$action}";
		if(!$params['additionalVars']) $params['additionalVars'] = array();
		
		if(!$params['currencyFormat']) $params['currencyFormat'] = "{$c} %f "; //"%01.2f";
	

		$article = $this;

		$objTemplate = new FrontendTemplate($params['template']);
		// set the currency
		$article->setCurrency($params['currency']);

		/* -todo-  image sizes must be in module config */
		$objTemplate->image = $this->getImage($this->urlEncode($article->singleSRC), 100,false);
		$objTemplate->imageFullSize = $this->getImage($this->urlEncode($article->singleSRC), 500,false);
		
		$objTemplate->discountPercent = $params['discount'];
		$objTemplate->discount = $this->price/100*$params['discount'];
		$objTemplate->price = ShopHelpers::makePrice($params['currency'], $this->price-$objTemplate->discount, $params['currencyFormat']);
		
		$objTemplate->addToBasketLink = $params['baselink']."?a=".$params['action']."&addItem=".$article->id;
		$objTemplate->addToBasketText = "add to basket";
		
		$objTemplate->deleteFromBasketLink = $params['baselink']."?a=".$params['action']."&delItem=".$article->id;
		$objTemplate->deleteFromBasketText = "remove";
		
		
		
		$objTemplate->article = $article;
		
		// add additionalVars
		foreach($params['additionalVars'] as $key => $value) {
			$objTemplate->$key = $value;
		}
		return $objTemplate->parse();
	}

	
	public function __get($field) {
		switch($field) {
			case "row":
				return $this->dbRow;
				break;
			default:
				return $this->dbRow[$field];
		}
	}
	

}

?>