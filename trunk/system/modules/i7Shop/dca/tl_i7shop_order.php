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
 * Table tl_i7shop_order 
 */

$GLOBALS['TL_DCA']['tl_i7shop_order'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'enableVersioning'            => false,
    ),
    
    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 2,
						'fields'                  => array('order_date DESC'),
						'flag'                    => 1,
						'panelLayout'             => 'search,limit'
        ),
        'label' => array
        (
            'fields'                  => array('order_date', 'client_name', 'order_status'),
            'format'                  => '%s - <strong>Client:</strong> %s, Status: %d',
						'label_callback'					=> array('tl_i7shop_order', 'label')
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset();"'
            )
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_i7shop_order']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_i7shop_order']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
            'cut' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_i7shop_order']['cut'],
                'href'                => 'act=paste&amp;mode=cut',
                'icon'                => 'cut.gif',
                'attributes'          => 'onclick="Backend.getScrollOffset();"'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_i7shop_order']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_i7shop_order']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
        )
    ),

		// Palettes
		'palettes' => array
		(
			'__selector__'                => array(),
			'default'                     => 'order_date,order_status,payment_booking_number,client_name,client_id;taxes,shipping,basket_total,total;basket,client_bill_address,client_ship_address',
		),
		// Subpalettes
		'subpalettes' => array
		(

			

		),

    // Fields
    'fields' => array
    (
			'order_date' => array
			(
				'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_order']['order_date'],
				'default'                 => time(),
				'exclude'                 => true,
				'filter'                  => true,
				'flag'                    => 8,
				'inputType'               => 'text',
				'eval'                    => array('maxlength'=>10, 'rgxp'=>'date', 'datepicker'=>$this->getDatePickerString())
			),
        
        'order_status' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_order']['order_status'],
            'exclude'                 => true,
            'inputType'               => 'select',
						'options'									=> array(0,1,2),
            'eval'                    => array('maxlength'=>255),
						'reference'               => array(0 => "cancled", 1 => "paid", 2 => "shipped"),
        ),



				'payment_booking_number' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_order']['payment_booking_number'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>255)
        ),


				'data' => array
				(
					'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_order']['data'],
					'exclude'                 => true,
					'search'                  => true,
					'inputType'               => 'textarea',
					'eval'                    => array('helpwizard'=>true),
					'explanation'             => 'insertTags'
				),
				'client_bill_address' => array
				(
					'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_order']['client_bill_address'],
					'exclude'                 => true,
					'search'                  => true,
					'inputType'               => 'textarea',
					'eval'                    => array('helpwizard'=>true),
					'explanation'             => 'insertTags'
				),
				'client_ship_address' => array
				(
					'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_order']['client_ship_address'],
					'exclude'                 => true,
					'search'                  => true,
					'inputType'               => 'textarea',
					'eval'                    => array('helpwizard'=>true),
					'explanation'             => 'insertTags'
				),
				'client_name' => array
		        (
		            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_order']['client_name'],
		            'exclude'                 => true,
		            'inputType'               => 'text',
		            'eval'                    => array('maxlength'=> 100)
		        ),
						'client_id' => array
				        (
				            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_order']['client_id'],
				            'exclude'                 => true,
				            'inputType'               => 'text',
				            'eval'                    => array('maxlength'=> 100)
				        ),
				'taxes' => array
		        (
		            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_order']['taxes'],
		            'exclude'                 => true,
		            'inputType'               => 'text',
		            'eval'                    => array('maxlength'=> 30)
		        ),
				'shipping' => array
		        (
		            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_order']['shippment'],
		            'exclude'                 => true,
		            'inputType'               => 'text',
		            'eval'                    => array('maxlength'=> 30)
		        ),
				'basket_total' => array
		        (
		            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_order']['basket_total'],
		            'exclude'                 => true,
		            'inputType'               => 'text',
		            'eval'                    => array('maxlength'=> 30)
		        ),
				'total' => array
		        (
		            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_order']['total'],
		            'exclude'                 => true,
		            'inputType'               => 'text',
		            'eval'                    => array('maxlength'=> 30)
		        ),
					'basket' => array
					(
						'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_order']['basket'],
						'exclude'                 => true,
						'search'                  => true,
						'inputType'               => 'textarea',
						'eval'                    => array('helpwizard'=>true),
						'explanation'             => 'insertTags'
					),
			
    )
);

class tl_i7shop_order extends Backend
{
	/**
	 * Add an image to each page in the tree
	 * @param array
	 * @param string
	 * @param string
	 * @param object
	 * @return string
	 */
	public function label($row, $label, $imageAttribute, DataContainer $dc)
	{
		
		$div = "<div>";
		$div .= "<strong>Orderdate:</strong> ".date($GLOBALS['TL_CONFIG']['datimFormat'], $row['order_date'])."<br />\n";
		$div .= "<strong>Order ID: </strong> ".$row['id']."<br />\n";
		$div .= "<strong>Client:</strong> ".$row['client_name']."<br />\n";
		$div .= "<strong>Total:</strong> ".number_format($row['total'],2)."<br />\n";
		if($row['order_status'] == 1) {
			$div .= "<span style=\"background-color:#fdd;font-weight:bold;\"> Status: new, paid but unshipped</span><br />\n";
		}
		elseif($row['order_status'] == 2) {
			$div .= "<span style=\"background-color:#dfd;font-weight:bold;\"> Status: shipped</span><br />\n";
		}
		$div .= "</div>";
		
		return $div;
	}
}

?>