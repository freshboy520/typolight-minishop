<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
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
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    News
 * @license    LGPL
 * @filesource
 */


/**
 * Add palettes to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['newslist']    = 'name,type,headline;news_archives,news_template;news_metaFields,news_dateFormat;news_numberOfItems,perPage,skipFirst,searchable;align,space,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['newsreader']  = 'name,type,headline;news_archives,news_template;news_metaFields,news_dateFormat;align,space,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['newsarchive'] = 'name,type;news_archives,news_template;news_metaFields,news_dateFormat;perPage,news_jumpToCurrent,searchable;align,space,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['newsmenu']    = 'name,type,headline;news_archives,news_showQuantity;jumpTo;align,space,cssID';


/**
 * Add fields to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['news_archives'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['news_archives'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'foreignKey'              => 'tl_news_archive.title',
	'eval'                    => array('multiple'=>true, 'mandatory'=>true)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['news_showQuantity'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['news_showQuantity'],
	'exclude'                 => true,
	'inputType'               => 'checkbox'
);

$GLOBALS['TL_DCA']['tl_module']['fields']['news_numberOfItems'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['news_numberOfItems'],
	'default'                 => 3,
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>true, 'rgxp'=>'digit')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['news_template'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['news_template'],
	'default'                 => 'news_single',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => $this->getTemplateGroup('news_')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['news_metaFields'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['news_metaFields'],
	'default'                 => array('date', 'author'),
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'options'                 => array('date', 'author', 'comments'),
	'reference'               => &$GLOBALS['TL_LANG']['MSC'],
	'eval'                    => array('multiple'=>true)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['news_dateFormat'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['news_dateFormat'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>32)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['news_jumpToCurrent'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['news_jumpToCurrent'],
	'exclude'                 => true,
	'inputType'               => 'checkbox'
);

?>