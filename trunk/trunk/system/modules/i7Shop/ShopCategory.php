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
 * Class ShopCategory
 *
 * Coreclass for handling a categorie – for i7Shop
 * 
 * @copyright  Jonas Schnelli / include7 AG 2008
 * @author     Jonas Schnelli / include7 AG <jonas.schnelli@incude7.ch>
 * @package    i7Shop
 */

/* -todo- 
		document functions */

class ShopCategory {
	
	protected $dbRow;
	
	static function load($id) {
		$objCategory = Database::getInstance()->prepare("SELECT * FROM tl_i7shop_articletree WHERE id=?")
									   ->execute($id);
		if(!$objCategory->numRows) return false;
		
		return new ShopCategory($objCategory);
	}
	
	public function __construct($obj) {
		$this->dbRow = $obj->fetchAssoc();
	}
	
	public function __get($field) {
		switch($field) {
			case "row":
				return $this->dbRow;
				break;
			case "categoryTitle":
				return $this->dbRow['title'];
				break;
			default:
				return $this->dbRow[$field];
		}
	}
}

?>