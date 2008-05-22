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
 * @package    Calendar
 * @license    LGPL
 * @filesource
 */


/**
 * Class Calendar
 *
 * Provide methods regarding calendars.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class Calendar extends Frontend
{

	/**
	 * Current events
	 * @var array
	 */
	protected $arrEvents = array();


	/**
	 * Update the current feed
	 */
	public function generateFeed()
	{
		$objCalendar = $this->Database->prepare("SELECT * FROM tl_calendar WHERE id=? AND makeFeed=?")
									  ->limit(1)
									  ->execute(CURRENT_ID, 1);

		if ($objCalendar->numRows)
		{
			$this->generateFiles($objCalendar->fetchAssoc());
		}
	}


	/**
	 * Delete old files and generate all feeds
	 */
	public function generateFeeds()
	{
		$this->removeOldFeeds();
		$objCalendar = $this->Database->execute("SELECT * FROM tl_calendar WHERE makeFeed=1");

		while ($objCalendar->next())
		{
			$this->generateFiles($objCalendar->row());
		}
	}


	/**
	 * Generate an XML files and save them to the root directory
	 * @param array
	 */
	private function generateFiles($arrArchive)
	{
		$time = time();
		$strType = ($arrArchive['format'] == 'atom') ? 'generateAtom' : 'generateRss';
		$strFile = strlen($arrArchive['alias']) ? $arrArchive['alias'] : 'calendar' . $arrArchive['id'];
		$strLink = strlen($arrArchive['feedBase']) ? $arrArchive['feedBase'] : $this->Environment->base;

		$objFeed = new Feed($strFile);

		$objFeed->link = $strLink;
		$objFeed->title = $arrArchive['title'];
		$objFeed->description = $arrArchive['description'];
		$objFeed->language = $arrArchive['language'];
		$objFeed->published = $arrArchive['tstamp'];

		// Get upcoming events
		$objArticleStmt = $this->Database->prepare("SELECT * FROM tl_calendar_events WHERE pid=? AND (startTime>=? OR (recurring=1 AND (recurrences=0 OR repeatEnd>=?))) AND published=? ORDER BY startTime");

		if ($arrArchive['maxItems'] > 0)
		{
			$objArticleStmt->limit($arrArchive['maxItems']);
		}

		$objArticle = $objArticleStmt->execute($arrArchive['id'], $time, $time, 1);

		// Get default URL
		$objParent = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
									->limit(1)
									->execute($arrArchive['jumpTo']);

		$strUrl = $strLink . $this->generateFrontendUrl($objParent->fetchAssoc(), '/events/%s');

		// Parse items
		while ($objArticle->next())
		{
			$this->addEvent($objArticle, $objArticle->startTime, $objArticle->endTime, $strUrl);

			// Recurring events
			if ($objArticle->recurring)
			{
				$count = 0;
				$arrRepeat = deserialize($objArticle->repeatEach);
				$blnSummer = date('I', $objArticle->startTime);

				// Do not include more than 20 recurrences
				while ($count++ < 20)
				{
					if ($objArticle->recurrences > 0 && $count >= $objArticle->recurrences)
					{
						break;
					}

					switch ($arrRepeat['unit'])
					{
						case 'days':
							$multiplier = 86400;
							break;

						case 'weeks':
							$multiplier = 604800;
							break;

						case 'months':
							$multiplier = (date('t', $objArticle->startTime)) * 86400;
							break;

						case 'years':
							if (date('n', $objArticle->startTime) < 3)
								$multiplier = date('L', $objArticle->startTime) ? 31622400 : 31536000;
							else
								$multiplier = ((date('Y', $objArticle->startTime) % 4) == 3) ? 31622400 : 31536000;
							break;

						default:
							$multiplier = 0;
							break(2);
					}

					$objArticle->startTime += ($multiplier * $arrRepeat['value']);
					$objArticle->endTime += ($multiplier * $arrRepeat['value']);

					// Daylight saving time
					if (($date = date('I', $objArticle->startTime)) !== $blnSummer)
					{
						$objArticle->startTime += $blnSummer ? 3600 : -3600;
						$objArticle->endTime += $blnSummer ? 3600 : -3600;

						$blnSummer = $date;
					}

					if ($objArticle->startTime >= $time)
					{
						$this->addEvent($objArticle, $objArticle->startTime, $objArticle->endTime, $strUrl);
					}
				}
			}
		}

		$count = 0;
		ksort($this->arrEvents);

		// Add feed items
		foreach ($this->arrEvents as $days)
		{
			foreach ($days as $events)
			{
				foreach ($events as $event)
				{
					if ($arrArchive['maxItems'] > 0 && $count++ >= $arrArchive['maxItems'])
					{
						break(3);
					}

					$objItem = new FeedItem();

					$objItem->title = $event['title'];
					$objItem->description = $event['description'];
					$objItem->content = $event['content'];
					$objItem->link = $event['link'];
					$objItem->published = $event['published'];
					$objItem->start = $event['start'];
					$objItem->end = $event['end'];

					$objFeed->addItem($objItem);
				}
			}
		}

		// Create file
		$objRss = new File($strFile . '.xml');
		$objRss->write($this->replaceInsertTags($objFeed->$strType()));
		$objRss->close();
	}


	/**
	 * Add events to the indexer
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

		$objCalendar = $this->Database->prepare("SELECT id, jumpTo FROM tl_calendar WHERE protected!=?")
									  ->execute(1);

		// Walk through each calendar
		while ($objCalendar->next())
		{
			if (is_array($arrRoot) && count($arrRoot) > 0 && !in_array($objCalendar->jumpTo, $arrRoot))
			{
				continue;
			}

			// Get default URL
			$objParent = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=? AND (start=? OR start<?) AND (stop=? OR stop>?) AND published=?")
										->limit(1)
										->execute($objCalendar->jumpTo, '', time(), '', time(), 1);

			if ($objParent->numRows < 1)
			{
				continue;
			}

			$strUrl = $this->generateFrontendUrl($objParent->fetchAssoc(), '/events/%s');

			// Get items
			$objArticle = $this->Database->prepare("SELECT * FROM tl_calendar_events WHERE pid=? AND published=? ORDER BY startTime DESC")
										 ->execute($objCalendar->id, 1);

			// Add items to the indexer
			while ($objArticle->next())
			{
				$arrPages[] = sprintf($strUrl, ((strlen($objArticle->alias) && !$GLOBALS['TL_CONFIG']['disableAlias']) ? $objArticle->alias : $objArticle->id));
			}
		}

		return $arrPages;
	}


	/**
	 * Add an event to the array of active events
	 * @param object
	 * @param integer
	 * @param integer
	 * @param string
	 */
	private function addEvent(Database_Result $objArticle, $intStart, $intEnd, $strUrl)
	{
		if ($intStart < time())
			return;

		$intKey = date('Ymd', $intStart);
		$span = floor(($intEnd - $intStart) / 86400);
		$format = $objArticle->addTime ? 'datimFormat' : 'dateFormat';

		// Set title
		if ($span > 0)
			$title = date($GLOBALS['TL_CONFIG'][$format], $intStart) . ' - ' . date($GLOBALS['TL_CONFIG'][$format], $intEnd);
		else
			$title = date($GLOBALS['TL_CONFIG']['dateFormat'], $intStart) . ($objArticle->addTime ? ' (' . date($GLOBALS['TL_CONFIG']['timeFormat'], $intStart) . ' - ' . date($GLOBALS['TL_CONFIG']['timeFormat'], $intEnd) . ')' : '');

		$arrEvent = array
		(
			'title' => $title,
			'description' => $objArticle->details,
			'content' => $objArticle->details,
			'link' => sprintf($strUrl, ((strlen($objArticle->alias) && !$GLOBALS['TL_CONFIG']['disableAlias']) ? $objArticle->alias : $objArticle->id)),
			'published' => $intStart
		);

		$this->arrEvents[$intKey][$intStart][] = $arrEvent;
	}
}

?>