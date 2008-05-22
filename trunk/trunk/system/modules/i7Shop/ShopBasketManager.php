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
 * Class ShopBasketManager
 *
 * Controller for managing the basket – for i7Shop
 * 
 * @copyright  Jonas Schnelli / include7 AG 2008
 * @author     Jonas Schnelli / include7 AG <jonas.schnelli@incude7.ch>
 * @package    i7Shop
 */

/* -todo- 
		document functions */
		
class ShopBasketManager {
 	protected static $objInstance;
	protected $basket;
	protected $Session;
	protected $shipmentCosts;
	
	public static function getInstance()
	{
		if (!is_object(self::$objInstance))
		{
			self::$objInstance = new ShopBasketManager();
		}

		return self::$objInstance;
	}
	
	public function setSessionObject($Session) {
		$this->Session = $Session;
	}
	
	public function emptyBasket()
	{
		if($this->basket) {
			$this->basket->dropAll();
		}
	}
	public function newBasket()
	{
		$this->Session->set('basket', serialize(new ShopBasket()));
	}
	
	public function getAllArticleCount() {
		return count($this->basket->articles());
	}
	
	public function getAllArticles() {
		return $this->basket->articles();
	}
	
	public function changeQuantity($id, $quant = 1, $absolute=false) {
		$this->basket->changeQuantity($id,$quant, $absolute);
	}
	
	public function removeBasketItem($articleId) {
		$this->basket->drop($articleId);
	}
	public function addBasketItem($article) {
		$this->basket->addArticle($article);
		return true;
	}

	
	public function getTotal($params=array()) {
		$total = 0;
		foreach($this->getAllArticles() as $article) {
			$total += $article->total($params);
		}
		
		return $total;
	}
	
	public function checkModification($input) {
		if($input->get('addItem')) {
			// okay.. add a item
			
			// load item
			$article = ShopArticle::load($input->get('addItem'));
			if($article) {
				$this->addBasketItem($article);
			}
		}
		
		if($input->get('addArticleNumber')) {
			// okay.. add a item
			
			// load item
			$article = ShopArticle::loadByArticleNumber($input->get('addArticleNumber'));
			if($article) {
				$this->addBasketItem($article);
			}
		}
		
		
		
		
		if($input->get('delItem')) {
	
				$this->removeBasketItem($input->get('delItem'));

		}
		
		if($input->get('updateBasket')) {
			// now i have to user $_REQUEST because the Input class can't give out the whole parsed array
			foreach($_REQUEST as $key => $value) {
				if(preg_match("/i7shop_quantity_([0-9]*)/", $key, $matches)) {
					$id = $matches[1];
					$newQuantity = htmlentities(strip_tags($value)); // basic security
					
					
					$this->changeQuantity($id, $newQuantity, true);
					
				}
			}
		}
	}
	
	
	public function loadBasket() {
		// check if a basket exists
		$basket = $this->Session->get('basket');
		if(!$basket) {
			$basket = new ShopBasket();
		}
		else {
			$basket = unserialize($basket);
		}
		$this->basket = $basket;
	}
	
	public function saveBasket() {
		$this->Session->set('basket', serialize($this->basket));
	}
	
	public function getBasket() {
		return $this->basket;
	}
}
?>