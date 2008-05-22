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
			- move static config to tl_i7shop */

$GLOBALS['TL_SHOP']['SHOW_TAXES_ONLY'] = true;


$GLOBALS['TL_SHOP']['SHIPPMENT_COSTS']['DEFAULT'] = 15;
$GLOBALS['TL_SHOP']['SHIPPMENT_COSTS']['ch'] = 7.5;
$GLOBALS['TL_SHOP']['SHIPPMENT_COSTS']['de'] = 10;
$GLOBALS['TL_SHOP']['SHIPPMENT_COSTS']['at'] = 10;
$GLOBALS['TL_SHOP']['SHIPPMENT_COSTS']['be'] = 10;
$GLOBALS['TL_SHOP']['SHIPPMENT_COSTS']['bg'] = 10;
$GLOBALS['TL_SHOP']['SHIPPMENT_COSTS']['cy'] = 10;
$GLOBALS['TL_SHOP']['SHIPPMENT_COSTS']['cz'] = 10;
$GLOBALS['TL_SHOP']['SHIPPMENT_COSTS']['dk'] = 10;
$GLOBALS['TL_SHOP']['SHIPPMENT_COSTS']['ee'] = 10;
$GLOBALS['TL_SHOP']['SHIPPMENT_COSTS']['fi'] = 10;
$GLOBALS['TL_SHOP']['SHIPPMENT_COSTS']['fr'] = 10;
$GLOBALS['TL_SHOP']['SHIPPMENT_COSTS']['gr'] = 10;
$GLOBALS['TL_SHOP']['SHIPPMENT_COSTS']['hu'] = 10;
$GLOBALS['TL_SHOP']['SHIPPMENT_COSTS']['ie'] = 10;
$GLOBALS['TL_SHOP']['SHIPPMENT_COSTS']['it'] = 10;
$GLOBALS['TL_SHOP']['SHIPPMENT_COSTS']['lv'] = 10;
$GLOBALS['TL_SHOP']['SHIPPMENT_COSTS']['lt'] = 10;
$GLOBALS['TL_SHOP']['SHIPPMENT_COSTS']['lu'] = 10;
$GLOBALS['TL_SHOP']['SHIPPMENT_COSTS']['mt'] = 10;
$GLOBALS['TL_SHOP']['SHIPPMENT_COSTS']['nl'] = 10;
$GLOBALS['TL_SHOP']['SHIPPMENT_COSTS']['pl'] = 10;
$GLOBALS['TL_SHOP']['SHIPPMENT_COSTS']['pt'] = 10;
$GLOBALS['TL_SHOP']['SHIPPMENT_COSTS']['ro'] = 10;
$GLOBALS['TL_SHOP']['SHIPPMENT_COSTS']['sk'] = 10;
$GLOBALS['TL_SHOP']['SHIPPMENT_COSTS']['si'] = 10;
$GLOBALS['TL_SHOP']['SHIPPMENT_COSTS']['es'] = 10;
$GLOBALS['TL_SHOP']['SHIPPMENT_COSTS']['se'] = 10;
$GLOBALS['TL_SHOP']['SHIPPMENT_COSTS']['uk'] = 10;

$GLOBALS['TL_SHOP']['TAX_PERCENTAGE']['DEFAULT'] = 0;
$GLOBALS['TL_SHOP']['TAX_PERCENTAGE']['de'] = 20;
$GLOBALS['TL_SHOP']['TAX_PERCENTAGE']['at'] = 20;
$GLOBALS['TL_SHOP']['TAX_PERCENTAGE']['be'] = 20;
$GLOBALS['TL_SHOP']['TAX_PERCENTAGE']['bg'] = 20;
$GLOBALS['TL_SHOP']['TAX_PERCENTAGE']['cy'] = 20;
$GLOBALS['TL_SHOP']['TAX_PERCENTAGE']['cz'] = 20;
$GLOBALS['TL_SHOP']['TAX_PERCENTAGE']['dk'] = 20;
$GLOBALS['TL_SHOP']['TAX_PERCENTAGE']['ee'] = 20;
$GLOBALS['TL_SHOP']['TAX_PERCENTAGE']['fi'] = 20;
$GLOBALS['TL_SHOP']['TAX_PERCENTAGE']['fr'] = 20;
$GLOBALS['TL_SHOP']['TAX_PERCENTAGE']['gr'] = 20;
$GLOBALS['TL_SHOP']['TAX_PERCENTAGE']['hu'] = 20;
$GLOBALS['TL_SHOP']['TAX_PERCENTAGE']['ie'] = 20;
$GLOBALS['TL_SHOP']['TAX_PERCENTAGE']['it'] = 20;
$GLOBALS['TL_SHOP']['TAX_PERCENTAGE']['lv'] = 20;
$GLOBALS['TL_SHOP']['TAX_PERCENTAGE']['lt'] = 20;
$GLOBALS['TL_SHOP']['TAX_PERCENTAGE']['lu'] = 20;
$GLOBALS['TL_SHOP']['TAX_PERCENTAGE']['mt'] = 20;
$GLOBALS['TL_SHOP']['TAX_PERCENTAGE']['nl'] = 20;
$GLOBALS['TL_SHOP']['TAX_PERCENTAGE']['pl'] = 20;
$GLOBALS['TL_SHOP']['TAX_PERCENTAGE']['pt'] = 20;
$GLOBALS['TL_SHOP']['TAX_PERCENTAGE']['ro'] = 20;
$GLOBALS['TL_SHOP']['TAX_PERCENTAGE']['sk'] = 20;
$GLOBALS['TL_SHOP']['TAX_PERCENTAGE']['si'] = 20;
$GLOBALS['TL_SHOP']['TAX_PERCENTAGE']['es'] = 20;
$GLOBALS['TL_SHOP']['TAX_PERCENTAGE']['se'] = 20;
$GLOBALS['TL_SHOP']['TAX_PERCENTAGE']['uk'] = 20;


