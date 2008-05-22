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
 * @package    DfGallery
 * @license    LGPL
 * @filesource
 */


/**
 * Class ContentDfGallery 
 *
 * Provide methods to render the dfGallery content element.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer 
 * @package    Controller
 */
class ContentDfGallery extends Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_dfgallery';


	/**
	 * Make sure the UFO plugin is available
	 * @return string
	 */
	public function generate()
	{
		if (!file_exists(TL_ROOT . '/plugins/ufo/ufo.js'))
		{
			throw new Exception('Plugin "ufo" required');
		}

		if (!in_array('dfGallery', $this->Config->getActiveModules()))
		{
			throw new Exception('Module "dfGallery" required');
		}

		if (!strlen($this->singleSRC) || !is_dir(TL_ROOT . '/' . $this->singleSRC))
		{
			return '';
		}

		return parent::generate();
	}


	/**
	 * Generate module
	 */
	protected function compile()
	{
		$xmlName = 'system/html/' . md5($this->tstamp . $this->id . $GLOBALS['TL_LANGUAGE']) . '.xml';

		// Generate XML file
		if (!file_exists(TL_ROOT . '/' . $xmlName))
		{
			$xmlFile  = '<?xml version="1.0" encoding="utf-8"?>' . "\n";
			$xmlFile .= '<gallery>' . "\n";
			$xmlFile .= '  <config>' . "\n";
			$xmlFile .= '    <title>' . $this->dfTitle . '</title>' . "\n";
			$xmlFile .= '    <slideshow_interval>' . $this->dfInterval . '</slideshow_interval>' . "\n";
			$xmlFile .= '    <pause_slideshow>' . ($this->dfPause ? 'true' : 'false') . '</pause_slideshow>' . "\n";
			$xmlFile .= '  </config>' . "\n";
			$xmlFile .= '  <albums>' . "\n";
			$xmlFile .= '    <album title="' . $this->dfTitle . '" description="" type="typolight" url="' . $this->singleSRC . '"></album>' . "\n";
			$xmlFile .= '  </albums>' . "\n";
			$xmlFile .= '  <language>' . "\n";
			$xmlFile .= '    <string id="please wait" value="' . $GLOBALS['TL_LANG']['MSC']['pleaseWait'] . '" />' . "\n";
			$xmlFile .= '    <string id="loading" value="' . $GLOBALS['TL_LANG']['MSC']['loading'] . '" />' . "\n";
			$xmlFile .= '    <string id="previous page" value="' . $GLOBALS['TL_LANG']['MSC']['previous'] . '" />' . "\n";
			$xmlFile .= '    <string id="page % of %" value="' . str_replace('%s', '%', $GLOBALS['TL_LANG']['MSC']['totalPages']) . '" />' . "\n";
			$xmlFile .= '    <string id="next page" value="' . $GLOBALS['TL_LANG']['MSC']['next'] . '" />' . "\n";
			$xmlFile .= '  </language>' . "\n";
			$xmlFile .= '</gallery>' . "\n";

			$objFile = new File($xmlName);
			$objFile->write($xmlFile);
			$objFile->close();
		}
		
		$this->Template->alt = $this->alt;
		$this->Template->href = 'system/modules/dfGallery/gallery.swf';
		$this->Template->flashId = strlen($this->flashID) ? $this->flashID : 'swf_' . $this->id;
		$this->Template->flashvars = 'xmlFile=' . $xmlName;
		$this->Template->src = $this->singleSRC;

		$size = deserialize($this->dfSize);

		$this->Template->width = $size[0];
		$this->Template->height = $size[1];

		// Adjust movie size in the back end
		if (TL_MODE == 'BE' && $size[0] > 640)
		{
			$this->Template->width = 640;
			$this->Template->height = floor(640 * $size[1] / $size[0]);
		}
	}
}

?>