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
 * Class ModuleEventReader
 *
 * Front end module "event reader".
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class ModuleEventReader extends Events
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_event';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new Template('be_wildcard');
			$objTemplate->wildcard = '### EVENT READER ###';

			return $objTemplate->parse();
		}

		// Return if no event has been specified
		if (!$this->Input->get('events'))
		{
			return '';
		}

		$this->cal_calendar = $this->sortOutProtected(deserialize($this->cal_calendar, true));

		// Return if there are no calendars
		if (!is_array($this->cal_calendar) || count($this->cal_calendar) < 1)
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

		$this->Template->event = '';
		$this->Template->referer = $this->getReferer(ENCODE_AMPERSANDS);
		$this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];

		// Get current event
		$objEvent = $this->Database->prepare("SELECT * FROM tl_calendar_events WHERE pid IN(" . implode(',', $this->cal_calendar) . ") AND (id=? OR alias=?)" . (!BE_USER_LOGGED_IN ? " AND published=?" : ""))
								   ->limit(1)
								   ->execute($this->Input->get('events'), $this->Input->get('events'), 1);

		if ($objEvent->numRows < 1)
		{
			$this->Template->event = '<p class="error">Invalid event ID</p>';

			// Do not index the page
			$objPage->noSearch = 1;
			$objPage->cache = 0;

			// Send 404 header
			header('HTTP/1.0 404 Not Found');
			return;
		}

		// Overwrite page title
		if (strlen($objEvent->title))
		{
			$objPage->pageTitle = $objEvent->title;
		}

		$span = intval(($objEvent->endTime - $objEvent->startTime) / 86400);

		// Get date
		if ($span > 0)
			$date = date($GLOBALS['TL_CONFIG'][($objEvent->addTime ? 'datimFormat' : 'dateFormat')], $objEvent->startTime) . ' - ' . date($GLOBALS['TL_CONFIG'][($objEvent->addTime ? 'datimFormat' : 'dateFormat')], $objEvent->endTime);
		elseif ($objEvent->startTime == $objEvent->endTime)
			$date = date($GLOBALS['TL_CONFIG']['dateFormat'], $objEvent->startTime) . ($objEvent->addTime ? ' (' . date($GLOBALS['TL_CONFIG']['timeFormat'], $objEvent->startTime) . ')' : '');
		else
			$date = date($GLOBALS['TL_CONFIG']['dateFormat'], $objEvent->startTime) . ($objEvent->addTime ? ' (' . date($GLOBALS['TL_CONFIG']['timeFormat'], $objEvent->startTime) . ' - ' . date($GLOBALS['TL_CONFIG']['timeFormat'], $objEvent->endTime) . ')' : '');

		$until = '';
		$recurring = '';

		// Recurring event
		if ($objEvent->recurring)
		{
			$arrRange = deserialize($objEvent->repeatEach);
			$strKey = 'cal_' . $arrRange['unit'];
			$recurring = sprintf($GLOBALS['TL_LANG']['MSC'][$strKey], $arrRange['value']);

			if ($objEvent->recurrences > 0)
				$until = sprintf($GLOBALS['TL_LANG']['MSC']['cal_until'], date($GLOBALS['TL_CONFIG']['dateFormat'], $objEvent->repeatEnd));
		}

		$objTemplate = new FrontendTemplate($this->cal_template);

		$objTemplate->date = $date;
		$objTemplate->title = $objEvent->title;
		$objTemplate->details = $objEvent->details;
		$objTemplate->teaser = $objEvent->teaser;
		$objTemplate->recurring = $recurring;
		$objTemplate->until = $until;
		$objTemplate->start = $objEvent->startTime;
		$objTemplate->end = $objEvent->endTime;

		$this->Template->event = $objTemplate->parse();
	}
}

?>