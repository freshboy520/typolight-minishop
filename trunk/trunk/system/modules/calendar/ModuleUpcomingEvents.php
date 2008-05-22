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
 * Class ModuleUpcomingEvents
 *
 * Front end module "upcoming events".
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class ModuleUpcomingEvents extends Events
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
			$objTemplate->wildcard = '### UPCOMING EVENTS ###';

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
		$this->Date = new Date(date('Ymd'), 'Ymd');

		$strEvents = '';
		$strBegin = $this->Date->dayBegin;
		$strEnd = ($this->Date->dayEnd + 31536000);

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
					++$count;

					if ($this->cal_limit && $count > $this->cal_limit)
					{
						break(3);
					}

					$objTemplate = new FrontendTemplate($this->cal_template);

					if ($blnHeader)
					{
						$objTemplate->header = true;
						$objTemplate->hclass = $strHclass;

						$blnHeader = false;
					}

					$objTemplate->day = $strDay;
					$objTemplate->title = $event['title'];
					$objTemplate->date = date($GLOBALS['TL_CONFIG']['dateFormat'], $day);
					$objTemplate->time = $event['time'];
					$objTemplate->href = $event['href'];
					$objTemplate->teaser = $event['teaser'];
					$objTemplate->details = $event['details'];
					$objTemplate->class = ((($count % 2) == 0) ? ' odd' : ' even') . ' cal_' . $event['parent'];
					$objTemplate->more = $GLOBALS['TL_LANG']['MSC']['more'];
					$objTemplate->start = $event['start'];
					$objTemplate->end = $event['end'];

					// Short view
					if ($this->cal_noSpan)
					{
						$objTemplate->day = $event['day'];
						$objTemplate->date = $event['date'];
					}

					$strEvents .= $objTemplate->parse();
				}
			}
		}

		// No events found
		if (!strlen($strEvents))
		{
			$strEvents = "\n" . '<div class="empty">' . $GLOBALS['TL_LANG']['MSC']['cal_empty'] . '</div>' . "\n";
		}

		$this->Template->events = $strEvents;
	}
}

?>