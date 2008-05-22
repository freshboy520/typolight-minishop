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


$GLOBALS['BE_FFL']['i7shopCategorieTree'] = "i7shopCategorieTree";
		
$GLOBALS['TL_DCA']['tl_module']['palettes']['i7shop'] = "name,type,i7shop_shopsystem,i7shop_template,i7shop_defineRootCategorie";
$GLOBALS['TL_DCA']['tl_module']['palettes']['i7shopBasketInfo'] = "name,type,i7shop_shopsystem,i7shop_basket_info_always_visible,jumpTo,i7shop_hide_on_page";


$GLOBALS['TL_DCA']['tl_module']['fields']['i7shop_shopsystem'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['i7SHOP']['i7shop_shopsystem'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'foreignKey'              => 'tl_i7shop.name'
);

$GLOBALS['TL_DCA']['tl_module']['fields']['i7shop_basket_info_always_visible'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['i7SHOP']['i7shop_basket_info_always_visible'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
);

$GLOBALS['TL_DCA']['tl_module']['fields']['i7shop_hide_on_page'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['i7SHOP']['i7shop_hide_on_page'],
	'exclude'                 => true,
	'inputType'               => 'pageTree',
	'eval'                    => array('fieldType'=>'checkbox'),
);

/* -todo- not active at the moment, FOR LATER USE */
$GLOBALS['TL_DCA']['tl_module']['fields']['i7shop_defineRootCategorie'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['rootPage'],
	'exclude'                 => true,
	'inputType'               => 'i7shopCategorieTree',
);


$GLOBALS['TL_DCA']['tl_module']['fields']['i7shop_template'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['i7shop_template'],
	'default'                 => 'shop_main',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => $this->getTemplateGroup('shop_')
);


/* -todo- not active at the moment, FOR LATER USE */
class i7shopCategorieTree extends PageTree
{
	
	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{

		return '<ul class="tl_listing'.(strlen($this->strClass) ? ' ' . $this->strClass : '').'" id="'.$this->strId.'">
    <li class="tl_folder_top"><div class="tl_left">'.$this->generateImage((strlen($GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['icon']) ? $GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['icon'] : 'pagemounts.gif')).' '.(strlen($GLOBALS['TL_CONFIG']['websiteTitle']) ? $GLOBALS['TL_CONFIG']['websiteTitle'] : 'TYPOlight webCMS').'</div> <div class="tl_right"><label for="ctrl_'.$this->strId.'" class="tl_change_selected">'.$GLOBALS['TL_LANG']['MSC']['changeSelected'].'</label> <input type="checkbox" name="'.$this->strName.'_save" id="ctrl_'.$this->strId.'" class="tl_tree_checkbox" value="1" onclick="Backend.showTreeBody(this, \''.$this->strId.'_parent\');" /></div><div style="clear:both;"></div></li><li class="parent" id="'.$this->strId.'_parent"><ul>'.$this->renderCategorietree(0, -20).'
  </ul></li></ul>';
	}
	
	/**
	 * Generate a particular subpart of the page tree and return it as HTML string
	 * @param integer
	 * @param string
	 * @param integer
	 * @return string
	 */
	public function generateAjax($id, $strField, $level)
	{

		$this->strField = $strField;

		if (!$this->Input->post('isAjax'))
		{
			return '';
		}

		if ($this->Database->fieldExists($strField, $this->strTable))
		{
			$objField = $this->Database->prepare("SELECT " . $strField . " FROM " . $this->strTable . " WHERE id=?")
									   ->limit(1)
									   ->execute($this->strId);

			if ($objField->numRows)
			{
				$this->varValue = deserialize($objField->$strField);
			}
		}

		return $this->renderCategorietree($id, ($level * 20));
	}
	
	/**
	 * Recursively render the pagetree
	 * @param int
	 * @param integer
	 * @param boolean
	 * @return string
	 */
	private function renderCategorietree($pid, $intMargin, $protectedPage=false)
	{

		static $session;
		$session = $this->Session->getData();

		$flag = substr($this->strField, 0, 2);
		$node = 'tree_' . $this->strTable . '_' . $this->strField;
		$xtnode = 'tree_' . $this->strTable . '_' . $this->strName;
		// Get session data and toggle nodes
		if ($this->Input->get($flag.'tg'))
		{
			$session[$node][$this->Input->get($flag.'tg')] = (isset($session[$node][$this->Input->get($flag.'tg')]) && $session[$node][$this->Input->get($flag.'tg')] == 1) ? 0 : 1;
			$this->Session->setData($session);

			$this->redirect(preg_replace('/(&(amp;)?|\?)'.$flag.'tg=[^& ]*/i', '', $this->Environment->request));
		}
		
		$objNodes = $this->Database->prepare("SELECT * FROM tl_i7shop_articletree WHERE pid=? ORDER BY sorting")
								   ->execute($pid);
		// Return if there are no pages
		if ($objNodes->numRows < 1)
		{
			return '';
		}

		$return = '';
		$intSpacing = 20;

		// Add pages
		while ($objNodes->next())
		{
			$objChilds = $this->Database->prepare("SELECT * FROM tl_i7shop_articletree WHERE pid=?")
										->execute($objNodes->id);

			$return .= "\n    " . '<li class="'.(($objNodes->type == 'root') ? 'tl_folder' : 'tl_file').'" onmouseover="Theme.hoverDiv(this, 1);" onmouseout="Theme.hoverDiv(this, 0);"><div class="tl_left" style="padding-left:'.($intMargin + $intSpacing).'px;">';

			$folderAttribute = 'style="margin-left:20px;"';
			$session[$node][$objNodes->id] = is_numeric($session[$node][$objNodes->id]) ? $session[$node][$objNodes->id] : 0;
			$level = ($intMargin / $intSpacing + 1);

			if ($objChilds->numRows)
			{
				$folderAttribute = '';
				$img = ($session[$node][$objNodes->id] == 1) ? 'folMinus.gif' : 'folPlus.gif';
				$return .= '<a href="'.$this->addToUrl($flag.'tg='.$objNodes->id).'" onclick="Backend.getScrollOffset(); return AjaxRequest.togglePagetree(this, \''.$xtnode.'_'.$objNodes->id.'\', \''.$this->strField.'\', \''.$this->strName.'\', '.$level.');">'.$this->generateImage($img, '', 'style="margin-right:2px;"').'</a>';
			}

			$sub = 0;
			$image = $objNodes->type.'.gif';
			$objNodes->protected = ($objNodes->protected || $protectedPage);
			$objNodes->protected = false;
		
			// Get image name
			if ($sub > 0)
			{
				$image = $objNodes->type.'_'.$sub.'.gif';
			}

			// Add page name
			$return .= $this->generateImage($image, '', $folderAttribute).' <label for="'.$this->strName.'_'.$objNodes->id.'">'.(($objNodes->type == 'root') ? '<strong>' : '').$objNodes->title.(($objNodes->type == 'root') ? '</strong>' : '').'</label></div> <div class="tl_right">';
			
			$return .= '<input type="radio" name="'.$this->strName.'" id="'.$this->strName.'_'.$objNodes->id.'" class="tl_tree_radio" value="'.specialchars($objNodes->id).'" onfocus="Backend.getScrollOffset();"'.$this->optionChecked($objNodes->id, $this->varValue).' />';
	
			$return .= '</div><div style="clear:both;"></div></li>';

			// Call next node
			if ($objChilds->numRows && $session[$node][$objNodes->id] == 1)
			{
				$return .= '<li class="parent" id="'.$xtnode.'_'.$objNodes->id.'"><ul class="level_'.$level.'">';
				$return .= $this->renderCategorietree($objNodes->id, ($intMargin + $intSpacing), ($objNodes->protected || $protectedPage));
				$return .= '</ul></li>';
			}
		}

		return $return;
	}
}


?>