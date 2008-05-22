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
 * Class BackendTemplate
 *
 * Provide methods to handle back end templates.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class BackendTemplate extends Template
{

	/**
	 * Add the rich text editor configuration and custom style sheets
	 * @return string
	 */
	public function parse()
	{
		// Rich text editor configuration
		if (count($GLOBALS['TL_RTE']) && $GLOBALS['TL_CONFIG']['useRTE'])
		{
			$this->base = $this->Environment->base;
			$this->rteFields = implode(',', $GLOBALS['TL_RTE']['fields']);

			$strFile = sprintf('%s/system/config/%s.php', TL_ROOT, $GLOBALS['TL_RTE']['type']);

			if (!file_exists($strFile))
			{
				throw new Exception(sprintf('Cannot find rich text editor configuration file "%s.php"', $GLOBALS['TL_RTE']['type']));
			}

			$this->language = 'en';

			// Fallback to English if the user language is not supported
			if (file_exists(TL_ROOT . '/plugins/tinyMCE/langs/' . $GLOBALS['TL_LANGUAGE'] . '.js'))
			{
				$this->language = $GLOBALS['TL_LANGUAGE'];
			}

			ob_start();
			include($strFile);
			$this->rteConfig = ob_get_contents();
			ob_end_clean();
		}

		// Style sheets
		if (is_array($GLOBALS['TL_CSS']) && count($GLOBALS['TL_CSS']))
		{
			$strStyleSheets = '';

			foreach ($GLOBALS['TL_CSS'] as $stylesheet)
			{
				$strStyleSheets .= '<link rel="stylesheet" type="text/css" href="' . $stylesheet . '" media="all" />' . "\n";
			}

			$this->stylesheets = $strStyleSheets;
		}

		// JavaScripts
		if (is_array($GLOBALS['TL_JAVASCRIPT']) && count($GLOBALS['TL_JAVASCRIPT']))
		{
			$strJavaScripts = '';

			foreach ($GLOBALS['TL_JAVASCRIPT'] as $javascript)
			{
				$strJavaScripts .= '<script type="text/javascript" src="' . $javascript . '"></script>' . "\n";
			}

			$this->javascripts = $strJavaScripts;
		}

		return parent::parse();
	}
}

?>