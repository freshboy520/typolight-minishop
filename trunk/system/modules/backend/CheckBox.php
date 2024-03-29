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
 * Class CheckBox
 *
 * Provide methods to handle check boxes.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class CheckBox extends Widget
{

	/**
	 * Submit user input
	 * @var boolean
	 */
	protected $blnSubmitInput = true;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'be_widget_chk';

	/**
	 * Options
	 * @var array
	 */
	protected $arrOptions = array();


	/**
	 * Add specific attributes
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'options':
				$this->arrOptions = deserialize($varValue);
				foreach ($this->arrOptions as $key=>$arrOptions)
				{
					// Single dimension array
					if (is_numeric($key))
					{
						if ($arrOptions['default'])
						{
							$this->varValue = $arrOptions['value'];
						}
						continue;
					}
					// Multidimensional array
					foreach ($arrOptions as $arrOption)
					{
						if ($arrOption['default'])
						{
							$this->varValue = $arrOption['value'];
						}
					}
				}
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
	 * Clear result if nothing has been submitted
	 */
	public function validate()
	{
		parent::validate();

		if (!array_key_exists($this->strName, $_POST))
		{
			$this->varValue = '';
		}
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		$arrOptions = array();

		if (!$this->multiple && count($this->arrOptions) > 1)
		{
			$this->arrOptions = array($this->arrOptions[0]);
		}

		$state = $this->Session->get('checkbox_groups');

		// Toggle checkbox group
		if ($this->Input->get('cbc'))
		{
			$state[$this->Input->get('cbc')] = (isset($state[$this->Input->get('cbc')]) && $state[$this->Input->get('cbc')] == 1) ? 0 : 1;
			$this->Session->set('checkbox_groups', $state);

			$this->redirect(preg_replace('/(&(amp;)?|\?)cbc=[^& ]*/i', '', $this->Environment->request));
		}

		$blnFirst = true;

		foreach ($this->arrOptions as $i=>$arrOption)
		{
			// Single dimension array
			if (is_numeric($i))
			{
				$arrOptions[] = $this->generateCheckbox($arrOption, $i);
				continue;
			}

			$id = 'cbc_' . $this->strId . '_' . standardize($i);

			$img = 'folPlus';
			$display = 'none';

			if ($state[$id] || !is_array($state) || !array_key_exists($id, $state))
			{
				$img = 'folMinus';
				$display = 'block';
			}

			$arrOptions[] = '<div class="checkbox_toggler' . ($blnFirst ? '_first' : '') . '"><a href="' . $this->addToUrl('cbc=' . $id) . '" onclick="AjaxRequest.toggleCheckboxGroup(this, \'' . $id . '\'); Backend.getScrollOffset(); return false;"><img src="system/themes/' . $this->getTheme() . '/images/' . $img . '.gif" alt="toggle checkbox group" /></a>' . $i .	'</div><div id="' . $id . '" class="checkbox_options" style="display:' . $display . ';"><input type="checkbox" id="check_all_' . $id . '" class="tl_checkbox" onclick="Backend.toggleCheckboxGroup(this, \'' . $id . '\')" /> <label for="check_all_' . $id . '" style="color:#a6a6a6;"><em>' . $GLOBALS['TL_LANG']['MSC']['selectAll'] . '</em></label>';

			// Multidimensional array
			foreach ($arrOption as $k=>$v)
			{
				$arrOptions[] = $this->generateCheckbox($v, $i.'_'.$k);
			}

			$arrOptions[] = '</div>';
			$blnFirst = false;
		}

		// Add a "no entries found" message if there are no options
		if (!count($arrOptions))
		{
			$arrOptions[]= '<p class="tl_noopt">'.$GLOBALS['TL_LANG']['MSC']['noResult'].'</p>';
		}

        return sprintf('<div id="ctrl_%s" class="%s%s">%s</div>',
						$this->strId,
						($this->multiple ? 'tl_checkbox_container' : 'tl_checkbox_single_container'),
						(strlen($this->strClass) ? ' ' . $this->strClass : ''),
						str_replace('<br /></div><br />', '</div>', implode('<br />', $arrOptions)));
	}


	/**
	 * Generate a checkbox and return it as string
	 * @param array
	 * @param integer
	 * @return string
	 */
	private function generateCheckbox($arrOption, $i)
	{
		return sprintf('<input type="checkbox" name="%s" id="opt_%s" class="tl_checkbox" value="%s"%s%s onfocus="Backend.getScrollOffset();" /> <label for="opt_%s">%s</label>',
						$this->strName . ($this->multiple ? '[]' : ''),
						$this->strId.'_'.$i,
						($this->multiple ? specialchars($arrOption['value']) : 1),
						((is_array($this->varValue) && in_array($arrOption['value'] , $this->varValue) || $this->varValue == $arrOption['value']) ? ' checked="checked"' : ''),
						$this->getAttributes(),
						$this->strId.'_'.$i,
						$arrOption['label']);
	}
}

?>