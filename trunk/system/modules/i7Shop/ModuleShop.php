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
 * Class ModuleShop
 *
 * Mainmodule for i7Shop.
 * 
 * @copyright  Jonas Schnelli / include7 AG 2008
 * @author     Jonas Schnelli / include7 AG <jonas.schnelli@incude7.ch>
 * @package    i7Shop
 */

class ModuleShop extends BaseShop {
	
	protected $strTemplate = 'shop_main';
	protected $paymentAdapter = 'iPayment';
	
	
	
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
		$this->init();
		$queryAction = $this->Input->get('a');
		if(!$queryAction) $queryAction = $this->Input->post('a');
		if(!$queryAction) $queryAction = "list";
		
		// basketManager check for modification:
		$this->basketManager->checkModification($this->Input);
		

		
		switch($queryAction) {
			case "list":
				// set the category
				$categorie = 1; //*todo*
				$categorie = $this->i7shop_defineRootCategorie;
				// add the artciles to the template
				
				$this->generateList(array("categorieId" => $categorie, "template" => "shoparticle_normal"));
			break;
			
			
			case "basket":
				$objTemplate = new FrontendTemplate("shopbasket_basic");

				$vars = $this->generateBasketList();
				foreach($vars as $key => $value) { $objTemplate->$key = $value; }
				
				$objTemplate->action = $queryAction;
				$objTemplate->startOrderLink = $this->getBaseLink()."?a=orderl";
				$objTemplate->formAction = $this->getBaseLink();
				$this->Template->code = $objTemplate->parse();
			break;
			
			///// ORDER LOGIN LOGIC
			case "orderl":
				$objTemplate = new FrontendTemplate("shoporder_login");
				
				// Form fields for Register
				$arrFields = array
				(
					'login_email' => array
					(
						'name' => 'login_email',
						'label' => $GLOBALS['TL_LANG']['i7SHOP']['text_loginusername'],
						'inputType' => 'text',
						'eval' => array('rgxp'=>'email', 'mandatory'=>true, 'maxlength'=>128)
					),
					'login_password' => array
					(
						'name' => 'login_password',
						'label' => $GLOBALS['TL_LANG']['i7SHOP']['text_loginpassword'],
						'inputType' => 'password',
						'eval' => array('mandatory'=>true, 'maxlength'=>128)
					),
				);
				
				// Form fields for Register
				$arrFieldsRegister = array
				(
					'email' => array
					(
						'name' => 'email',
						'label' => $GLOBALS['TL_LANG']['i7SHOP']['text_registerusername'],
						'inputType' => 'text',
						'eval' => array('rgxp'=>'email', 'mandatory'=>true, 'maxlength'=>128)
					),
					'password' => array
					(
						'name' => 'password',
						'label' => $GLOBALS['TL_LANG']['i7SHOP']['text_registerpassword'],
						'inputType' => 'password',
						'eval' => array('mandatory'=>true, 'maxlength'=>128)
					),
				);
				
				$validate = false;
				if($this->Input->post('FORM_SUBMIT') == "order_login_form") { 
					// unpopulate while submiting the form
					$populateFields = array();
					$login = true;
				}
				if($this->Input->post('FORM_SUBMIT') == "order_register_form") { 
					$validate = true;
					// unpopulate while submiting the form
					$populateFields = array();
				}
				
				$objTemplate->fields = $this->buildWidgets($arrFields, false);
				$objTemplate->register_fields = $this->buildWidgets($arrFieldsRegister, $validate);
				$objTemplate->userExistsAlready = false;
				$objTemplate->errorLogin = false;
				
				if($login) {
					$client = ShopClient::checkLogin($this->Input->post('login_email'),$this->Input->post('login_password'));
					if($client) {
						// okay.. found
						$this->setLogedInClient($client);
						$this->saveState();

						
						// redirect
						$this->redirect($this->getBaseLink()."?a=order");
					}
					else {
						$objTemplate->errorLogin = true;
					}
				}
				
				if($validate) {
					
					if( $this->validateWidgets($arrFieldsRegister)	) {
						// okay.. no error in register
					
						// check if username exists
						if(ShopClient::checkUsernameExists($this->Input->post('email'))) {
							// user exists already
							$objTemplate->userExistsAlready = true;
						}
						else {
							// create a new client
							$client = new ShopClient(array("register_username" => $this->Input->post('email'), "register_password" => $this->Input->post('password')));
							$client->store();

							// store into session
							$this->setLogedInClient($client);
							$this->saveState();
							
							$order = new ShopOrder(array());					
							$this->Session->set('i7shop_order', serialize($order));
												
							// redirect
							$this->redirect($this->getBaseLink()."?a=order");
						}
					}
				}
				
				$objTemplate->formAction = $this->getBaseLink();				
				$this->Template->order = $objTemplate->parse();
				break;
				
			///// ORDER ADDRESS LOGIC
			case "order":
				
				
				$objTemplate = new FrontendTemplate("shoporder_address");

				$populateFields = array();
				if($this->Session->get('i7shop_order')) {
					// there is already a order in the session
					$order = unserialize($this->Session->get('i7shop_order'));
					
					// populate fields
					$populateFields = $order->getBillAddress()->row;
					$populateFieldsShp = $order->getShipAddress()->row;
					if($order->hasShippingAddress()) $objTemplate->sa = 1;
				}
	
				if($this->getLogedInClient()) {
					// load the client
					$client = $this->getLogedInClient();
				}
				else {
					// no client!
					// timeout
					$this->redirect($this->getBaseLink()."?a=basket");
				}

				
				// hide / display shipping address
				/* REVIEW */
				if($this->Input->get('sa') == "1") {
					$objTemplate->sa = 1;
				}
				if($this->Input->post('sa') == "1") {
					$objTemplate->sa = 1;
				}
				if($this->Input->get('sa') == "0") {
					$objTemplate->sa = 0;
				}
				if($this->Input->post('sa') == "0") {
					$objTemplate->sa = 0;
				}	
				
				$validate = false;
				if($this->Input->post('FORM_SUBMIT') == "order_step_1") { 
					$validate = true;
					// unpopulate while submiting the form
					$populateFields = array();
				}
				
				$objTemplate->fields = $this->buildWidgets(ShopAddress::getFields($populateFields, $this->getCountries(), "", true), $validate);
				$objTemplate->shp_fields = $this->buildWidgets(ShopAddress::getFields($populateFieldsShp, $this->getCountries(),"shp_"), $validate);
				
				$objTemplate->legalFields = $this->buildWidgets(ShopOrder::getLegalFields($populateFields), $validate);
				
				// add: validate shippment
				$shippmentOkay = 1;
				if($objTemplate->sa) {
					$shippmentOkay = $this->validateWidgets(ShopAddress::getFields(array(), $this->getCountries(), "shp_"));
				}
				
				if($validate) {
					if($this->validateWidgets(ShopAddress::getFields(array(), $this->getCountries(), "", true)) && $this->validateWidgets(ShopOrder::getLegalFields()) && $shippmentOkay
					) {
						// okay.. prefect
						if(!$order) {
						$order = new ShopOrder(
								array("basket" => $this->basketManager->getBasket(),
										"ip" => $_SERVER['REMOTE_ADDR']
								));
						}
						else {
							$order->setBasket($this->basketManager->getBasket());
						}
						
	
						// set the related client
						$order->setClient($client);
						
						// set the address
						$order->setBillAddress(new ShopAddress($_REQUEST)); // need to send $_REQUEST because tl Input don't has a ParamHolder
						if($objTemplate->sa == 1) {
							$order->setShipAddress(new ShopAddress($_REQUEST, 1));
						}
						else {
							$order->deleteShipAddress();
						}
						
						// calculate shp cost
						$shpCosts = $GLOBALS['TL_SHOP']['SHIPPMENT_COSTS']['DEFAULT'];
						if(($_REQUEST['shp_country']) && $objTemplate->sa == 1) {
							if($GLOBALS['TL_SHOP']['SHIPPMENT_COSTS'][$_REQUEST['shp_country']]) {
								$shpCosts = $GLOBALS['TL_SHOP']['SHIPPMENT_COSTS'][$_REQUEST['shp_country']];
							}
						}
						elseif($_REQUEST['country']) {
							if($GLOBALS['TL_SHOP']['SHIPPMENT_COSTS'][$_REQUEST['country']]) {
								$shpCosts = $GLOBALS['TL_SHOP']['SHIPPMENT_COSTS'][$_REQUEST['country']];
							}
						}
						$order->setShippmentCosts($shpCosts);
						
						// calculate tax % cost
						$taxPercent = $GLOBALS['TL_SHOP']['TAX_PERCENTAGE']['DEFAULT'];
						if($GLOBALS['TL_SHOP']['TAX_PERCENTAGE'][$_REQUEST['country']]) {
							$taxPercent = $GLOBALS['TL_SHOP']['TAX_PERCENTAGE'][$_REQUEST['country']];
						}
						$order->setTaxPercentage($taxPercent);
						
						
						
						
						$this->Session->set('i7shop_order', serialize($order));
						
						// jump to order payment
						$this->redirect($this->getBaseLink()."?a=orderp");
					}
				}
				
				$objTemplate->legal_info = sprintf($GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP2_LEGAL_TEXT'], '<a href="/legal.html" target="_blank">', "</a>");
				$objTemplate->back_link = $this->getBaseLink()."?a=orderl";
				$objTemplate->formAction = $this->getBaseLink();				
				$this->Template->order = $objTemplate->parse();
	
			break;
			
			
			
			
			
			///// ORDER PAYMENT LOGIC
			case "orderp":
			
			
				// first, get the old order
				if($this->Session->get('i7shop_order')) {
					// there is already a order in the session
					$order = unserialize($this->Session->get('i7shop_order'));
				
					// populate fields
					$populateFields = $order->getPaymentDetails();
					if($populateFields['payment']) $paymentMethode = $populateFields['payment'];
				}
				else {
					$this->redirect($this->getBaseLink()."?a=orderl");
				}
				
				// set payment methode
				if($this->Input->post('payment')) $paymentMethode = $this->Input->post('payment');
			
				$objTemplate = new FrontendTemplate("shoporder_payment");
				
				
				
				$validate = false;
				
				if($this->Input->post('FORM_SUBMIT') == "order_payment") { 
					$validate = true;
					// unpopulate while submiting the form
					$populateFields = array();
				}
				
				$objTemplate->fields = $this->buildWidgets(ShopOrder::getPaymentFields($populateFields, $this->getSettings()), $validate);
				if($validate) {
			
					if($this->validateWidgets(ShopOrder::getPaymentFields()) || ($paymentMethode == "prepay")) {
						// okay.. prefect
						
						$order->setPaymentDetails($_REQUEST); // todo if tl would have a input holder get all

		
						// save order
						$this->Session->set('i7shop_order', serialize($order));
						
						// jump to order overview
						$this->redirect($this->getBaseLink()."?a=ordero");
					}
					
				}

				$objTemplate->paymentMethode = $paymentMethode;
				$objTemplate->back_link = $this->getBaseLink()."?a=order";
				$objTemplate->formAction = $this->getBaseLink();
				$this->Template->order = $objTemplate->parse();
				break;
				
				
				
				
				
					///// ORDER OVERVIEW LOGIC
				case "ordero":
					// first, get the old order
					if($this->Session->get('i7shop_order')) {
						// there is already a order in the session
						$order = unserialize($this->Session->get('i7shop_order'));
				
						// populate fields
						$populateFields = $order->getPaymentDetails();

					}
					else {
						$this->redirect($this->getBaseLink()."?a=orderl");
					}
				
					$objTemplate = new FrontendTemplate("shoporder_overview");

					$vars = $this->generateBasketList(array("template" => "shoparticle_overview"));
					foreach($vars as $key => $value) { $objTemplate->$key = $value; }
					
					$order->setBasketTotal($this->basketManager->getTotal($this->getSettings()));
					
					$objTemplate->total = ShopHelpers::makePrice($this->currency, $order->getBasketTotal(), $this->currencyFormat);
					$objTemplate->shippmentCosts = ShopHelpers::makePrice($this->currency, $order->getShippmentCosts(), $this->currencyFormat);
					$objTemplate->taxCosts = ShopHelpers::makePrice($this->currency, $order->getTaxeCost(), $this->currencyFormat);
					$objTemplate->taxPercent = $order->getTaxPercentage();
					$objTemplate->endtotal = ShopHelpers::makePrice($this->currency, $order->getEndTotal(), $this->currencyFormat);
					
					$objTemplate->paymentDetails = $order->getFormatedPaymentDetails();
					$objTemplate->billAddress = str_replace("\n", "<br />", $order->getBillAddress());
					$objTemplate->shipAddress = str_replace("\n", "<br />", $order->getShipAddress());
					
					$objTemplate->place_order_link = $this->getBaseLink()."?a=orderd";
					$objTemplate->back_link = $this->getBaseLink()."?a=orderp";
					
					$objTemplate->payment_error = "";
					if($this->Input->get('perr')) {
						// okay, thats not the first time... there was a payment error
						$objTemplate->payment_error = $order->getLastError();
						$objTemplate->debug_error = $order->getDebugResponse();
					}
					
					// save order
					$this->Session->set('i7shop_order', serialize($order));
					
					$objTemplate->taxes_info = sprintf($GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_TAXES_INCLUDED'], $order->getTaxPercentage()."%");
					
					$this->Template->order = $objTemplate->parse();
					
				
				break;
				
					///// ORDER DO PAYMENT LOGIC
				case "orderd":
					// pay
					
					// first, get the old order
					if($this->Session->get('i7shop_order')) {
						// there is already a order in the session
						$order = unserialize($this->Session->get('i7shop_order'));
					}
					else {
						$this->redirect($this->getBaseLink()."?a=orderl");
					}
					
					$suc = $order->payOrder(array_merge($this->getSettings(), $order->getPaymentDetails()));
				
					//$suc = 1;
				
					if(!$suc) {
						// payment went wrong
						$this->Session->set('i7shop_order', serialize($order));
						$this->redirect($this->getBaseLink()."?a=ordero&perr=1");
						
					}
					else {
						// success ... placce the order
						$order->setOrderStatus(1); // placed and payed
						$order->store();
						
						// send mails
						$objTemplate = new FrontendTemplate("shopmail_client");
						$vars = $this->generateBasketList(array("template" => "shoparticle_mail"));
						foreach($vars as $key => $value) { $objTemplate->$key = $value; }
						$objTemplate->total = ShopHelpers::makePrice($this->currency, $order->getBasketTotal(), $this->currencyFormat);
						$objTemplate->shippmentCosts = ShopHelpers::makePrice($this->currency, $order->getShippmentCosts(), $this->currencyFormat);
						$objTemplate->taxCosts = ShopHelpers::makePrice($this->currency, $order->getTaxeCost(), $this->currencyFormat);
						$objTemplate->taxPercent = $order->getTaxPercentage();
						$objTemplate->endtotal = ShopHelpers::makePrice($this->currency, $order->getEndTotal(), $this->currencyFormat);

						$objTemplate->paymentDetails = $order->getPaymentDetails();
						$objTemplate->billAddress = str_replace("\n", "<br />", $order->getBillAddress());
						$objTemplate->shipAddress = str_replace("\n", "<br />", $order->getShipAddress());
						$objTemplate->orderId = $order->getId();
						
						$mailtext = $objTemplate->parse();
						
		
						$objEmail = new Email();
						$objEmail->from = $GLOBALS['TL_ADMIN_EMAIL'];
						$objEmail->subject = "Order Confirmation (".$order->getId().")";
						$objEmail->text = $mailtext;
						$objEmail->sendTo($order->getBillAddress()->getEmail());
						$objEmail->sendTo($GLOBALS['TL_ADMIN_EMAIL']);
						
						
						$this->clearOrderAndBasket();
						$this->saveState();
						$this->redirect($this->getBaseLink()."?a=ordert");
						
					}
					$this->Session->set('i7shop_order', serialize($order));
				break;
				
					///// ORDER THANKS
				case "ordert":
				
					$objTemplate = new FrontendTemplate("shoporder_thanks");
					$this->Template->order = $objTemplate->parse();
					break;
		}// end switch
		
		
		

		
		
		
		
		
		
		
		// populate template
		$this->Template->action = $queryAction;
		$this->Template->formAction = $this->getBaseLink();
		
		
		$this->saveState();
	}
	
	

}



?>