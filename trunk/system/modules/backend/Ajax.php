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
 * Class Ajax
 *
 * Provide methods to handle Ajax requests.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class Ajax extends Backend
{

	/**
	 * Ajax action
	 * @var string
	 */
	protected $strAction;

	/**
	 * Ajax id
	 * @var string
	 */
	protected $strAjaxId;

	/**
	 * Ajax key
	 * @var string
	 */
	protected $strAjaxKey;

	/**
	 * Ajax name
	 * @var string
	 */
	protected $strAjaxName;


	/**
	 * Get the current action
	 * @throws Exception
	 */
	public function __construct($strAction)
	{
		if (!$strAction)
		{
			throw new Exception('Missing Ajax action');
		}

		$this->strAction = $strAction;
		parent::__construct();
	}


	/**
	 * Ajax actions that do not require a data container object
	 */
	public function executePreActions()
	{
		switch ($this->strAction)
		{
			// Toggle navigation menu
			case 'toggleNavigation':
				$bemod = $this->Session->get('backend_modules');
				$bemod[$this->Input->post('id')] = intval($this->Input->post('state'));

				$this->Session->set('backend_modules', $bemod);
				exit; break;

			// Load a navigation menu group
			case 'loadNavigation':
				$bemod = $this->Session->get('backend_modules');
				$bemod[$this->Input->post('id')] = intval($this->Input->post('state'));
				$this->Session->set('backend_modules', $bemod);

				$this->import('BackendUser', 'User');
				$navigation = $this->User->navigation();

				$objTemplate = new BackendTemplate('be_navigation');
				$objTemplate->modules = $navigation[$this->Input->post('id')]['modules'];

				$objTemplate->output();
				exit; break;

			// Toggle nodes of the file or page tree
			case 'toggleStructure':
			case 'toggleFileManager':
			case 'togglePagetree':
			case 'toggleFiletree':
				$this->strAjaxId = preg_replace('/.*_([0-9a-zA-Z]+)$/i', '$1', $this->Input->post('id'));
				$this->strAjaxKey = str_replace('_' . $this->strAjaxId, '', $this->Input->post('id'));

				if ($this->Input->get('act') == 'editAll')
				{
					$this->strAjaxKey = preg_replace('/(.*)_[0-9a-zA-Z]+$/i', '$1', $this->strAjaxKey);
					$this->strAjaxName = preg_replace('/.*_([0-9a-zA-Z]+)$/i', '$1', $this->Input->post('name'));
				}

				$nodes = $this->Session->get($this->strAjaxKey);
				$nodes[$this->strAjaxId] = intval($this->Input->post('state'));

				$this->Session->set($this->strAjaxKey, $nodes);
				exit; break;

			// Load nodes of the file or page tree
			case 'loadStructure':
			case 'loadFileManager':
			case 'loadPagetree':
			case 'loadFiletree':
				$this->strAjaxId = preg_replace('/.*_([0-9a-zA-Z]+)$/i', '$1', $this->Input->post('id'));
				$this->strAjaxKey = str_replace('_' . $this->strAjaxId, '', $this->Input->post('id'));

				if ($this->Input->get('act') == 'editAll')
				{
					$this->strAjaxKey = preg_replace('/(.*)_[0-9a-zA-Z]+$/i', '$1', $this->strAjaxKey);
					$this->strAjaxName = preg_replace('/.*_([0-9a-zA-Z]+)$/i', '$1', $this->Input->post('name'));
				}

				$nodes = $this->Session->get($this->strAjaxKey);
				$nodes[$this->strAjaxId] = intval($this->Input->post('state'));

				$this->Session->set($this->strAjaxKey, $nodes);
				break;

			// Toggle the visibility of content elements
			case 'toggleVisibility':
				$this->Database->prepare("UPDATE tl_content SET invisible='" . (intval($this->Input->post('state') == 1) ? '' : 1) . "' WHERE id=?")
							   ->execute($this->Input->post('id'));
				exit; break;

			// Check whether the temporary directory is writeable
			case 'liveUpdate':
				$GLOBALS['TL_CONFIG']['liveUpdateId'] = $this->Input->post('id');
				$this->Config->update("\$GLOBALS['TL_CONFIG']['liveUpdateId']", $this->Input->post('id'));

				try
				{
					$objFile = new File('system/tmp/' . md5(uniqid('', true)));
					$objFile->close();
					$objFile->delete();
				}

				// Temp directory not writeable
				catch (Exception $e)
				{
					if ($e->getCode() == 0)
					{
						$this->loadLanguageFile('tl_maintenance');

						header('Content-Type: text/html; charset='.$GLOBALS['TL_CONFIG']['characterSet']);
						echo '<p class="tl_error">' . $GLOBALS['TL_LANG']['tl_maintenance']['notWriteable'] . '</p>';
					}
				}

				// Empty live update ID
				if (!strlen($this->Input->post('id')))
				{
					$this->loadLanguageFile('tl_maintenance');

					header('Content-Type: text/html; charset='.$GLOBALS['TL_CONFIG']['characterSet']);
					echo '<p class="tl_error">' . $GLOBALS['TL_LANG']['tl_maintenance']['emptyLuId'] . '</p>';
				}
				exit; break;

			// Toggle checkbox groups
			case 'toggleCheckboxGroup':
				$state = $this->Session->get('checkbox_groups');
				$state[$this->Input->post('id')] = intval($this->Input->post('state'));

				$this->Session->set('checkbox_groups', $state);
				break;
		}
	}


	/**
	 * Ajax actions that do require a data container object
	 * @param object
	 */
	public function executePostActions(DataContainer $dc)
	{
		header('Content-Type: text/html; charset='.$GLOBALS['TL_CONFIG']['characterSet']);

		switch ($this->strAction)
		{
			// Load nodes of the page structure tree
			case 'loadStructure':
				echo $dc->ajaxTreeView($this->strAjaxId, intval($this->Input->post('level')));
				exit; break;

			// Load nodes of the file manager tree
			case 'loadFileManager':
				echo $dc->ajaxTreeView($this->Input->post('folder', DECODE_ENTITIES), intval($this->Input->post('level')));
				exit; break;

			// Load nodes of the page tree
			case 'loadPagetree':
				$arrData['strTable'] = $dc->table;
				$arrData['id'] = strlen($this->strAjaxName) ? $this->strAjaxName : $dc->id;
				$arrData['name'] = $this->Input->post('name');

				$objWidget = new $GLOBALS['BE_FFL']['pageTree']($arrData, $dc);

				echo $objWidget->generateAjax($this->strAjaxId, $this->Input->post('field'), intval($this->Input->post('level')));
				exit; break;

			// Load nodes of the file tree
			case 'loadFiletree':
				$arrData['strTable'] = $dc->table;
				$arrData['id'] = strlen($this->strAjaxName) ? $this->strAjaxName : $dc->id;
				$arrData['name'] = $this->Input->post('name');

				$objWidget = new $GLOBALS['BE_FFL']['fileTree']($arrData, $dc);

				echo $objWidget->generateAjax($this->Input->post('folder', DECODE_ENTITIES), $this->Input->post('field'), intval($this->Input->post('level')));
				exit; break;

			// Toggle subpalettes
			case 'toggleSubpalette':
				if ($dc instanceof DC_Table)
				{
					if ($this->Input->get('act') == 'editAll')
					{
						$this->strAjaxId = preg_replace('/.*_([0-9a-zA-Z]+)$/i', '$1', $this->Input->post('id'));
						$this->Database->prepare("UPDATE " . $dc->table . " SET " . $this->Input->post('field') . "='" . (intval($this->Input->post('state') == 1) ? 1 : '') . "' WHERE id=?")->execute($this->strAjaxId);

						if ($this->Input->post('load'))
						{
							echo $dc->editAll($this->strAjaxId, $this->Input->post('id'));
						}
					}

					else
					{
						$this->Database->prepare("UPDATE " . $dc->table . " SET " . $this->Input->post('field') . "='" . (intval($this->Input->post('state') == 1) ? 1 : '') . "' WHERE id=?")->execute($dc->id);

						if ($this->Input->post('load'))
						{
							echo $dc->edit(false, $this->Input->post('id'));
						}
					}
				}

				elseif ($dc instanceof DC_File)
				{
					$val = (intval($this->Input->post('state') == 1) ? true : false);
					$this->Config->update("\$GLOBALS['TL_CONFIG']['".$this->Input->post('field')."']", $val);

					if ($this->Input->post('load'))
					{
						$GLOBALS['TL_CONFIG'][$this->Input->post('field')] = $val;
						echo $dc->edit(false, $this->Input->post('id'));
					}
				}
				exit; break;
		}
	}
}

?>