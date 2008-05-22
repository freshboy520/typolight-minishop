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
 * Fields
 */
$GLOBALS['TL_LANG']['tl_news']['headline']     = array('Überschrift', 'Bitte geben Sie die Überschrift des Beitrags ein.');
$GLOBALS['TL_LANG']['tl_news']['alias']        = array('Nachrichtenalias', 'Der Alias eines Beitrags ist eine eindeutige Referenz, die anstelle der Nachrichten ID aufgerufen werden kann.');
$GLOBALS['TL_LANG']['tl_news']['date']         = array('Datum', 'Bitte geben Sie das Datum des Nachrichtenbeitrags ein.');
$GLOBALS['TL_LANG']['tl_news']['time']         = array('Uhrzeit', 'Bitte geben Sie die Uhrzeit des Nachrichtenbeitrags ein.');
$GLOBALS['TL_LANG']['tl_news']['subheadline']  = array('Unterüberschrift', 'Hier können Sie eine Unterüberschrift eingeben.');
$GLOBALS['TL_LANG']['tl_news']['teaser']       = array('Teasertext', 'Der Teasertext wird anstatt des Nachrichtentextes gefolgt von einem "weiterlesen..." Link angezeigt.');
$GLOBALS['TL_LANG']['tl_news']['text']         = array('Nachrichtentext', 'Bitte geben Sie den Nachrichtentext ein.');
$GLOBALS['TL_LANG']['tl_news']['addImage']     = array('Ein Bild einfügen', 'Wenn Sie diese Option wählen, wird dem Beitrag ein Bild hinzugefügt.');
$GLOBALS['TL_LANG']['tl_news']['author']       = array('Autor', 'Hier können Sie den Autor des Beitrags ändern.');
$GLOBALS['TL_LANG']['tl_news']['published']    = array('Veröffentlicht', 'Solange Sie diese Option nicht wählen, ist der Nachrichtenbeitrag auf Ihrer Webseite nicht sichtbar.');
$GLOBALS['TL_LANG']['tl_news']['noComments']   = array('Kommentare deaktivieren', 'Die Kommentarfunktion für diesen Nachrichtenbeitrag deaktivieren.');
$GLOBALS['TL_LANG']['tl_news']['addEnclosure'] = array('Enclosure hinzufügen', 'Eine Datei zu der Nachricht hinzufügen.');
$GLOBALS['TL_LANG']['tl_news']['enclosure']    = array('RSS-Enclosure', 'Bitte wählen Sie die Datei, die Sie dem RSS-Feed hinzufügen möchten.');
$GLOBALS['TL_LANG']['tl_news']['source']       = array('Quellseite', 'Bitte wählen Sie, ob der Beitrag auf eine interne oder eine externe Seite verweist.');
$GLOBALS['TL_LANG']['tl_news']['jumpTo']       = array('Weiterleitung zu Seite', 'Bitte wählen Sie die Seite, zu der ein Besucher weitergeleitet werden soll. Beachten Sie, dass die Zielseite das Modul "Nachrichtenleser" enthalten sollte, damit der Nachrichtenbeitrag angezeigt werden kann.');


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_news']['default']  = array('Standardseite', 'Bei Anklicken des "weiterlesen..." Links wird ein Besucher auf die Standardseite des Nachrichtenarchivs weitergeleitet.');
$GLOBALS['TL_LANG']['tl_news']['internal'] = array('Interne Seite', 'Bei Anklicken des "weiterlesen..." Links wird ein Besucher auf eine interne Seite weitergeleitet, auf der er den ganzen Beitrag lesen kann.');
$GLOBALS['TL_LANG']['tl_news']['external'] = array('Externe Webseite', 'Bei Anklicken des "weiterlesen..." Links wird ein Besucher auf eine externe Webseite weitergeleitet, auf der er den ganzen Beitrag lesen kann.');


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_news']['new']        = array('Neuer Beitrag', 'Einen neuen Nachrichtenbeitrag erstellen');
$GLOBALS['TL_LANG']['tl_news']['edit']       = array('Beitrag bearbeiten', 'Beitrag ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_news']['copy']       = array('Beitrag duplizieren', 'Beitrag ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_news']['delete']     = array('Beitrag löschen', 'Beitrag ID %s löschen');
$GLOBALS['TL_LANG']['tl_news']['show']       = array('Beitragsdetails', 'Die Details des Beitrags ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_news']['editheader'] = array('Archivkopf bearbeiten', 'Den Kopf dieses Nachrichtenarchives bearbeiten');

?>