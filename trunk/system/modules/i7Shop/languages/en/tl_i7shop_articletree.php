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
$GLOBALS['TL_LANG']['tl_i7shop_articletree']['title']    = array('Article ', 'Please enter the name of the article.');
$GLOBALS['TL_LANG']['tl_i7shop_articletree']['alias'] = array('Article Alias', 'The alias can be use for nice links.');
$GLOBALS['TL_LANG']['tl_i7shop_articletree']['text']   = array('Description', 'This text can be displayed in a detail view of the article');
$GLOBALS['TL_LANG']['tl_i7shop_articletree']['price']   = array('Price', 'Please enter the price in the default currency');
$GLOBALS['TL_LANG']['tl_i7shop_articletree']['addImages']   = array('Images', 'Select some Images');

$GLOBALS['TL_LANG']['tl_i7shop_articletree']['artnr']   = array('Article number / Article unique name', 'Use this for building a link to other items on site');
$GLOBALS['TL_LANG']['tl_i7shop_articletree']['options']   = array('Article Options', 'not in use at the moment');
$GLOBALS['TL_LANG']['tl_i7shop_articletree']['client_ship_address']   = array('Shipping Address', '');
$GLOBALS['TL_LANG']['tl_i7shop_articletree']['client_name']   = array('Name of the client', '');
$GLOBALS['TL_LANG']['tl_i7shop_articletree']['client_id']   = array('Member ID', 'with this id you can make a link to the member');


$GLOBALS['TL_LANG']['tl_i7shop_articletree']['singleSRC']   = array('Main image', 'Please select a main image');
$GLOBALS['TL_LANG']['tl_i7shop_articletree']['images']   = array('Additional images', 'Please select additional images');



/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_i7shop_articletree']['new']    = array('New article', 'Create a new article');
$GLOBALS['TL_LANG']['tl_i7shop_articletree']['edit']   = array('Edit article', 'Edit article ID %s');
$GLOBALS['TL_LANG']['tl_i7shop_articletree']['copy']   = array('Copy article', 'Copy article ID %s');
$GLOBALS['TL_LANG']['tl_i7shop_articletree']['delete'] = array('Delete article', 'Delete article ID %s');
$GLOBALS['TL_LANG']['tl_i7shop_articletree']['show']   = array('Article details', 'Show details of article ID %s');
$GLOBALS['TL_LANG']['tl_i7shop_articletree']['cut']   = array('Move details', 'Move article ID %s');

?>