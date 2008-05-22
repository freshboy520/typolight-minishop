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
 * Class ShopClient
 *
 * Coreclass for handling a client – for i7Shop
 * 
 * @copyright  Jonas Schnelli / include7 AG 2008
 * @author     Jonas Schnelli / include7 AG <jonas.schnelli@incude7.ch>
 * @package    i7Shop
 */

/* -todo- 
		document functions */

class ShopClient {

	protected $billAddress;
	protected $shipAddress;

	protected $registerDate;
	protected $username;
	protected $password;
	
	protected $customerGroup;
	protected $specialRabat;
	
	protected $clientId;
	
	protected $groupPrefix = "shop_clientgroup_";
	protected $shopGroupName = "shop_client";
	
	static function checkLogin($username, $password) {
		$objArticles = Database::getInstance()->prepare("SELECT * FROM tl_member WHERE username=? AND password=?")
									   ->execute($username, sha1($password));
		$users = $objArticles->fetchAllAssoc();
		if($users) {
			return self::createFromRow($users[0]);
		}
		return false;
	}
	
	static function checkUsernameExists($username) {
		$objArticles = Database::getInstance()->prepare("SELECT * FROM tl_member WHERE username=?")
									   ->execute($username);
		$articles = $objArticles->fetchAllAssoc();
		if($articles) {
			return true;
		}
		return false;
	}
	
	static function createFromRow($row) {
		$client = new ShopClient(array("register_username" => $row['email'], "register_password" => $row['password']));
		$client->setCustomerGroup($client->getCustomerGroupByBlob($row['groups']));
		$client->setId($row['id']);
		return $client;
	}
	
	public function getCustomerGroupByBlob($groupBlob) {
		$groupArray = unserialize($groupBlob);
		$higestLevel = 1; // set lowest level by default
		foreach($groupArray as $groupid) {
			$name = $this->getGroupName($groupid);
			$level = str_replace($this->groupPrefix, "", $name);
			if($level > $higestLevel) $higestLevel = $level;
		}
		return $higestLevel;
	}
	
	public function setId($id) {
		$this->clientId = $id;
	}
	public function getId() {
		return $this->clientId;
	}
	
	
	public function __construct($params) {
		$this->username = $params['register_username'];
		$this->password = $params['register_password'];
		
		$this->customerGroup = 1;
	}
	
	public function setCustomerGroup($group) {
		$this->customerGroup = $group;
	}
	public function getCustomerGroup() {
		return $this->customerGroup;
	}
	
	public function store() {
		$this->checkGroups();;
		$groups = array($this->getGroupId($this->customerGroup), $this->getShopGroupId());
		$transaction = Database::getInstance()->prepare("INSERT INTO tl_member SET tstamp=?, firstname=?,lastname=?, username=?, email=?, password=?, groups=?, login='1'")
									   ->execute(strtotime("now"), $this->username, $this->username, $this->username, $this->username, sha1($this->password), $groups);
									
		$this->setId($transaction->insertId);
	}
	
	public function getGroupId($num) {
		$obj = Database::getInstance()->prepare("SELECT * FROM tl_member_group WHERE name = ?")
									   ->execute($this->groupPrefix.$num);
		$groups = $obj->fetchAllAssoc();
		if($groups) {
			return $groups[0]['id'];
		}
		return false;							
	}
	
	public function getShopGroupId() {
		$obj = Database::getInstance()->prepare("SELECT * FROM tl_member_group WHERE name = ?")
									   ->execute($this->shopGroupName);
		$groups = $obj->fetchAllAssoc();
		if($groups) {
			return $groups[0]['id'];
		}
		return false;							
	}
	
	public function getGroupName($id) {
		$obj = Database::getInstance()->prepare("SELECT name FROM tl_member_group WHERE id = ?")
									   ->execute($id);
		$groups = $obj->fetchAllAssoc();
		if($groups) {
			return $groups[0]['name'];
		}
		return false;							
	}
	
	public function checkGroups() {
		if(!$this->getGroupId(1)) {
			Database::getInstance()->prepare("INSERT INTO tl_member_group SET name=?")
									   ->execute($this->groupPrefix.'1');
		}
		if(!$this->getGroupId(2)) {
			Database::getInstance()->prepare("INSERT INTO tl_member_group SET name=?")
									   ->execute($this->groupPrefix.'2');
		}
		if(!$this->getGroupId(3)) {
			Database::getInstance()->prepare("INSERT INTO tl_member_group SET name=?")
									   ->execute($this->groupPrefix.'3');
		}
		if(!$this->getGroupId(4)) {
			Database::getInstance()->prepare("INSERT INTO tl_member_group SET name=?")
									   ->execute($this->groupPrefix.'4');
		}
		if(!$this->getGroupId(5)) {
			Database::getInstance()->prepare("INSERT INTO tl_member_group SET name=?")
									   ->execute($this->groupPrefix.'5');
		}
		
		if(!$this->getShopGroupId()) {
			Database::getInstance()->prepare("INSERT INTO tl_member_group SET name=?")
									   ->execute($this->shopGroupName);
		}
	}
	
}

?>