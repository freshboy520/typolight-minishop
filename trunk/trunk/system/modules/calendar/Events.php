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
 * Class Events
 *
 * Provide methods to get all events of a certain period from the database.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
abstract class Events extends Module
{

	/**
	 * Current URL
	 * @var string
	 */
	protected $strUrl;

	/**
	 * Current events
	 * @var array
	 */
	protected $arrEvents = array();


	/**
	 * Sort out protected archives
	 * @param array
	 * @return array
	 */
	protected function sortOutProtected($arrCalendars)
	{
		if (BE_USER_LOGGED_IN)
		{
			return $arrCalendars;
		}

		$this->import('FrontendUser', 'User');
		$objCalendar = $this->Database->execute("SELECT id, protected, groups FROM tl_calendar WHERE id IN(" . implode(',', $arrCalendars) . ")");
		$arrCalendars = array();

		while ($objCalendar->next())
		{
			if ($objCalendar->protected)
			{
				$groups = deserialize($objCalendar->groups, true);

				if (!is_array($this->User->groups) || count($this->User->groups) < 1 || !is_array($groups) || count($groups) < 1)
				{
					continue;
				}

				if (count(array_intersect($groups, $this->User->groups)) < 1)
				{
					continue;
				}
			}

			$arrCalendars[] = $objCalendar->id;
		}

		return $arrCalendars;
	}


	/**
	 * Get all events of a certain period
	 * @param array
	 * @param integer
	 * @param integer
	 * @return array
	 */
	protected function getAllEvents($arrCalendars, $intStart, $intEnd)
	{
		if (!is_array($arrCalendars))
		{
			return array();
		}

		foreach ($arrCalendars as $id)
		{
			$strUrl = $this->strUrl;

			// Get current "jumpTo" page
			$objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=(SELECT jumpTo FROM tl_calendar WHERE id=?)")
									  ->limit(1)
									  ->execute($id);

			if ($objPage->numRows)
			{
				$strUrl = $this->generateFrontendUrl($objPage->row(), '/events/%s');
			}

			// Get events of the current period
			$objEvents = $this->Database->prepare("SELECT * FROM tl_calendar_events WHERE pid=? AND ((startTime>=? AND startTime<=?) OR (endTime>=? AND endTime<=?) OR (startTime<=? AND endTime>=?) OR (recurring=1 AND (recurrences=0 OR repeatEnd>=?)))" . (!BE_USER_LOGGED_IN ? " AND published=?" : "") . " ORDER BY startTime")
										->execute($id, $intStart, $intEnd, $intStart, $intEnd, $intStart, $intEnd, $intStart, 1);

			if ($objEvents->numRows < 1)
			{
				continue;
			}

			while ($objEvents->next())
			{
				$this->addEvent($objEvents, $objEvents->startTime, $objEvents->endTime, $strUrl, $intEnd, $id);

				// Recurring events
				if ($objEvents->recurring)
				{
					$count = 0;
					$arrRepeat = deserialize($objEvents->repeatEach);
					$blnSummer = date('I', $objEvents->startTime);

					while ($objEvents->endTime < $intEnd)
					{
						if ($objEvents->recurrences > 0 && $count++ >= $objEvents->recurrences)
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
								$multiplier = (date('t', $objEvents->startTime)) * 86400;
								break;

							case 'years':
								if (date('n', $objEvents->startTime) < 3)
									$multiplier = date('L', $objEvents->startTime) ? 31622400 : 31536000;
								else
									$multiplier = ((date('Y', $objEvents->startTime) % 4) == 3) ? 31622400 : 31536000;
								break;

							default:
								$multiplier = 0;
								break(2);
						}

						$objEvents->startTime += ($multiplier * $arrRepeat['value']);
						$objEvents->endTime += ($multiplier * $arrRepeat['value']);

						// Daylight saving time
						if (($date = date('I', $objEvents->startTime)) !== $blnSummer)
						{
							$objEvents->startTime += $blnSummer ? 3600 : -3600;
							$objEvents->endTime += $blnSummer ? 3600 : -3600;

							$blnSummer = $date;
						}

						if ($objEvents->startTime >= $intStart || $objEvents->endTime <= $intEnd)
						{
							$this->addEvent($objEvents, $objEvents->startTime, $objEvents->endTime, $strUrl, $intEnd, $id);
						}
					}
				}
			}
		}

		// Sort data
		foreach (array_keys($this->arrEvents) as $key)
		{
			ksort($this->arrEvents[$key]);
		}

		return $this->arrEvents;
	}


	/**
	 * Add an event to the array of active events
	 * @param object
	 * @param integer
	 * @param integer
	 * @param string
	 * @param integer
	 * @param string
	 * @param integer
	 */
	private function addEvent(Database_Result $objEvents, $intStart, $intEnd, $strUrl, $intLimit, $intCalendar)
	{
		$intDate = $intStart;
		$intKey = date('Ymd', $intStart);
		$blnSummer = date('I', $intStart);
		$ds = 0;

		if (date('I', $intEnd) !== $blnSummer)
		{
			$ds = $blnSummer ? -3600 : 3600;
		}

		$span = floor(($intEnd - $intStart + $ds) / 86400);
		$strDate = date($GLOBALS['TL_CONFIG']['dateFormat'], $intStart);
		$strDay = $GLOBALS['TL_LANG']['DAYS'][date('w', $intStart)];

		if ($span > 0)
		{
			$strDate = date($GLOBALS['TL_CONFIG']['dateFormat'], $intStart) . ' - ' . date($GLOBALS['TL_CONFIG']['dateFormat'], $intEnd);
			$strDay = '';
		}

		$strTime = '';

		if ($objEvents->addTime)
		{
			if ($span > 0)
				$strDate = date($GLOBALS['TL_CONFIG']['datimFormat'], $intStart) . ' - ' . date($GLOBALS['TL_CONFIG']['datimFormat'], $intEnd);
			elseif ($intStart == $intEnd)
				$strTime = date($GLOBALS['TL_CONFIG']['timeFormat'], $intStart);
			else
				$strTime = date($GLOBALS['TL_CONFIG']['timeFormat'], $intStart) . ' - ' . date($GLOBALS['TL_CONFIG']['timeFormat'], $intEnd);
		}

		$arrEvent = array
		(
			'time' => $strTime,
			'date' => $strDate,
			'day' => $strDay,
			'parent' => $intCalendar,
			'link' => $objEvents->title,
			'title' => specialchars($objEvents->title),
			'href' => sprintf($strUrl, ((strlen($objEvents->alias) && !$GLOBALS['TL_CONFIG']['disableAlias']) ? $objEvents->alias : $objEvents->id)),
			'details' => $objEvents->details,
			'teaser' => $objEvents->teaser,
			'start' => $intStart,
			'end' => $intEnd
		);

		$this->arrEvents[$intKey][$intStart][] = $arrEvent;

		// Multi-day event
		if ($this->cal_noSpan)
		{
			return;
		}

		for ($i=1; $i<=$span && $intDate <= $intLimit; $i++)
		{
			$intDate = ($intStart + ($i * 86400));
			$intNextKey = date('Ymd', $intDate);

			$this->arrEvents[$intNextKey][$intDate][] = $arrEvent;
		}
	}
}

?>