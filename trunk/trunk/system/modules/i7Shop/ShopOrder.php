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
 * Class ShopOrder
 *
 * Coreclass to controll and store a order – for i7Shop
 * 
 * @copyright  Jonas Schnelli / include7 AG 2008
 * @author     Jonas Schnelli / include7 AG <jonas.schnelli@incude7.ch>
 * @package    i7Shop
 */

/* -todo- 
		document functions */


class ShopOrder {

	protected $orderTime;
	protected $clientIp;
	protected $basket;
	protected $orderId; 
	
	protected $shopClient;
	protected $shopClientId;
	
	protected $billAddress;
	protected $shipAddress;
	protected $orderStatus; // 0 = created, 1 = ccpayed & placed, 2 = ..
	protected $paymentDetails;
	
	protected $basketTotalCost;
	protected $shippmentCosts;
	protected $taxCost;
	protected $taxPercentage;
	protected $paymentAdapter = "iPayment";
	
	protected $paymentBookingNumber; 
	
	public function __construct($params) {
		$this->basket = $params['basket'];
		$this->clientIp = $params['ip'];
		$this->shopClient = $params['shopClient'];
		$this->orderTime = date("Y-m-d H:i:s");
		$this->orderStatus = 0;
		$this->paymentBookingNumber = 0;
		$this->taxCost = 0;
	}
	
	public function setClient($client) {
		$this->shopClient = $client;
	}
	public function setClientId($id) {
		$this->shopClientId = $id;
	}
	
	public function setBasket($basket) {
		$this->basket = $basket;
	}
	public function getBasket() {
		return $this->basket;
	}

	
	public function hasShippingAddress() {
		if($this->shipAddress->row['shp_firstname']) return true;
		return false;
	}
	public function setBillAddress($addr) {
		$this->billAddress = $addr;
	}
	public function getBillAddress() {
		return $this->billAddress;
	}
	public function setShipAddress($addr) {
		$this->shipAddress = $addr;
	}
	public function getShipAddress() {
		return $this->shipAddress;
	}
	public function deleteShipAddress() {
		$this->shipAddress = "";
	}
	public function setOrderStatus($status) {
		$this->orderStatus = $status;
	}
	

	
	public function getPaymentDetails() {
		return $this->paymentDetails;
	}
	public function setPaymentDetails($paymentDetails) {
		$this->paymentDetails = $paymentDetails;
	}
	
	
	/**
	 * store
	 * will save the order in the database
	 * @return void
	 * @author Jonas Schnelli
	 **/
	public function store()
	{
		$wholeOrderData = serialize($this);
		
		if(!$this->paymentBookingNumber) $this->paymentBookingNumber = "0";
		
			$objArticles = Database::getInstance()->prepare("INSERT INTO tl_i7shop_order SET order_date=?, order_status=?, client_name=?,client_id=?, data=?, shipping=?, taxes=?, basket_total=?, total=?, client_bill_address=?, client_ship_address=?, basket=?, payment_booking_number=?")
										   ->execute(strtotime("now"), $this->orderStatus,  $this->getBillAddress()->getClientName(),$this->shopClient->getId(),$wholeOrderData,
												$this->getShippmentCosts(), $this->getTaxeCost(), $this->getBasketTotal(), $this->getEndTotal(),
												$this->getBillAddress()->__tostring(), ($this->getShipAddress()) ? $this->getShipAddress()->__tostring():0, $this->getBasket()->__tostring(), $this->paymentBookingNumber
										);
		$this->setId($objArticles->insertId);
	}
	
	/**
	 * setId
	 * set the order id
	 * @return void
	 * @author Jonas Schnelli
	 **/
	public function setId($id)
	{
		$this->orderId = $id;
	}
	
	
	/**
	 * getId
	 * returns the order id
	 * @return id:String
	 * @author Jonas Schnelli
	 **/
	public function getId()
	{
		return $this->orderId;
	}
	
	
	/**
	 * getFormatedPaymentDetails
	 *
	 * @return void
	 * @author /bin/bash: niutil: command not found
	 **/
	public function getFormatedPaymentDetails()
	{
		$text = "card type: ".$this->paymentDetails['cctype']."<br />\n";
		$text .= "cardnumber: ".$this->paymentDetails['ccnumber']."<br />\n";
		$text .= "name: ".$this->paymentDetails['ccname']."<br />\n";
		
		return $text;
	}
	
	
	/**
	 * getShippmentCosts
	 *
	 * @return Number
	 * @author /bin/bash: niutil: command not found
	 **/
	public function getShippmentCosts()
	{
		return $this->shippmentCosts;
	}
	
	
	/**
	 * getShippmentCosts
	 *
	 * @return void
	 * @author Jonas Schnelli
	 **/
	public function setShippmentCosts($cost)
	{
		$this->shippmentCosts = $cost;
	}
	
	
	
	public function setTaxPercentage($percent) {
		$this->taxPercentage = $percent;
	}
	
	public function getTaxPercentage() {
		return $this->taxPercentage;
	}
	
	/**
	 * getShippmentCosts
	 *
	 * @return Number
	 * @author Jonas Schnelli
	 **/
	public function getTaxeCost()
	{
		return round($this->getBasketTotal() / 100 * $this->taxPercentage, 2);
	}
	
	/**
	 * getBasketTotal
	 *
	 * @return Number
	 * @author Jonas Schnelli
	 **/
	public function getBasketTotal()
	{
		return $this->basketTotalCost;
	}
	
