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
 * Table tl_i7shop 
 */
$GLOBALS['TL_DCA']['tl_i7shop'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'enableVersioning'            => false
	),
    
    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 2,
        ),
        'label' => array
        (
            'fields'                  => array('name'),
            'format'                  => '%s'
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
                'label'               => &$GLOBALS['TL_LANG']['tl_i7shop']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_i7shop']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
            'cut' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_i7shop']['cut'],
                'href'                => 'act=paste&amp;mode=cut',
                'icon'                => 'cut.gif',
                'attributes'          => 'onclick="Backend.getScrollOffset();"'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_i7shop']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_i7shop']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
        )
    ),

		// Palettes
		'palettes' => array
		(
			'__selector__'                => array('addImage', 'name'),
			'default'                     => 'name,maincurrency,currencyformat;payment_account_id,payment_user_id,payment_transaction_password,payment_adminaction_password; ', /* -todo - unused
			tax, discount10, discount100, article_per_page, discount_group_1, discount_group_2, discount_group_3, discount_group_4, discount_group_5, info_email_address, show_prices_for_unregistered_user, shipping_fixed, shipping_till_value;addImage
			*/
			'external'					  => 'name,alias;addImage',
		),
   // Subpalettes
	'subpalettes' => array
	(
		'addImage'                    => 'singleSRC,size',
	),

    // Fields
    'fields' => array
    (
        'name' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop']['name'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255)
        ),
 		'alias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop']['alias'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'url', 'unique'=>true, 'spaceToUnderscore'=>true, 'maxlength'=>64),
			'save_callback' => array
			(
				array('tl_i7shop_article', 'generateAlias')
			)
		),

		'maincurrency' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop']['maincurrency'],
            'exclude'                 => true,
            'inputType'               => 'text',
						'default'                 => 'EUR',
            'eval'                    => array('maxlength'=>64)
        ),
		'currencyformat' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop']['currencyformat'],
            'exclude'                 => true,
            'inputType'               => 'text',
						'default'                 => '{$c} %01.2f',
            'eval'                    => array('maxlength'=>64)
        ),
		'payment_account_id' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop']['payment_account_id'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>255)
        ),
		'payment_user_id' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop']['payment_user_id'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>255)
        ),
		'payment_transaction_password' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop']['payment_transaction_password'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>255)
        ),
		'payment_adminaction_password' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop']['payment_adminaction_password'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>255)
        ),
		
		/* -todo- not in use at the moment
		
		
		'tax' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop']['tax'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('size'=>4, 'rgxp'=>'digit', 'nospace'=>true)
        ),
		'discount10' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop']['discount10'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('size'=>4, 'rgxp'=>'digit', 'nospace'=>true)
        ),
		'discount100' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop']['discount100'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('size'=>4, 'rgxp'=>'digit', 'nospace'=>true)
        ),
		'article_per_page' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop']['article_per_page'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('size'=>3, 'rgxp'=>'digit', 'nospace'=>true)
        ),
		'discount_group_1' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop']['discount_group_1'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>64)
        ),
		'discount_group_2' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop']['discount_group_2'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>64)
        ),
		'discount_group_3' => array
		(
		'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop']['discount_group_3'],
		'exclude'                 => true,
		'inputType'               => 'text',
		'eval'                    => array('maxlength'=>64)
		),
		'discount_group_4' => array
		(
		  'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop']['discount_group_4'],
		  'exclude'                 => true,
		  'inputType'               => 'text',
		  'eval'                    => array('maxlength'=>64)
		),
		'discount_group_5' => array
		(
		    'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop']['discount_group_5'],
		    'exclude'                 => true,
		    'inputType'               => 'text',
		    'eval'                    => array('maxlength'=>64)
		),
		'info_email_address' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop']['info_email_address'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'email', 'maxlength'=>64)
        ),
		'show_prices_for_unregistered_user' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop']['show_prices_for_unregistered_user'],
            'exclude'                 => true,
            'inputType'               => 'checkbox'
        ),
		'shipping_fixed' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop']['shipping_fixed'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'digit', 'nospace'=>true)
        ),
		'shipping_till_value' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop']['shipping_till_value'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'digit', 'nospace'=>true)
        ),
			'addImage' => array
			(
				'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop']['addImage'],
				'exclude'                 => true,
				'inputType'               => 'checkbox',
				'eval'                    => array('submitOnChange'=>true)
			),
			'singleSRC' => array
			(
				'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop']['singleSRC'],
				'exclude'                 => true,
				'inputType'               => 'fileTree',
				'eval'                    => array('fieldType'=>'radio', 'files'=>true, 'mandatory'=>true)
			),
			'size' => array
			(
				'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop']['size'],
				'exclude'                 => true,
				'inputType'               => 'text',
				'eval'                    => array('multiple'=>true, 'size'=>2, 'rgxp'=>'digit', 'nospace'=>true)
			),
			'alt' => array
			(
				'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop']['alt'],
				'exclude'                 => true,
				'inputType'               => 'text',
				'eval'                    => array('mandatory'=>true, 'rgxp'=>'extnd', 'maxlength'=>255)
			),
			'imagemargin' => array
			(
				'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop']['imagemargin'],
				'exclude'                 => true,
				'inputType'               => 'trbl',
				'options'                 => array('px', '%', 'em', 'pt', 'pc', 'in', 'cm', 'mm'),
				'eval'                    => array('includeBlankOption'=>true)
			),
			'fullsize' => array
			(
				'label'                   => &$GLOBALS['TL_LANG']['tl_i7shop']['fullsize'],
				'exclude'                 => true,
				'inputType'               => 'checkbox'
			),
			*/
    )
);

class tl_i7shop_article extends Backend
{

	/**
	 * Autogenerate a news alias if it has not been set yet
	 * @param mixed
	 * @param object
	 * @return string
	 */
	public function generateAlias($varValue, DataContainer $dc)
	{
		$autoAlias = false;

		// Generate alias if there is none
		if (!strlen($varValue))
		{
			$objTitle = $this->Database->prepare("SELECT name FROM tl_i7shop_article WHERE id=?")
									   ->limit(1)
									   ->execute($dc->id);

			$autoAlias = true;
			$varValue = standardize($objTitle->name);
		}

		$objAlias = $this->Database->prepare("SELECT id FROM tl_i7shop_article WHERE alias=?")
								   ->execute($varValue, $dc->id);

		// Check whether the news alias exists
		if ($objAlias->numRows > 1 && !$autoAlias)
		{
			throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
		}

		// Add ID to alias
		if ($objAlias->numRows && $autoAlias)
		{
			$varValue .= '.' . $dc->id;
		}

		return $varValue;
	}
}


?>