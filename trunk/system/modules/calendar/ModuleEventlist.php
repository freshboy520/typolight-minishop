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
 * Class ModuleEventlist
 *
 * Front end module "event list".
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class ModuleEventlist extends Events
{

	/**
	 * Current date object
	 * @var integer
	 */
	protected $Date;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_eventlist';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new Template('be_wildcard');
			$objTemplate->wildcard = '### EVENT LIST ###';

			return $objTemplate->parse();
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
		$this->Date = new Date(($this->Input->get('day') ? $this->Input->get('day') : date('Ymd')), 'Ymd');

		// Set current format
		switch ($this->cal_format)
		{
			case 'cal_day':
				$strBegin = $this->Date->dayBegin;
				$strEnd = $this->Date->dayEnd;
				$strEmpty = $GLOBALS['TL_LANG']['MSC']['cal_emptyDay'];
				break;

			case 'cal_week':
				$strBegin = $this->Date->dayBegin - ((date('w', $this->Date->dayBegin) - $this->cal_startDay) * 86400);
				$strEnd = $strBegin + 604799;
				$strEmpty = $GLOBALS['TL_LANG']['MSC']['cal_emptyWeek'];
				break;

			case 'cal_year':
				$strBegin = $this->Date->yearBegin;
				$strEnd = $this->Date->yearEnd;
				$strEmpty = $GLOBALS['TL_LANG']['MSC']['cal_emptyYear'];
				break;

			case 'cal_two':
				$strBegin = $this->Date->yearBegin;
				$strEnd = $this->Date->yearEnd + 31536000;
				$strEmpty = $GLOBALS['TL_LANG']['MSC']['cal_emptyYear'];
				break;

			default:
				$strBegin = $this->Date->monthBegin;
				$strEnd = $this->Date->monthEnd;
				$strEmpty = $GLOBALS['TL_LANG']['MSC']['cal_emptyMonth'];
				break;
		}

		$strEvents = '';
		$arrAllEvents = $this->getAllEvents($this->cal_calendar, $strBegin, $strEnd);
		$blnHeader = null;
		$count = 0;

		// Sort array
		ksort($arrAllEvents);

		foreach ($arrAllEvents as $days)
		{
			$strHclass = is_null($blnHeader) ? ' first' : '';
			$blnHeader = true;

			foreach ($days as $day=>$events)
			{
				if ($day < $strBegin || $day > $strEnd)
				{
					$blnHeader = null;
					continue;
				}

				$strDay = $GLOBALS['TL_LANG']['DAYS'][date('w', $day)];

				foreach ($events as $event)
				{
					$objTemplate = new FrontendTemplate($this->cal_template);

					if ($blnHeader)
					{
						$count = 0;
						$objTemplate->header = true;
						$objTemplate->hclass = $strHclass;

						$blnHeader = false;
					}

					$objTemplate->day = $strDay;
					$objTemplate->firstDay = $strDay;
					$objTemplate->title = $event['title'];
					$objTemplate->time = $event['time'];
					$objTemplate->href = $event['href'];
					$objTemplate->teaser = $event['teaser'];
					$objTemplate->details = $event['details'];
					$objTemplate->class = ((($count++ % 2) == 0) ? ' even' : ' odd') . ' cal_' . $event['parent'];
					$objTemplate->date = date($GLOBALS['TL_CONFIG']['dateFormat'], $day);
					$objTemplate->more = $GLOBALS['TL_LANG']['MSC']['more'];
					$objTemplate->firstDate = $objTemplate->date;
					$objTemplate->start = $event['start'];
					$objTemplate->end = $event['end'];
					$objTemplate->span = '';

					// Short view
					if ($this->cal_noSpan)
					{
						$objTemplate->day = $event['day'];
						$objTemplate->date = $event['date'];

						if (!strlen($event['time']) && !strlen($event['day']))
						{
							$objTemplate->span = $event['date'];
						}
					}

					$strEvents .= $objTemplate->parse();
				}
			}
		}

		// No events found
		if (!strlen($strEvents))
		{
			$strEvents = "\n" . '<div class="empty">' . $strEmpty . '</div>' . "\n";
		}

		$this->Template->events = $strEvents;
		$this->Template->searchable = $this->searchable;
	}
}

?>