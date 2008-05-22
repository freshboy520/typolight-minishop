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
 * @package    News
 * @license    LGPL
 * @filesource
 */


/**
 * Class News
 *
 * Provide methods regarding news archives.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class News extends Frontend
{

	/**
	 * Update the current feed
	 */
	public function generateFeed()
	{
		$objArchive = $this->Database->prepare("SELECT * FROM tl_news_archive WHERE id=? AND makeFeed=?")
									 ->limit(1)
									 ->execute(CURRENT_ID, 1);

		if ($objArchive->numRows)
		{
			$this->generateFiles($objArchive->fetchAssoc());
		}
	}


	/**
	 * Delete old files and generate all feeds
	 */
	public function generateFeeds()
	{
		$this->removeOldFeeds();
		$objArchive = $this->Database->execute("SELECT * FROM tl_news_archive WHERE makeFeed=1");

		while ($objArchive->next())
		{
			$this->generateFiles($objArchive->row());
		}
	}


	/**
	 * Generate an XML files and save them to the root directory
	 * @param array
	 */
	private function generateFiles($arrArchive)
	{
		$strType = ($arrArchive['format'] == 'atom') ? 'generateAtom' : 'generateRss';
		$strFile = strlen($arrArchive['alias']) ? $arrArchive['alias'] : 'news' . $arrArchive['id'];
		$strLink = strlen($arrArchive['feedBase']) ? $arrArchive['feedBase'] : $this->Environment->base;

		$objFeed = new Feed($strFile);

		$objFeed->link = $strLink;
		$objFeed->title = $arrArchive['title'];
		$objFeed->description = $arrArchive['description'];
		$objFeed->language = $arrArchive['language'];
		$objFeed->published = $arrArchive['tstamp'];

		// Get items
		$objArticleStmt = $this->Database->prepare("SELECT * FROM tl_news WHERE pid=? AND published=? ORDER BY date DESC");

		if ($arrArchive['maxItems'] > 0)
		{
			$objArticleStmt->limit($arrArchive['maxItems']);
		}

		$objArticle = $objArticleStmt->execute($arrArchive['id'], 1);

		// Get default URL
		$objParent = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
									->limit(1)
									->execute($arrArchive['jumpTo']);

		$strUrl = $this->generateFrontendUrl($objParent->fetchAssoc(), '/items/%s');

		// Parse items
		while ($objArticle->next())
		{
			$objItem = new FeedItem();

			$objItem->title = $objArticle->headline;
			$objItem->description = strlen($objArticle->teaser) ? $objArticle->teaser : $objArticle->text;
			$objItem->link = (($objArticle->source == 'external') ? '' : $strLink) . $this->getLink($objArticle, $strUrl);
			$objItem->published = $objArticle->date;
			$objItem->content = $objArticle->text;

			// Enclosure
			if ($objArticle->addEnclosure && strlen($objArticle->enclosure) && is_file(TL_ROOT . '/' . $objArticle->enclosure))
			{
				$objItem->addEnclosure($objArticle->enclosure);
			}

			$objFeed->addItem($objItem);
		}

		// Create file
		$objRss = new File($strFile . '.xml');
		$objRss->write($this->replaceInsertTags($objFeed->$strType()));
		$objRss->close();
	}


	/**
	 * Add news items to the indexer
	 * @param array
	 * @param integer
	 * @return array
	 */
	public function getSearchablePages($arrPages, $intRoot=0)
	{
		$arrRoot = array();

		if ($intRoot > 0)
		{
			$arrRoot = $this->getChildRecords($intRoot, 'tl_page', true);
		}

		$objArchive = $this->Database->prepare("SELECT id, jumpTo FROM tl_news_archive WHERE protected!=?")
									 ->execute(1);

		// Walk through each archive
		while ($objArchive->next())
		{
			if (is_array($arrRoot) && count($arrRoot) > 0 && !in_array($objArchive->jumpTo, $arrRoot))
			{
				continue;
			}

			// Get default URL
			$objParent = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=? AND (start=? OR start<?) AND (stop=? OR stop>?) AND published=?")
										->limit(1)
										->execute($objArchive->jumpTo, '', time(), '', time(), 1);

			if ($objParent->numRows < 1)
			{
				continue;
			}

			$strUrl = $this->generateFrontendUrl($objParent->fetchAssoc(), '/items/%s');

			// Get items
			$objArticle = $this->Database->prepare("SELECT * FROM tl_news WHERE pid=? AND source!=? AND published=? ORDER BY date DESC")
										 ->execute($objArchive->id, 'external', 1);

			// Add items to the indexer
			while ($objArticle->next())
			{
				$arrPages[] = $this->getLink($objArticle, $strUrl);
			}
		}

		return $arrPages;
	}


	/**
	 * Return the link of a news article
	 * @param object
	 * @param string
	 * @return string
	 */
	private function getLink(Database_Result $objArticle, $strUrl)
	{
		if ($objArticle->source == 'external')
		{
			return $objArticle->url;
		}

		if ($objArticle->source == 'internal')
		{
			$objParent = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
										->limit(1)
										->execute($objArticle->jumpTo);

			return $this->generateFrontendUrl($objParent->fetchAssoc());
		}

		return sprintf($strUrl, ((strlen($objArticle->alias) && !$GLOBALS['TL_CONFIG']['disableAlias']) ? $objArticle->alias : $objArticle->id));
	}
}

?>