$GLOBALS['TL_SHOP']['SHIP_TIME']['DEFAULT'] = '>10';
$GLOBALS['TL_SHOP']['SHIP_TIME']['ch'] = 10;
$GLOBALS['TL_SHOP']['SHIP_TIME']['de'] = 10;
$GLOBALS['TL_SHOP']['SHIP_TIME']['at'] = 10;
$GLOBALS['TL_SHOP']['SHIP_TIME']['be'] = 10;
$GLOBALS['TL_SHOP']['SHIP_TIME']['bg'] = 10;
$GLOBALS['TL_SHOP']['SHIP_TIME']['cy'] = 10;
$GLOBALS['TL_SHOP']['SHIP_TIME']['cz'] = 10;
$GLOBALS['TL_SHOP']['SHIP_TIME']['dk'] = 10;
$GLOBALS['TL_SHOP']['SHIP_TIME']['ee'] = 10;
$GLOBALS['TL_SHOP']['SHIP_TIME']['fi'] = 10;
$GLOBALS['TL_SHOP']['SHIP_TIME']['fr'] = 10;
$GLOBALS['TL_SHOP']['SHIP_TIME']['gr'] = 10;
$GLOBALS['TL_SHOP']['SHIP_TIME']['hu'] = 10;
$GLOBALS['TL_SHOP']['SHIP_TIME']['ie'] = 10;
$GLOBALS['TL_SHOP']['SHIP_TIME']['it'] = 10;
$GLOBALS['TL_SHOP']['SHIP_TIME']['lv'] = 10;
$GLOBALS['TL_SHOP']['SHIP_TIME']['lt'] = 10;
$GLOBALS['TL_SHOP']['SHIP_TIME']['lu'] = 10;
$GLOBALS['TL_SHOP']['SHIP_TIME']['mt'] = 10;
$GLOBALS['TL_SHOP']['SHIP_TIME']['nl'] = 10;
$GLOBALS['TL_SHOP']['SHIP_TIME']['pl'] = 10;
$GLOBALS['TL_SHOP']['SHIP_TIME']['pt'] = 10;
$GLOBALS['TL_SHOP']['SHIP_TIME']['ro'] = 10;
$GLOBALS['TL_SHOP']['SHIP_TIME']['sk'] = 10;
$GLOBALS['TL_SHOP']['SHIP_TIME']['si'] = 10;
$GLOBALS['TL_SHOP']['SHIP_TIME']['es'] = 10;
$GLOBALS['TL_SHOP']['SHIP_TIME']['se'] = 10;
$GLOBALS['TL_SHOP']['SHIP_TIME']['uk'] = 10;









/*
	CONTENT MODULED
	
*/
array_insert($GLOBALS['TL_CTE'],1, array
(
	'i7Shop' => array
	(
				'addToBasketLink'     => 'ContentAddToBasket'
	)
));


/* BACKEND MODULES */

$shopArray['i7shop'] = array
(
    'tables'       => array('tl_i7shop'),
    'icon'         => 'system/modules/i7Shop/html/icon.gif'
);

$shopArray['i7shop_articletree'] = array
(
    'tables'       => array('tl_i7shop_articletree', 'tl_i7shop'),
    'icon'         => 'system/modules/i7Shop/html/icon.gif'
);

$shopArray['i7shop_order'] = array
(
	'tables' => array('tl_i7shop_order', 'tl_i7shop_order_article'),
	'icon'   => 'system/modules/i7Shop/html/icon.gif'
);

$GLOBALS['BE_MOD'] = array_merge(array('shop' => $shopArray), $GLOBALS['BE_MOD']);
/**
 * Front end module
 */

array_insert($GLOBALS['FE_MOD'], 4, array
(
	'i7shop' => array
	(
		'i7shop'   => 'ModuleShop',
		'i7shopBasketInfo'   => 'ModuleShopBasketInfo',
	
	)
));


?>