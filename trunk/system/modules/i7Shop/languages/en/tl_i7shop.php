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
$GLOBALS['TL_LANG']['tl_i7shop']['name']   = array('Shopsystem internal Name', 'Please set a internal name for the shopsystem (p.E. "shop english")');
$GLOBALS['TL_LANG']['tl_i7shop']['alias']   = array('Shopsystem alias', '');

$GLOBALS['TL_LANG']['tl_i7shop']['maincurrency']   = array('Currency', 'Set the currency (p.E. "USD" or "EUR")');
$GLOBALS['TL_LANG']['tl_i7shop']['currencyformat']   = array('Currency format', 'Set the currencyformat (user printf format, p.E "{$c} %01.2f")');

$GLOBALS['TL_LANG']['tl_i7shop']['payment_account_id']   = array('Payment account ID', 'Payment adapter account ID');
$GLOBALS['TL_LANG']['tl_i7shop']['payment_user_id']   = array('Payment user ID', 'Payment adapter user ID');
$GLOBALS['TL_LANG']['tl_i7shop']['payment_transaction_password']   = array('Payment transcation password', 'Payment adapter transaction password');
$GLOBALS['TL_LANG']['tl_i7shop']['payment_adminaction_password']   = array('Payment admin password', 'Payment adapter admin password');

/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_i7shop']['new']    = array('New shopsystem', 'Create a new shopsystem');
$GLOBALS['TL_LANG']['tl_i7shop']['edit']   = array('Edit shopsystem', 'Edit shopsystem ID %s');
$GLOBALS['TL_LANG']['tl_i7shop']['copy']   = array('Copy shopsystem', 'Copy shopsystem ID %s');
$GLOBALS['TL_LANG']['tl_i7shop']['delete'] = array('Delete Shopsystem', 'Delete shopsystem ID %s');
$GLOBALS['TL_LANG']['tl_i7shop']['show']   = array('Shopsystem details', 'Show details of shopsystem ID %s');
$GLOBALS['TL_LANG']['tl_i7shop']['cut']   = array('Move details', 'Move shopsystem ID %s');


?>