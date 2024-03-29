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
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Class ContentAlias
 *
 * Front end content element "alias".
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class ContentAlias extends ContentElement
{

	/**
	 * Parse the template
	 * @return string
	 */
	public function generate()
	{
		$objElement = $this->Database->prepare("SELECT * FROM tl_content WHERE id=?")
									 ->limit(1)
									 ->execute($this->cteAlias);

		if ($objElement->numRows < 1)
		{
			return '';
		}

		$strClass = $this->findContentElement($objElement->type);

		if (!$this->classFileExists($strClass))
		{
			return '';
		}

		$objElement->typePrefix = 'ce_';
		$objElement = new $strClass($objElement);

		// Overwrite spacing, alignment and CSS ID
		$objElement->space = $this->space;
		$objElement->align = $this->align;
		$objElement->cssID = $this->cssID;

		return $objElement->generate();
	}


	/**
	 * Generate content element
	 */
	protected function compile()
	{
		return;
	}
}

?>