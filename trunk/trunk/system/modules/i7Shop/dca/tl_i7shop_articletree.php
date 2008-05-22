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
 * Table tl_i7shop_articletree 
 */

$GLOBALS['TL_DCA']['tl_i7shop_articletree'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
				'ctable'                      => 'tl_i7shop',
        'enableVersioning'            => false,
    ),
    
    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 5,
        ),
        'label' => array
        (
            'fields'                  => array('title', 'artnr', 'id'),
            'format'                  => '%s (articleid: %s, internal-id: %s)'
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
                'label'               => &$GLOBALS['TL_LANG']['tl_i7shop_articletree']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_i7shop_articletree']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
            'cut' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_i7shop_articletree']['cut'],
                'href'                => 'act=paste&amp;mode=cut',
                'icon'                => 'cut.gif',
                'attributes'          => 'onclick="Backend.getScrollOffset();"'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_i7shop_articletree']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_i7shop_articletree']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
        )
    ),

		// Palettes
		'palettes' => array
		(
			'__selector__'                => array('addOptions', 'addImage', 'title'),
			'default'                     => 'title,alias;text,price,artnr;singleSRC,size,fullsize,images',
		),
		// Subpalettes
		'subpalettes' => array
		(
			'addOptions'                    => 'maincurrency, tax, discount10, discount100, article_per_page, discount_group_1, discount_group_2, discount_group_3, discount_group_4, discount_group_5, info_email_address, show_prices_for_unregistered_user, shipping_fixed, shipping_till_value',
			

		),

    // Fields
    'fields' => array
    (
        'title' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_articletree']['title'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255)
        ),
        
        'alias' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_articletree']['alias'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>255)
        ),

				'text' => array
				(
					'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_articletree']['text'],
					'exclude'                 => true,
					'search'                  => true,
					'inputType'               => 'textarea',
					'eval'                    => array('rte'=>'tinyMCE', 'helpwizard'=>true),
					'explanation'             => 'insertTags'
				),
				'price' => array
		        (
		            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_articletree']['price'],
		            'exclude'                 => true,
		            'inputType'               => 'text',
		            'eval'                    => array('maxlength'=> 30)
		        ),
				'artnr' => array
		        (
		            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_articletree']['artnr'],
		            'exclude'                 => true,
		            'inputType'               => 'text',
		            'eval'                    => array('maxlength'=> 100)
		        ),
				'options' => array
		        (
		            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_articletree']['options'],
		            'exclude'                 => true,
		            'inputType'               => 'text',
		            'eval'                    => array('maxlength'=> 255)
		        ),
						'addImages' => array
						(
							'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_articletree']['addImages'],
							'exclude'                 => true,
							'inputType'               => 'checkbox',
							'eval'                    => array('submitOnChange'=>true)
						),
						'images' => array
						(
							'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_articletree']['images'],
							'exclude'                 => true,
							'inputType'               => 'fileTree',
							'eval'                    => array('files'=>true, 'fieldType'=>'checkbox')
						),

		'teaser' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_articletree']['teaser'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('style'=>'height:80px;', 'allowHtml'=>true)
		),
		'addOptions' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_articletree']['addOptions'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'maincurrency' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_articletree']['maincurrency'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>64)
        ),
		'tax' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_articletree']['tax'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('size'=>4, 'rgxp'=>'digit', 'nospace'=>true)
        ),
		'discount10' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_articletree']['discount10'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('size'=>4, 'rgxp'=>'digit', 'nospace'=>true)
        ),
		'discount100' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_articletree']['discount100'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('size'=>4, 'rgxp'=>'digit', 'nospace'=>true)
        ),
		'article_per_page' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_articletree']['article_per_page'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('size'=>3, 'rgxp'=>'digit', 'nospace'=>true)
        ),
		'discount_group_1' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_articletree']['discount_group_1'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>64)
        ),
		'discount_group_2' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_articletree']['discount_group_2'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>64)
        ),
		'discount_group_3' => array
		(
		'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_articletree']['discount_group_3'],
		'exclude'                 => true,
		'inputType'               => 'text',
		'eval'                    => array('maxlength'=>64)
		),
		'discount_group_4' => array
		(
		  'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_articletree']['discount_group_4'],
		  'exclude'                 => true,
		  'inputType'               => 'text',
		  'eval'                    => array('maxlength'=>64)
		),
		'discount_group_5' => array
		(
		    'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_articletree']['discount_group_5'],
		    'exclude'                 => true,
		    'inputType'               => 'text',
		    'eval'                    => array('maxlength'=>64)
		),
		'info_email_address' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_articletree']['info_email_address'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'email', 'maxlength'=>64)
        ),
		'show_prices_for_unregistered_user' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_articletree']['show_prices_for_unregistered_user'],
            'exclude'                 => true,
            'inputType'               => 'checkbox'
        ),
		'shipping_fixed' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_articletree']['shipping_fixed'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'digit', 'nospace'=>true)
        ),
		'shipping_till_value' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_articletree']['shipping_till_value'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'digit', 'nospace'=>true)
        ),
			'addImage' => array
			(
				'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_articletree']['addImage'],
				'exclude'                 => true,
				'inputType'               => 'checkbox',
				'eval'                    => array('submitOnChange'=>true)
			),
			'singleSRC' => array
			(
				'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_articletree']['singleSRC'],
				'exclude'                 => true,
				'inputType'               => 'fileTree',
				'eval'                    => array('fieldType'=>'radio', 'files'=>true)
			),
			'size' => array
			(
				'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_articletree']['size'],
				'exclude'                 => true,
				'inputType'               => 'text',
				'eval'                    => array('multiple'=>true, 'size'=>2, 'rgxp'=>'digit', 'nospace'=>true)
			),
			'alt' => array
			(
				'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_articletree']['alt'],
				'exclude'                 => true,
				'inputType'               => 'text',
				'eval'                    => array('mandatory'=>true, 'rgxp'=>'extnd', 'maxlength'=>255)
			),
			'imagemargin' => array
			(
				'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_articletree']['imagemargin'],
				'exclude'                 => true,
				'inputType'               => 'trbl',
				'options'                 => array('px', '%', 'em', 'pt', 'pc', 'in', 'cm', 'mm'),
				'eval'                    => array('includeBlankOption'=>true)
			),
			'fullsize' => array
			(
				'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop_articletree']['fullsize'],
				'exclude'                 => true,
				'inputType'               => 'checkbox'
			),

    )
);

