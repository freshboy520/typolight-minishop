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



/* -todo- 
			-  */


// Order 

$GLOBALS['TL_LANG']['i7SHOP']['text_loginusername']	= 'Email address (username)';
$GLOBALS['TL_LANG']['i7SHOP']['text_loginpassword']	= 'Password';

$GLOBALS['TL_LANG']['i7SHOP']['text_registerusername']	= 'Your email address (username)';
$GLOBALS['TL_LANG']['i7SHOP']['text_registerpassword']	= 'Password';

$GLOBALS['TL_LANG']['i7SHOP']['text_loginconfirm'] = 'Enter your password again';
$GLOBALS['TL_LANG']['i7SHOP']['remove_article']	= 'remove article';
$GLOBALS['TL_LANG']['i7SHOP']['text_company']		= 'Company';
$GLOBALS['TL_LANG']['i7SHOP']['text_firstname']	= 'First name';
$GLOBALS['TL_LANG']['i7SHOP']['text_lastname']	= 'Last name';
$GLOBALS['TL_LANG']['i7SHOP']['text_address1']	= 'Street';
$GLOBALS['TL_LANG']['i7SHOP']['text_address2']	= 'p.o. box';
$GLOBALS['TL_LANG']['i7SHOP']['text_address3']	= 'address3';
$GLOBALS['TL_LANG']['i7SHOP']['text_zipcode']		= 'Zip code';
$GLOBALS['TL_LANG']['i7SHOP']['text_location']	= 'City';
$GLOBALS['TL_LANG']['i7SHOP']['text_country']		= 'Country';
$GLOBALS['TL_LANG']['i7SHOP']['text_billingaddress']		= 'Billing address';

$GLOBALS['TL_LANG']['i7SHOP']['text_email']		= 'email';




$GLOBALS['TL_LANG']['i7SHOP']['i7shop_hide_on_page'] = array("Hide basket info on the following pages", "You can choose some pages where you don't want to see the basket info");
$GLOBALS['TL_LANG']['i7SHOP']['i7shop_basket_info_always_visible'] = array("Always visible", "The basket info sould also be visible if basket is empty");


$GLOBALS['TL_LANG']['i7SHOP']['cctype']		= 'Creditcard type';
$GLOBALS['TL_LANG']['i7SHOP']['ccnumber']		= 'Creditcard number';
$GLOBALS['TL_LANG']['i7SHOP']['ccname']		= 'Name on creditcard';
$GLOBALS['TL_LANG']['i7SHOP']['ccdatem']		= 'Exp. date of your creditcard (month)';
$GLOBALS['TL_LANG']['i7SHOP']['ccdatey']		= 'Exp. date of your creditcard (year)';
$GLOBALS['TL_LANG']['i7SHOP']['ccv']		= 'CCV Verification on back side of your creditcard';

$GLOBALS['TL_LANG']['i7SHOP']['termsaccepted']		= 'I accept the terms and conditions';


// TEMPLATS
// BASKET
$GLOBALS['TL_LANG']['i7SHOP']['BASKET_TITLE']	= "Shopping Basket";
$GLOBALS['TL_LANG']['i7SHOP']['BASKET_AMOUNT']	= "Amount";
$GLOBALS['TL_LANG']['i7SHOP']['BASKET_ITEM']	= "Item";
$GLOBALS['TL_LANG']['i7SHOP']['BASKET_UNITPRICE']	= "Unit price";
$GLOBALS['TL_LANG']['i7SHOP']['BASKET_SUBTOTAL']	= "Sub total";
$GLOBALS['TL_LANG']['i7SHOP']['BASKET_TOTAL']	= "Total";
$GLOBALS['TL_LANG']['i7SHOP']['BASKET_ORDER_COMMAND']	= "Order now!";
$GLOBALS['TL_LANG']['i7SHOP']['BASKET_EMPTY']	= "Your shopping basket is empty";
$GLOBALS['TL_LANG']['i7SHOP']['BASKET_DELETE_COMMAND']	= "Delete";

// O STEP 1
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP1_TITLE']	= "Order Step 1/4 - Login / Register";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP3_SUBTITLE']	= "";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP1_TITLE_LOGIN']	= "If you already have a user name, please login here";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP1_LOGIN_ERROR']	= "Username or password not found";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP1_LOGIN_COMMAND']	= "Login";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP1_TITLE_REGISTER']	= "If you are a new client, please register";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP1_REGISTER_USEREXISTS']	= "This email (Username) already exists!";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP1_REGISTER_COMMAND']	= "Register";


/* O STEP 2 */
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP2_TITLE']	= "Order Step 2/4 - Address";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP2_SUBTITLE']	= "Enter shipping & billing address";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP2_SHIPPING_ADDRESS_TITLE']	= "Shipping address";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP2_SHIPPING_ADDRESS_NO']	= "Shipment address is different than billing address";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP2_SHIPPING_ADDRESS_YES']	= "Shipment address is same as billing address";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP2_BACK_COMMAND']	= "« Back";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP2_CONTINUE_COMMAND']	= "Continue to step 2/4";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP2_LEGAL_TEXT']	= "I agree with the %sterms and conditions%s";

/* O STEP 3 */

$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP3_TITLE']	= "Order Step 3/4 - Payment";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP3_SUBTITLE']	= "";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP3_CHOOSE'] = "--- please choose ---";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP3_BACK_COMMAND']	= "« Back";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP3_CONTINUE_COMMAND']	= "Continue to step 3/4";

$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_TITLE']	= "Order Step 4/4 - Confirm";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_SUBTITLE']	= "Please review your order";

$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_SHIP_TIME']	= "Please allow %s days for delivery";

$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_BACK_COMMAND']	= "« Back";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_CONTINUE_COMMAND']	= "Confirm & pay your order";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_PAYMENT_ERROR_TITLE']	= "An error occurred with your payment";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_PAYMENT_ERROR_TEXT']	= "Please go back and change your creditcard information";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_BLOCK_PAYMENTINFO']	= "Payment details";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_BLOCK_SHIPPINGADDRESSINFO']	= "Shipping address";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_BLOCK_BILLADDRESSINFO']	= "Billing address";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_ENDTOTAL']	= "You have to pay";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_TOTAL_SHIPPMENT']	= "Shipment costs";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_TOTAL']	= "Total";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_TAXES_INCLUDED']	= "Included VAT (%s)";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_TAXES_EXCLUDED']	= "VAT (%s%)";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_BASKET_AMOUNT']	= $GLOBALS['TL_LANG']['i7SHOP']['BASKET_AMOUNT'];
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_BASKET_ITEM']	= $GLOBALS['TL_LANG']['i7SHOP']['BASKET_ITEM'];
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_BASKET_UNITPRICE']	= $GLOBALS['TL_LANG']['i7SHOP']['BASKET_UNITPRICE'];
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_BASKET_SUBTOTAL']	= $GLOBALS['TL_LANG']['i7SHOP']['BASKET_SUBTOTAL'];


$GLOBALS['TL_LANG']['i7SHOP']['ORDER_THANKS_TITLE']	= "Thank you for your order";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_THANKS_SUBTITLE']	= "You should receive by e-mail an order confirmation within 1 hour. If you do not receive a confirmation within one hour, please send email to: <a href=\"mailto:onlineshop@qwstion.com\">onlineshop@qwstion.com</a>";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_THANKS_BACK_COMMAND']	= "« Back to home";







$GLOBALS['TL_LANG']['i7SHOP']['BASKETINFO']	= "You have %s product(s) in your %sshopping basket%s";





?>