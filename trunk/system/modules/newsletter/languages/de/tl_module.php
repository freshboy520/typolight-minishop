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
 * @package    Newsletter
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_module']['nl_channel']     = array('Newsletter', 'Bitte wählen Sie einen Newsletter.');
$GLOBALS['TL_LANG']['tl_module']['nl_template']    = array('Modul-Layout', 'Bitte wählen Sie ein Modul-Layout. Sie können eigene Modul-Layouts im Ordner <em>templates</em> anlegen. Newsletter-Templates beginnen mit <em>nl_</em> und müssen die Dateiendung <em>.tpl</em> haben.');
$GLOBALS['TL_LANG']['tl_module']['nl_subscribe']   = array('Bestell-Bestätigung', 'Bitte geben Sie den Text der Bestätigungsemail ein. Sie können die Platzhalter <em>##channel##</em> (Name des Newsletters), <em>##domain##</em> (aktuelle Domain) und <em>##link##</em> (Aktivierungslink) verwenden.');
$GLOBALS['TL_LANG']['tl_module']['nl_unsubscribe'] = array('Abbestell-Bestätigung', 'Bitte geben Sie den Text der Bestätigungsemail ein. Sie können die Platzhalter <em>##channel##</em> (Name des Newsletters) und <em>##domain##</em> (aktuelle Domain) verwenden.');


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_module']['text_subscribe']   = array('Ihr Abonnement auf %s', "Sie haben den Newsletter ##channel## auf ##domain## bestellt.\n\nBitte klicken Sie ##link## um Ihre Bestellung zu bestätigen. Bitte ignorieren Sie diese Email falls Sie die Bestellung nicht selbst getätigt haben.\n");
$GLOBALS['TL_LANG']['tl_module']['text_unsubscribe'] = array('Ihr Abonnement auf %s', "Sie haben den Newsletter ##channel## abbestellt.\n");

?>