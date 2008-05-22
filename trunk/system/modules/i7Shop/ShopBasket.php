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
 * Class ShopBasket
 *
 * Coreclass for storing the basket – for i7Shop
 * 
 * @copyright  Jonas Schnelli / include7 AG 2008
 * @author     Jonas Schnelli / include7 AG <jonas.schnelli@incude7.ch>
 * @package    i7Shop
 */

class ShopBasket {
	protected $articles;
	
	public function __construct() {
		$this->articles = array();
	}
	
	public function __tostring() {
		return $this->getNiceContents();
	}
	
	/**
	 * addArticle
	 * add a article into basket
	 * @return void
	 * @author jonas schnelli 
	 **/
	public function addArticle($object) {
		if($this->articleExists($object->id)) {
			$this->changeQuantity($object->id,1);
		}
		else {
			$this->articles[] = new ShopBasketItem($object);	
		}
	}
	
	/**
	 * articles
	 * returns all articles
	 * @return articles:Array
	 * @author jonas schnelli 
	 **/
	public function articles() {
		return $this->articles;
	}
	
	/**
	 * articleExists
	 * return true/false if a articleid exists in basket
	 * @return exists:BOOL
	 * @author jonas schnelli 
	 **/
	protected function articleExists($id) {
		foreach($this->articles as $article) {
			if($article->id == $id) {
				return true;
			}
		}
		return false;
	}
	
	/**
	 * changeQuantity
	 * 
	 * @return void
	 * @author jonas schnelli 
	 **/
	public function changeQuantity($id, $quant = 1, $absolute = false) {
		foreach($this->articles as $article) {
			if($article->id == $id) {
				
				$article->setQuantity(($absolute) ? $quant : $article->quantity()+$quant);
				if($article->quantity() == 0) {
					$this->drop($article->id);
				}
			}
		}
	}
	

	/**
	 * drop
	 * 
	 * @return void
	 * @author jonas schnelli 
	 **/
	public function drop($id) {

		$newarray = array();
		foreach($this->articles as $article) {
			if($article->id != $id) $newarray[] = $article;
		}
		
		$this->articles = $newarray;
	}
	
	
	/**
	 * dropAll
	 * 
	 * @return void
	 * @author jonas schnelli 
	 **/
	public function dropAll() {
		$this->articles = array();
	}
	
	
	/**
	 * getNiceContents
	 * 
	 * @return nice basket string:String
	 * @author jonas schnelli 
	 **/
	public function getNiceContents() {
		$text = "";
		foreach($this->articles as $article) {
			$text .= $article."\n";
		}
		$text.="\n\n";
		$text .= "Total: ".$this->getTotal();
		
		return $text;
	}
	
	/**
	 * getTotal
	 * 
	 * @return total:String
	 * @author jonas schnelli 
	 **/
	/* -todo- */
	/* double function also in manager */
	public function getTotal() {
		$total = 0;
		foreach($this->articles as $article) {
			$total += $article->total();
		}
		
		return $total;
	}
}

?>