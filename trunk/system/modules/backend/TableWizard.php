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
 * Class TableWizard
 *
 * Provide methods to handle table fields.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class TableWizard extends Widget
{

	/**
	 * Submit user input
	 * @var boolean
	 */
	protected $blnSubmitInput = true;

	/**
	 * Rows
	 * @var integer
	 */
	protected $intRows = 12;

	/**
	 * Columns
	 * @var integer
	 */
	protected $intCols = 80;

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

			case 'rows':
				$this->intRows = $varValue;
				break;

			case 'cols':
				$this->intCols = $varValue;
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
		$arrColButtons = array('ccopy', 'cmovel', 'cmover', 'cdelete');
		$arrRowButtons = array('rcopy', 'rup', 'rdown', 'rdelete');

		// Change the order
		if ($this->Input->get('command') && is_numeric($this->Input->get('cid')) && $this->Input->get('id') == $this->currentRecord)
		{
			$this->import('Database');

			switch ($this->Input->get('command'))
			{
					case 'ccopy':
					for ($i=0; $i<count($this->varValue); $i++)
					{
						$this->varValue[$i] = array_duplicate($this->varValue[$i], $this->Input->get('cid'));
					}
					break;

				case 'cmovel':
					for ($i=0; $i<count($this->varValue); $i++)
					{
						$this->varValue[$i] = array_move_up($this->varValue[$i], $this->Input->get('cid'));
					}
					break;

				case 'cmover':
					for ($i=0; $i<count($this->varValue); $i++)
					{
						$this->varValue[$i] = array_move_down($this->varValue[$i], $this->Input->get('cid'));
					}
					break;

				case 'cdelete':
					for ($i=0; $i<count($this->varValue); $i++)
					{
						$this->varValue[$i] = array_delete($this->varValue[$i], $this->Input->get('cid'));
					}
					break;

				case 'rcopy':
					$this->varValue = array_duplicate($this->varValue, $this->Input->get('cid'));
					break;

				case 'rup':
					$this->varValue = array_move_up($this->varValue, $this->Input->get('cid'));
					break;

				case 'rdown':
					$this->varValue = array_move_down($this->varValue, $this->Input->get('cid'));
					break;

				case 'rdelete':
					$this->varValue = array_delete($this->varValue, $this->Input->get('cid'));
					break;
			}

			$this->Database->prepare("UPDATE " . $this->strTable . " SET " . $this->strField . "=? WHERE id=?")
						   ->execute(serialize($this->varValue), $this->currentRecord);

			$this->redirect(preg_replace('/&(amp;)?cid=[^&]*/i', '', preg_replace('/&(amp;)?command=[^&]*/i', '', $this->Environment->request)));
		}

		// Make sure there is at least an empty array
		if (!is_array($this->varValue) || !$this->varValue[0])
		{
			$this->varValue = array(array(''));
		}

		// Begin table
		$return .= '<div id="tl_tablewizard">
  <table cellspacing="0" cellpadding="0" class="tl_tablewizard" id="ctrl_'.$this->strId.'" summary="Table wizard">
  <tbody>
    <tr>';

		// Add column buttons
		for ($i=0; $i<count($this->varValue[0]); $i++)
		{
			$return .= '
      <td style="text-align:center; white-space:nowrap;">';

			// Add column buttons
			foreach ($arrColButtons as $button)
			{
				$return .= '<a href="'.$this->addToUrl('&amp;command='.$button.'&amp;cid='.$i.'&amp;id='.$this->currentRecord).'" title="'.specialchars($GLOBALS['TL_LANG'][$this->strTable][$button][0]).'" onclick="Backend.tableWizard(this, \''.$button.'\', \'ctrl_'.$this->strId.'\'); return false;">'.$this->generateImage(substr($button, 1).'.gif', $GLOBALS['TL_LANG'][$this->strTable][$button][0], 'class="tl_tablewizard_img"').'</a> ';
			}

			$return .= '</td>';
		}

		$return .= '
      <td></td>
    </tr>';

		// Add rows
		for ($i=0; $i<count($this->varValue); $i++)
		{
			$return .= '
    <tr>';

			// Add cells
			for ($j=0; $j<count($this->varValue[$i]); $j++)
			{
				$return .= '
      <td><textarea name="'.$this->strId.'['.$i.']['.$j.']" class="tl_textarea" rows="'.$this->intRows.'" cols="'.$this->intCols.'"'.$this->getAttributes().'>'.specialchars($this->varValue[$i][$j]).'</textarea></td>';
			}

			$return .= '
      <td style="white-space:nowrap;">';

			// Add row buttons
			foreach ($arrRowButtons as $button)
			{
				$return .= '<a href="'.$this->addToUrl('&amp;command='.$button.'&amp;cid='.$i.'&amp;id='.$this->currentRecord).'" title="'.specialchars($GLOBALS['TL_LANG'][$this->strTable][$button][0]).'" onclick="Backend.tableWizard(this, \''.$button.'\', \'ctrl_'.$this->strId.'\'); return false;">'.$this->generateImage(substr($button, 1).'.gif', $GLOBALS['TL_LANG'][$this->strTable][$button][0], 'class="tl_tablewizard_img"').'</a> ';
			}

			$return .= '</td>
    </tr>';
		}

		return $return.'
  </tbody>
  </table>
  </div>';
	}
}

?>