class tl_i7shop_articletree extends Backend
{
	/**
	 * Add an image to each page in the tree
	 * @param array
	 * @param string
	 * @param string
	 * @param object
	 * @return string
	 */
	public function addImage($row, $label, $imageAttribute, DataContainer $dc)
	{
		$sub = 0;
		$image = ''.$row['type'].'.gif';

		// Page not published or not active
		if ((!$row['published'] || $row['start'] && $row['start'] > time() || $row['stop'] && $row['stop'] < time()))
		{
			$sub += 1;
		}

		// Page hidden from menu
		if ($row['hide'] && !in_array($row['type'], array('redirect', 'forward', 'root', 'error_403', 'error_404')))
		{
			$sub += 2;
		}

		// Page protected
		if ($row['protected'] && !in_array($row['type'], array('root', 'error_403', 'error_404')))
		{
			$sub += 4;
		}

		// Get image name
		if ($sub > 0)
		{
			$image = ''.$row['type'].'_'.$sub.'.gif';
		}

		if ($row['type'] == 'root' || $this->Input->get('do') == 'article')
		{
			$label = '<strong>' . $label . '</strong>';
		}

		// Return image
		return '<a href="'.$this->generateFrontendUrl($row).'" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['view']).'"' . (($dc->table != 'tl_page') ? ' class="tl_gray"' : ''). LINK_NEW_WINDOW_BLUR . '>'.$this->generateImage($image, '', $imageAttribute).'</a> '.$label;
	}
}

?>