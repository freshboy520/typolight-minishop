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
 * Fields
 */
$GLOBALS['TL_LANG']['tl_calendar_events']['title']       = array('Titel', 'Bitte geben Sie den Titel des Events ein.');
$GLOBALS['TL_LANG']['tl_calendar_events']['alias']       = array('Event-Alias', 'Der Event-Alias ist eine eindeutige Referenz, die anstelle der Event-ID verwendet werden kann.');
$GLOBALS['TL_LANG']['tl_calendar_events']['startDate']   = array('Startdatum', 'Bitte geben Sie das Startdatum des Events ein.');
$GLOBALS['TL_LANG']['tl_calendar_events']['endDate']     = array('Enddatum', 'Bitte geben Sie das Enddatum des Events ein. Bei eintägigen Events können Sie das Feld leer lassen.');
$GLOBALS['TL_LANG']['tl_calendar_events']['addTime']     = array('Zeit hinzufügen', 'Start- und Endzeit des Events hinzufügen.');
$GLOBALS['TL_LANG']['tl_calendar_events']['startTime']   = array('Startzeit', 'Bitte geben Sie die Startzeit des Events gemäß des globalen Zeitformats ein.');
$GLOBALS['TL_LANG']['tl_calendar_events']['endTime']     = array('Endzeit', 'Bitte geben Sie die Endzeit des Events gemäß des globalen Zeitformats ein.');
$GLOBALS['TL_LANG']['tl_calendar_events']['teaser']      = array('Teasertext', 'Der Teasertext wird anstatt der Eventdetails gefolgt von einem "weiterlesen..." Link angezeigt.');
$GLOBALS['TL_LANG']['tl_calendar_events']['details']     = array('Eventdetails', 'Bitte geben Sie die Details des Events ein.');
$GLOBALS['TL_LANG']['tl_calendar_events']['recurring']   = array('Wiederkehrender Termin', 'Den Termin in bestimmten Zeitabständen wiederholen.');
$GLOBALS['TL_LANG']['tl_calendar_events']['repeatEach']  = array('Intervall', 'Bitte legen Sie den Zeitabstand fest.');
$GLOBALS['TL_LANG']['tl_calendar_events']['recurrences'] = array('Wiederholungen', 'Bitte legen Sie die Anzahl der Wiederholungen fest (0 = unbegrenzt).');
$GLOBALS['TL_LANG']['tl_calendar_events']['published']   = array('Veröffentlicht', 'Der Event wird erst auf Ihrer Webseite sichtbar wenn er veröffentlicht ist.');


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_calendar_events']['days']   = 'Tag(e)';
$GLOBALS['TL_LANG']['tl_calendar_events']['weeks']  = 'Woche(n)';
$GLOBALS['TL_LANG']['tl_calendar_events']['months'] = 'Monat(e)';
$GLOBALS['TL_LANG']['tl_calendar_events']['years']  = 'Jahr(e)';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_calendar_events']['new']        = array('Neuer Event', 'Einen neuen Event erstellen');
$GLOBALS['TL_LANG']['tl_calendar_events']['edit']       = array('Event bearbeiten', 'Event ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_calendar_events']['copy']       = array('Event duplizieren', 'Event ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_calendar_events']['delete']     = array('Event löschen', 'Event ID %s löschen');
$GLOBALS['TL_LANG']['tl_calendar_events']['show']       = array('Eventdetails', 'Details des Events ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_calendar_events']['editheader'] = array('Kalender bearbeiten', 'Den Kalender bearbeiten');

?>