	/**
	 * getBasketTotal
	 *
	 * @return Number
	 * @author Jonas Schnelli
	 **/
	public function setBasketTotal($total)
	{
		$this->basketTotalCost = $total;
	}
	
	
	/**
	 * getEndTotal
	 *
	 * @return Number
	 * @author Jonas Schnelli
	 **/
	public function getEndTotal()
	{
		if($GLOBALS['TL_SHOP']['SHOW_TAXES_ONLY']) {
			return $this->getBasketTotal()+$this->getShippmentCosts();
		}
		return $this->getBasketTotal()+$this->getShippmentCosts()+$this->getTaxeCost();
	}
	
	

	
	
	
	
	
	public function payOrder($params) {
		require_once("paymentAdapters/".$this->paymentAdapter.".php");
		$paymentProcess = new $this->paymentAdapter;

		$paymentProcess->debug = false;
		$paymentProcess->setAccountId($params['payment_account_id']);
		$paymentProcess->setUserId($params['payment_user_id']);
		$paymentProcess->setTransactionPassword($params['payment_transaction_password']);
		$paymentProcess->setAdminactionPassword($params['payment_adminaction_password']);
		
		
		
		$paymentProcess->setOrderId($this->orderId);
		$paymentProcess->setTransactionId($this->orderId);
		$paymentProcess->setCurrency("EUR"); // todo
		
		$paymentProcess->setAmount(str_replace(".", "", number_format($this->getEndTotal(), 2)));
	
		$paymentProcess->setCardType($this->paymentDetails['cctype']);
		$paymentProcess->setCardNumber($this->paymentDetails['ccnumber']);
		$paymentProcess->setExpMonth($this->paymentDetails['ccdatem']);
		$paymentProcess->setExpYear($this->paymentDetails['ccdatey']);
		$paymentProcess->setClientName($this->paymentDetails['ccname']);
		$paymentProcess->setCardCheckCode($this->paymentDetails['ccv']);
		
		
		$success = $paymentProcess->bill();
		if(!$success) {
			/* IS THERE WAS A ERROR, SET SOME VARS */
			$this->lastErrorCode = $paymentProcess->getErrorCode();
			$this->lastError = $paymentProcess->getErrorMesage();
			$this->debugResponde = $paymentProcess->getDebugResonse();
			return false;
		}
		
		$this->paymentBookingNumber = $paymentProcess->getBookNr();
		
		
		// success
		return true;
	}
	
	public function getLastError() {
		return $this->lastError;
	}
	public function getLastErrorCode() {
		return $this->lastErrorCode;
	}
	public function getDebugResponse() {
		return $this->debugResponde;
	}
	
	
	
	
	
	
	
	static function getPaymentFields($content = array()) {
		$years = array();
		for($i=date("Y");$i<(date("Y")+12); $i++) {
			
			$years[] = $i;
		}
		
		$arrFields = array
		(
	   		'cctype' => array
				(
					'name' => 'cctype',
					'label' => $GLOBALS['TL_LANG']['i7SHOP']['cctype'],
					'inputType' => 'select',
					'value' => $content['cctype'],
					'options' => array('' => $GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP3_CHOOSE'], 'mastercard' => "Mastercard", 'visacard' => "Visa"),
					'eval' => array('rgxp'=>'alnum', 'mandatory'=>true, 'maxlength'=>64)
				),
				'ccnumber' => array
				(
					'name' => 'ccnumber',
					'label' => $GLOBALS['TL_LANG']['i7SHOP']['ccnumber'],
					'value' => $content['ccnumber'],
					'inputType' => 'text',
					'eval' => array('rgxp'=>'alnum', 'mandatory'=>true, 'maxlength'=>64)
				),
				'ccname' => array
				(
					'name' => 'ccname',
					'label' => $GLOBALS['TL_LANG']['i7SHOP']['ccname'],
					'value' => $content['ccname'],
					'inputType' => 'text',
					'eval' => array('rgxp'=>'alnum', 'mandatory'=>true, 'maxlength'=>64)
				),
				'ccdatem' => array
				(
					'name' => 'ccdatem',
					'label' => $GLOBALS['TL_LANG']['i7SHOP']['ccdatem'],
					'value' => $content['ccdatem'],
					'inputType' => 'select',
					'options' => array('1' => "01",'2' => "02",'3' => "03",'4' => "04",'5' => "05",'6' => "06",'7' => "07",'8' => "08",'9' => "09",'10' => "10",'11' => "11",'12' => "12",),
					'eval' => array('rgxp'=>'alnum', 'mandatory'=>true, 'maxlength'=>64)
				),
				'ccdatey' => array
				(
					'name' => 'ccdatey',
					'label' => $GLOBALS['TL_LANG']['i7SHOP']['ccdatey'],
					'value' => $content['ccdatey'],
					'inputType' => 'select',
					'options' => $years,
					'eval' => array('rgxp'=>'alnum', 'mandatory'=>true, 'maxlength'=>64)
				),
				'ccv' => array
				(
					'name' => 'ccv',
					'label' => $GLOBALS['TL_LANG']['i7SHOP']['ccv'],
					'value' => $content['ccv'],
					'inputType' => 'text',
					'eval' => array('rgxp'=>'alnum', 'mandatory'=>true, 'maxlength'=>64)
				)
		);
		
		return $arrFields;
	}
	
	static function getLegalFields($content = array()) {
		$arrFields = array
		(
	   		'termsaccepted' => array
				(
					'name' => 'termsaccepted',
					'label' => $GLOBALS['TL_LANG']['i7SHOP']['termsaccepted'],
					'inputType' => 'checkbox',
					'eval' => array('mandatory'=>true)
				)
		);
		
		return $arrFields;
	}
	
}
?>