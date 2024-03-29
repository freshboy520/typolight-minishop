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
 * Class ModuleWizard
 *
 * Provide methods to handle modules of a page layout.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class ModuleWizard extends Widget
{

	/**
	 * Submit user input
	 * @var boolean
	 */
	protected $blnSubmitInput = false;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'be_widget';


	/**
	 * Add specific attributes
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'value':
				$this->varValue = deserialize($varValue);
				break;

			case 'mandatory':
				$this->arrConfiguration['mandatory'] = $varValue ? true : false;
				break;

			default:
				parent::__set($strKey, $varValue);
				break;
		}
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		$this->import('Database');
		$arrButtons = array('copy', 'up', 'down', 'delete');

		// Change the order
		if ($this->Input->get('command') && is_numeric($this->Input->get('cid')) && $this->Input->get('id') == $this->currentRecord)
		{
			switch ($this->Input->get('command'))
			{
				case 'copy':
					$this->varValue = array_duplicate($this->varValue, $this->Input->get('cid'));
					break;

				case 'up':
					$this->varValue = array_move_up($this->varValue, $this->Input->get('cid'));
					break;

				case 'down':
					$this->varValue = array_move_down($this->varValue, $this->Input->get('cid'));
					break;

				case 'delete':
					$this->varValue = array_delete($this->varValue, $this->Input->get('cid'));
					break;
			}
		}

		// Get all modules from DB
		$objModules = $this->Database->execute("SELECT id, name FROM tl_module ORDER BY name");
		$modules[] = array('id'=>0, 'name'=>$GLOBALS['TL_LANG']['MOD']['article'][0]);

		if ($objModules->numRows)
		{
			$modules = array_merge($modules, $objModules->fetchAllAssoc());
		}

		$objRow = $this->Database->prepare("SELECT * FROM " . $this->strTable . " WHERE id=?")
								 ->limit(1)
								 ->execute($this->currentRecord);

		// Columns
		if ($objRow->numRows)
		{
			$cols = array('header');

			switch ($objRow->cols)
			{
				case '1c':
				case '1cl':
					$cols[] = 'main';
					break;

				case '2cll':
					$cols[] = 'left';
					$cols[] = 'main';
					break;

				case '2clr':
					$cols[] = 'main';
					$cols[] = 'right';
					break;

				case '3cl':
					$cols[] = 'left';
					$cols[] = 'main';
					$cols[] = 'right';
					break;
			}

			$cols[] = 'footer';
		}

		$arrSections = deserialize($objRow->sections);

		// Add custom page sections
		if (is_array($arrSections) && count($arrSections))
		{
			$cols = array_merge($cols, $arrSections);
		}

		// Get new value
		if ($this->Input->post('FORM_SUBMIT') == $this->strTable)
		{
			$this->varValue = $this->Input->post($this->strId);
		}

		// Make sure there is at least an empty array
		if (!is_array($this->varValue) || !$this->varValue[0])
		{
			$this->varValue = array('');
		}

		else
		{
			// Initialize sorting order
			foreach ($cols as $col)
			{
				$arrCols[$col] = array();
			}

			foreach ($this->varValue as $v)
			{
				// Add only modules of an active section
				if (in_array($v['col'], $cols))
				{
					$arrCols[$v['col']][] = $v;
				}
			}

			$this->varValue = array();

			foreach ($arrCols as $arrCol)
			{
				$this->varValue = array_merge($this->varValue, $arrCol);
			}
		}

		// Save the value
		if ($this->Input->get('command') || $this->Input->post('FORM_SUBMIT') == $this->strTable)
		{
			$this->Database->prepare("UPDATE " . $this->strTable . " SET " . $this->strField . "=? WHERE id=?")
						   ->execute(serialize($this->varValue), $this->currentRecord);

			// Reload the page
			if (is_numeric($this->Input->get('cid')) && $this->Input->get('id') == $this->currentRecord)
			{
				$this->redirect(preg_replace('/&(amp;)?cid=[^&]*/i', '', preg_replace('/&(amp;)?command=[^&]*/i', '', $this->Environment->request)));
			}
		}

		// Add label and return wizard
		$return .= '<table cellspacing="0" cellpadding="0" id="ctrl_'.$this->strId.'" class="tl_modulewizard" summary="Module wizard">
  <thead>
  <tr>
    <td>'.$GLOBALS['TL_LANG']['tl_layout']['module'].'</td>
    <td>'.$GLOBALS['TL_LANG']['tl_layout']['column'].'</td>
    <td>&nbsp;</td>
  </tr>
  </thead>
  <tbody>';

		// Add input fields
		for ($i=0; $i<count($this->varValue); $i++)
		{
			$options = '';

			// Add modules
			foreach ($modules as $v)
			{
				$options .= '<option value="'.specialchars($v['id']).'"'.$this->optionSelected($v['id'], $this->varValue[$i]['mod']).'>'.$v['name'].'</option>';
			}

			$return .= '
  <tr>
    <td><select name="'.$this->strId.'['.$i.'][mod]" class="tl_select" onfocus="Backend.getScrollOffset();">'.$options.'</select></td>';

			$options = '';

			// Add column
			foreach ($cols as $v)
			{
				$options .= '<option value="'.specialchars($v).'"'.$this->optionSelected($v, $this->varValue[$i]).'>'.$v.'</option>';
			}

			$return .= '
    <td><select name="'.$this->strId.'['.$i.'][col]" class="tl_select_column" onfocus="Backend.getScrollOffset();">'.$options.'</select></td>
    <td>';

			foreach ($arrButtons as $button)
			{
				$return .= '<a href="'.$this->addToUrl('command='.$button.'&amp;cid='.$i.'&amp;id='.$this->currentRecord).'" title="'.specialchars($GLOBALS['TL_LANG'][$this->strTable]['wz_'.$button]).'" onclick="Backend.moduleWizard(this, \''.$button.'\',  \'ctrl_'.$this->strId.'\'); return false;">'.$this->generateImage($button.'.gif', $GLOBALS['TL_LANG'][$this->strTable]['wz_'.$button], 'class="tl_listwizard_img"').'</a> ';
			}

			$return .= '</td>
  </tr>';
		}

		return $return.'
  </tbody>
  </table>';
	}
}

?>