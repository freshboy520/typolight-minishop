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
 * @copyright  Leo Feyer 2008 
 * @author     Leo Feyer <leo@typolight.org> 
 * @package    Faq 
 * @license    LGPL 
 * @filesource
 */


/**
 * Class ModuleFaqReader 
 *
 * @copyright  Leo Feyer 2008 
 * @author     Leo Feyer <leo@typolight.org> 
 * @package    Controller
 */
class ModuleFaqReader extends Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_faqreader';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$this->Template = new Template('be_wildcard');
			$this->Template->wildcard = '### FAQ READER ###';

			return $this->Template->parse();
		}

		// Return if no news item has been specified
		if (!$this->Input->get('items'))
		{
			return '';
		}

		$this->faq_categories = deserialize($this->faq_categories, true);

		// Return if there are no categories
		if (!is_array($this->faq_categories) || count($this->faq_categories) < 1)
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
		global $objPage;

		$this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];
		$this->Template->referer = $this->getReferer(ENCODE_AMPERSANDS);

		$objFaq = $this->Database->prepare("SELECT *, (SELECT title FROM tl_faq_category WHERE tl_faq_category.id=tl_faq.pid) AS category, (SELECT name FROM tl_user WHERE tl_user.id=tl_faq.author) AS authorsName FROM tl_faq WHERE pid IN(" . implode(',', $this->faq_categories) . ") AND (id=? OR alias=?)" . (!BE_USER_LOGGED_IN ? " AND published=?" : ""))
								 ->limit(1)
								 ->execute($this->Input->get('items'), $this->Input->get('items'), 1);

		if ($objFaq->numRows < 1)
		{
			$this->Template->error = '<p class="error">Invalid FAQ ID</p>';

			// Do not index the page
			$objPage->noSearch = 1;
			$objPage->cache = 0;

			// Send 404 header
			header('HTTP/1.0 404 Not Found');
			return;
		}

		// Overwrite page title
		if (strlen($objFaq->question))
		{
			$objPage->pageTitle = $objFaq->question;
		}

		$this->Template->question = $objFaq->question;
		$this->Template->answer = $objFaq->answer;
		$this->Template->addImage = false;

		// Add image
		if ($objFaq->addImage && is_file(TL_ROOT . '/' . $objFaq->singleSRC))
		{
			$size = deserialize($objFaq->size);
			$src = $this->getImage($this->urlEncode($objFaq->singleSRC), $size[0], $size[1]);

			if (($imgSize = @getimagesize(TL_ROOT . '/' . $src)) !== false)
			{
				$this->Template->imgSize = ' ' . $imgSize[3];
			}

			$this->Template->src = $src;
			$this->Template->href = $objFaq->singleSRC;
			$this->Template->alt = htmlspecialchars($objFaq->alt);
			$this->Template->fullsize = $objFaq->fullsize ? true : false;
			$this->Template->margin = $this->generateMargin(deserialize($objFaq->imagemargin), 'padding');
			$this->Template->float = in_array($objFaq->floating, array('left', 'right')) ? sprintf(' float:%s;', $objFaq->floating) : '';
			$this->Template->caption = $objFaq->caption;
			$this->Template->addImage = true;
		}

		// Add enclosure
		if ($objFaq->addEnclosure && strlen($objFaq->enclosure) && is_file(TL_ROOT . '/' . $objFaq->enclosure))
		{
			$objFile = new File($objFaq->enclosure);
			$allowedDownload = trimsplit(',', strtolower($GLOBALS['TL_CONFIG']['allowedDownload']));

			if (in_array($objFile->extension, $allowedDownload))
			{
				$token = $this->createDownloadToken($objFaq->enclosure);
				$size = ' ('.number_format(($objFile->filesize/1024), 1, $GLOBALS['TL_LANG']['MSC']['decimalSeparator'], $GLOBALS['TL_LANG']['MSC']['thousandsSeparator']).' kB)';
				$src = 'system/themes/' . $this->getTheme() . '/images/' . $objFile->icon;

				if (($imgSize = @getimagesize(TL_ROOT . '/' . $src)) !== false)
				{
					$this->Template->enclosureSize = ' ' . $imgSize[3];
				}

				$this->Template->enclosureIcon = $src;
				$this->Template->enclosureLink = basename($objFaq->enclosure) . $size;
				$this->Template->enclosureTitle = ucfirst(str_replace('_', ' ', $objFile->filename));
				$this->Template->enclosureHref = 'download.php?src=' . $this->urlEncode($objFaq->enclosure) . '&amp;token=' . $token . (!$this->Input->cookie('PHPSESSID') ? '&amp;PHPSESSID=' . session_id() : '');
				$this->Template->enclosure = $objFaq->enclosure;
			}
		}

		$this->Template->info = sprintf($GLOBALS['TL_LANG']['MSC']['faqCreatedBy'], date($GLOBALS['TL_CONFIG']['dateFormat'], $objFaq->tstamp), $objFaq->authorsName);
	}
}

?>