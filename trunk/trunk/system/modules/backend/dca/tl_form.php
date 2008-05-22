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
 * Table tl_form
 */
$GLOBALS['TL_DCA']['tl_form'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'switchToEdit'                => true,
		'enableVersioning'            => true,
		'ctable'                      => array('tl_form_field'),
		'onload_callback' => array
		(
			array('tl_form', 'checkPermission')
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 1,
			'fields'                  => array('title'),
			'flag'                    => 1,
			'panelLayout'             => 'filter,limit;search',
		),
		'label' => array
		(
			'fields'                  => array('title', 'formID'),
			'format'                  => '%s <span style="color:#b3b3b3; padding-left:3px;">[%s]</span>'
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
				'label'               => &$GLOBALS['TL_LANG']['tl_form']['edit'],
				'href'                => 'table=tl_form_field',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_form']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif',
				'button_callback'     => array('tl_form', 'copyForm')
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_form']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
				'button_callback'     => array('tl_form', 'deleteForm')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_form']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('storeValues', 'sendViaEmail'),
		'default'                     => 'title,formID,method;jumpTo;storeValues;sendViaEmail;attributes,allowTags,tableless'
	),

	// Subpalettes
	'subpalettes' => array
	(
		'storeValues'                 => 'targetTable',
		'sendViaEmail'                => 'recipient,subject,format'
	),

	// Fields
	'fields' => array
	(
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form']['title'],
			'search'                  => true,
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255)
		),
		'formID' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form']['formID'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('nospace'=>true, 'maxlength'=>64)
		),
		'method' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form']['method'],
			'default'                 => 'POST',
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('POST', 'GET')
		),
		'allowTags' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form']['allowTags'],
			'exclude'                 => true,
			'inputType'               => 'checkbox'
		),
		'storeValues' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form']['storeValues'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'targetTable' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form']['targetTable'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_form', 'getAllTables')
		),
		'tableless' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form']['tableless'],
			'exclude'                 => true,
			'inputType'               => 'checkbox'
		),
		'sendViaEmail' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form']['sendViaEmail'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'recipient' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form']['recipient'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'extnd')
		),
		'subject' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form']['subject'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'decodeEntities'=>true)
		),
		'format' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form']['format'],
			'default'                 => 'raw',
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('raw', 'email', 'xml', 'csv'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_form'],
			'eval'                    => array('helpwizard'=>true)
		),
		'jumpTo' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form']['jumpTo'],
			'exclude'                 => true,
			'inputType'               => 'pageTree',
			'eval'                    => array('fieldType'=>'radio', 'helpwizard'=>true),
			'explanation'             => 'jumpTo'
		),
		'attributes' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form']['attributes'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('multiple'=>true, 'size'=>2, 'maxlength'=>240)
		)
	)
);


/**
 * Class tl_form
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class tl_form extends Backend
{

	/**
	 * Check permissions to edit table tl_form
	 */
	public function checkPermission()
	{
		$this->import('BackendUser', 'User');

		if ($this->User->isAdmin)
		{
			return;
		}

		// Set root IDs
		if (!is_array($this->User->forms) || count($this->User->forms) < 1)
		{
			$root = array(0);
		}
		else
		{
			$root = $this->User->forms;
		}

		$GLOBALS['TL_DCA']['tl_form']['config']['closed'] = true;
		$GLOBALS['TL_DCA']['tl_form']['list']['sorting']['root'] = $root;

		// Check current action
		switch ($this->Input->get('act'))
		{
			case 'select':
				// Allow
				break;

			case 'edit':
			case 'show':
				if (!in_array($this->Input->get('id'), $root))
				{
					$this->log('Not enough permissions to '.$this->Input->get('act').' form ID "'.$this->Input->get('id').'"', 'tl_form checkPermission', 5);
					$this->redirect('typolight/main.php?act=error');
				}
				break;

			case 'editAll':
				$session = $this->Session->getData();
				$session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $root);
				$this->Session->setData($session);
				break;

			default:
				if (strlen($this->Input->get('act')))
				{
					$this->log('Not enough permissions to '.$this->Input->get('act').' forms', 'tl_form checkPermission', 5);
					$this->redirect('typolight/main.php?act=error');
				}
				break;
		}
	}


	/**
	 * Return the copy form button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function copyForm($row, $href, $label, $title, $icon, $attributes)
	{
		if (!$this->User->isAdmin)
		{
			return '';
		}

		return '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}


	/**
	 * Return the delete form button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function deleteForm($row, $href, $label, $title, $icon, $attributes)
	{
		if (!$this->User->isAdmin)
		{
			return '';
		}

		return '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}


	/**
	 * Get all tables and return them as array
	 * @return array
	 */
	public function getAllTables()
	{
		return $this->Database->listTables();
	}
}

?>