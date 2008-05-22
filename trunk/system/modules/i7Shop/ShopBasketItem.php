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
 * Class ShopBasketItem
 *
 * Coreclass for storing the basketitem – for i7Shop
 * 
 * @copyright  Jonas Schnelli / include7 AG 2008
 * @author     Jonas Schnelli / include7 AG <jonas.schnelli@incude7.ch>
 * @package    i7Shop
 */

/* -todo- 
		document functions */

class ShopBasketItem {
	protected $ShopArticle;
	protected $quantity = 0;
	public function __construct($ShopArticle) {
		$this->ShopArticle = $ShopArticle;
		$this->quantity = 1;
	}
	public function __get($name) {
		return $this->ShopArticle->$name;
	}
	
	public function __tostring() {
		return $this->title." (".$this->artnr."), SinglePrice: ".$this->price.", Quantity: ".$this->quantity.", Subtotal: ".$this->total();
	}
	
	public function render($settings = array()) {
		$settings['additionalVars'] = array();
		$settings['additionalVars']['quantity'] = $this->quantity();
	
		
		$settings['additionalVars']['subtotal'] = ShopHelpers::makePrice($settings['currency'], $this->total($settings), $settings['currencyFormat']);
		return $this->ShopArticle->render($settings);
	}
	public function row() {
		return $this->row;
	}
	
	public function total($params=array()) {
		$total = $this->quantity*$this->price;
		if($params['discount']) {
			$total = $total - ($total / 100 * $params['discount']);
		}
		return $total;
	}
	
	public function quantity() {
		return $this->quantity;
	}
	public function setQuantity($value) {
		$this->quantity = $value;
	}
	

	
}

?>