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


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_i7shop_order']['order_date']    = array('Order date', 'Please enter the date,time of the order.');
$GLOBALS['TL_LANG']['tl_i7shop_order']['order_status'] = array('Order status', 'Status of the order.');
$GLOBALS['TL_LANG']['tl_i7shop_order']['payment_booking_number']   = array('Payment Booking Number', 'This is the payment ID, use this in case of payment issues');
$GLOBALS['TL_LANG']['tl_i7shop_order']['data']   = array('Order Data', 'The raw order data (expert use only)');
$GLOBALS['TL_LANG']['tl_i7shop_order']['client_bill_address']   = array('Billing Address', '');
$GLOBALS['TL_LANG']['tl_i7shop_order']['client_ship_address']   = array('Shipping Address', '');
$GLOBALS['TL_LANG']['tl_i7shop_order']['client_name']   = array('Name of the client', '');
$GLOBALS['TL_LANG']['tl_i7shop_order']['client_id']   = array('Member ID', 'with this id you can make a link to the member');

$GLOBALS['TL_LANG']['tl_i7shop_order']['taxes']   = array('VAT', 'The amount of VAT');
$GLOBALS['TL_LANG']['tl_i7shop_order']['shippment']   = array('Shipping costs', 'The amount of shipping costs');
$GLOBALS['TL_LANG']['tl_i7shop_order']['basket_total']   = array('Basket Total', 'The amount of the basket');
$GLOBALS['TL_LANG']['tl_i7shop_order']['total']   = array('End total', 'The billed amount');
$GLOBALS['TL_LANG']['tl_i7shop_order']['basket']   = array('Basket', 'The basket contents');
/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_i7shop_order']['new']    = array('New order', 'Create a new order');
$GLOBALS['TL_LANG']['tl_i7shop_order']['edit']   = array('Edit order', 'Edit order ID %s');
$GLOBALS['TL_LANG']['tl_i7shop_order']['copy']   = array('Copy order', 'Copy order ID %s');
$GLOBALS['TL_LANG']['tl_i7shop_order']['delete'] = array('Delete order', 'Delete order ID %s');
$GLOBALS['TL_LANG']['tl_i7shop_order']['show']   = array('Order details', 'Show details of order ID %s');

?>