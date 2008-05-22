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
 * @package    Backend
 * @license    LGPL
 * @filesource
 */


/**
 * Back end modules
 */
$GLOBALS['BE_MOD'] = array
(
	// Content modules
	'content' => array
	(
		'article' => array
		(
			'tables' => array('tl_article', 'tl_content')
		),
		'flash' => array
		(
			'tables' => array('tl_flash')
		),
		'form' => array
		(
			'tables' => array('tl_form', 'tl_form_field')
		)
	),

	// Design modules
	'design' => array
	(
		'modules' => array
		(
			'tables' => array('tl_module')
		),
		'css' => array
		(
			'tables' => array('tl_style_sheet', 'tl_style'),
			'import' => array('StyleSheets', 'importStyleSheet')
		),
		'layout' => array
		(
			'tables' => array('tl_layout')
		),
		'page' => array
		(
			'tables' => array('tl_page')
		)
	),

	// Account modules
	'accounts' => array
	(
		'member' => array
		(
			'tables' => array('tl_member')
		),
		'mgroup' => array
		(
			'tables' => array('tl_member_group')
		),
		'user' => array
		(
			'tables' => array('tl_user')
		),
		'group' => array
		(
			'tables' => array('tl_user_group')
		)
	),

	// System modules
	'system' => array
	(
		'files' => array
		(
			'tables' => array('tl_files')
		),
		'log' => array
		(
			'tables' => array('tl_log')
		),
		'settings' => array
		(
			'tables' => array('tl_settings')
		),
		'maintenance' => array
		(
			'callback' => 'ModuleMaintenance'
		)
	),

	// User modules
	'profile' => array
	(
		'undo' => array
		(
			'tables' => array('tl_undo')
		),
		'login' => array
		(
			'tables' => array('tl_user'),
			'callback' => 'ModuleUser'
		),
		'tasks' => array
		(
			'callback' => 'ModuleTasks'
		)
	)
);


/**
 * Form fields
 */
$GLOBALS['BE_FFL'] = array
(
	'text'         => 'TextField',
	'password'     => 'Password',
	'textarea'     => 'TextArea',
	'select'       => 'SelectMenu',
	'checkbox'     => 'CheckBox',
	'radio'        => 'RadioButton',
	'radioTable'   => 'RadioTable',
	'inputUnit'    => 'InputUnit',
	'trbl'         => 'TrblField',
	'chmod'        => 'ChmodTable',
	'pageTree'     => 'PageTree',
	'fileTree'     => 'FileTree',
	'tableWizard'  => 'TableWizard',
	'listWizard'   => 'ListWizard',
	'optionWizard' => 'OptionWizard',
	'moduleWizard' => 'ModuleWizard'
);


/**
 * Page types
 */
$GLOBALS['TL_PTY'] = array
(
	'regular'   => 'PageRegular',
	'redirect'  => 'PageRedirect',
	'forward'   => 'PageForward',
	'root'      => 'PageRoot',
	'error_403' => 'PageError403',
	'error_404' => 'PageError404'
);


/**
 * Cache tables
 */
$GLOBALS['TL_CACHE'] = array
(
	'tl_cache',
	'tl_version',
	'tl_undo',
	'tl_search',
	'tl_search_index'
);


/**
 * Hooks
 */
$GLOBALS['TL_HOOKS'] = array();

?>