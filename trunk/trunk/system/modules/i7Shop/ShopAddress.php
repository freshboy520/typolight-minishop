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
 * Class ShopAddress
 *
 * Coreclass for keeping a address for i7Shop
 * 
 * @copyright  Jonas Schnelli / include7 AG 2008
 * @author     Jonas Schnelli / include7 AG <jonas.schnelli@incude7.ch>
 * @package    i7Shop
 */

class ShopAddress {

	protected $rawData;
	protected $type;  // 0 = normal, 1 = shippaddress
	
	

	public function __construct($data, $type=0) {
		$this->rawData = $data;
		$this->type = $type;
	}
	
	public function __tostring() {
		$prefix = "";
		if($this->type == 1) {
			$prefix = "shp_";
		}
		$address = $this->rawData[$prefix.'firstname']." ".$this->rawData[$prefix.'lastname']."\r\n";
		$address .= $this->rawData[$prefix.'address1']."\r\n";
		if($this->rawData[$prefix.'address2']) $address .= $this->rawData[$prefix.'address2']."\r\n";
		$address .= $this->rawData[$prefix.'country']."-".$this->rawData[$prefix.'zipcode']." ".$this->rawData[$prefix.'location']."\r\n";
		
		
		return $address;
	}
	
	/**
	 * store
	 * put all data into db
	 * @return id of the db record
	 * @author jonas schnelli 
	 **/
	public function store() {
		// sa
		// get db
		$objArticles = Database::getInstance()->prepare("SELECT * FROM tl_i7shop_articletree WHERE pid=?")
									   ->execute($id);
	}
	
	/**
	 * getClientName
	 * give a nice client_name string back
	 * @return clientname:String
	 * @author jonas schnelli 
	 **/
	public function getClientName() {
		return $this->firstname." ".$this->lastname;
	}
	
	/**
	 * getEmail
	 * 
	 * @return email:String
	 * @author jonas schnelli 
	 **/
	public function getEmail() {
		$prefix = "";
		if($this->type == 1) {
			$prefix = "shp_";
		}
		
		return $this->rawData[$prefix.'email'];
	}
	
	public function __get($key) {
		switch($key) {
			case "row":
				return $this->rawData;
				break;
			default:
				return $this->rawData[$key];
				break;
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	/**
	 * getFields
	 * generate the widgets
	 * @return widgets:Array
	 * @author jonas schnelli 
	 **/
	static function getFields($address1 = array(), $countries = array(), $prefix = "", $withEmail=false) {
		$arrFields = array
		(
	   		'company' => array
				(
					'name' => $prefix.'company',
					'label' => $GLOBALS['TL_LANG']['i7SHOP']['text_company'],
					'inputType' => 'text',
					'value' => $address1[$prefix.'company'],
					'eval' => array('rgxp'=>'alnum', 'maxlength'=>64)
				),
				'firstname' => array
				(
					'name' => $prefix.'firstname',
					'label' => $GLOBALS['TL_LANG']['i7SHOP']['text_firstname'],
					'value' => $address1['firstname'],
					'inputType' => 'text',
					'eval' => array('rgxp'=>'alnum', 'mandatory'=>true, 'maxlength'=>64)
				),
				'lastname' => array
				(
					'name' => $prefix.'lastname',
					'label' => $GLOBALS['TL_LANG']['i7SHOP']['text_lastname'],
					'value' => $address1[$prefix.'lastname'],
					'inputType' => 'text',
					'eval' => array('rgxp'=>'alnum', 'mandatory'=>true, 'maxlength'=>64)
				),
				'address1' => array
				(
					'name' => $prefix.'address1',
					'label' => $GLOBALS['TL_LANG']['i7SHOP']['text_address1'],
					'value' => $address1[$prefix.'address1'],
					'inputType' => 'text',
					'eval' => array('rgxp'=>'alnum', 'mandatory'=>true, 'maxlength'=>64)
				),
				/*'address2' => array
				(
					'name' => $prefix.'address2',
					'label' => $GLOBALS['TL_LANG']['i7SHOP']['text_address2'],
					'value' => $address1[$prefix.'address2'],
					'inputType' => 'text',
					'eval' => array('rgxp'=>'alnum', 'maxlength'=>64)
				),
				'address3' => array
				(
					'name' => $prefix.'address3',
					'label' => $GLOBALS['TL_LANG']['i7SHOP']['text_address3'],
					'value' => $address1[$prefix.'address3'],
					'inputType' => 'text',
					'eval' => array('rgxp'=>'alnum', 'maxlength'=>64)
				),
				*/
				'zipcode' => array
				(
					'name' => $prefix.'zipcode',
					'label' => $GLOBALS['TL_LANG']['i7SHOP']['text_zipcode'],
					'value' => $address1[$prefix.'zipcode'],
					'inputType' => 'text',
					'eval' => array('rgxp'=>'extnd', 'mandatory'=>true, 'maxlength'=>12)
				),
				'location' => array
				(
					'name' => $prefix.'location',
					'label' => $GLOBALS['TL_LANG']['i7SHOP']['text_location'],
					'value' => $address1[$prefix.'location'],
					'inputType' => 'text',
					'eval' => array('rgxp'=>'alnum', 'mandatory'=>true, 'maxlength'=>64)
				),
				'country' => array
				(
					'name' => $prefix.'country',
					'label' => &$GLOBALS['TL_LANG']['i7SHOP']['text_country'],
					'value' => $address1[$prefix.'country'],
					'inputType' => 'select',
					'options'   => $countries,
					'eval' => array('rgxp'=>'alnum', 'mandatory'=>true, 'maxlength'=>64)
				),
		);
		
		if($withEmail) {
		/*	$arrFields['email'] = array
			(
				'name' => 'email',
				'label' => $GLOBALS['TL_LANG']['i7SHOP']['text_email'],
				'inputType' => 'text',
				'value' => $address1[$prefix.'email'],
				'eval' => array('rgxp'=>'email', 'mandatory'=>true, 'maxlength'=>128)
			);*/
		}
		
		
		return $arrFields;
	}
	
	
}
		
?>