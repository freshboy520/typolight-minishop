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
 * @package    Language
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_settings']['websiteTitle']        = array('Titel der Webseite', 'Bitte geben Sie den Title der Webseite ein.');
$GLOBALS['TL_LANG']['tl_settings']['adminEmail']          = array('Emailadresse des Systemadministrators', 'Bitte geben Sie die Emailadresse des Systemadministrators ein.');
$GLOBALS['TL_LANG']['tl_settings']['websitePath']         = array('Relativer Pfad zum TYPOlight-Verzeichnis', 'Bitte geben Sie den relativen Pfad zu dem Verzeichnis, das die TYPOlight-Dateien enthält, ein (wenn Sie das TYPOlight Backend z.B. über <em>www.ihredomain.com/ihrewebseite/typolight</em> aufrufen, wäre der relative Pfad <em>/ihrewebseite</em>).');
$GLOBALS['TL_LANG']['tl_settings']['dateFormat']          = array('Datumsformat', 'Bitte geben Sie ein Datumsformat wie in der PHP Funktion date() ein.');
$GLOBALS['TL_LANG']['tl_settings']['timeFormat']          = array('Zeitformat', 'Bitte geben Sie ein Zeitformat wie in der PHP Funktion date() ein.');
$GLOBALS['TL_LANG']['tl_settings']['datimFormat']         = array('Datums- und Zeitformat', 'Bitte geben Sie ein Datums- und Zeitformat wie in der PHP Funktion date() ein.');
$GLOBALS['TL_LANG']['tl_settings']['timeZone']            = array('Zeitzone', 'Bitte wählen Sie die Zeitzone der Webseite aus.');
$GLOBALS['TL_LANG']['tl_settings']['characterSet']        = array('Zeichensatz', 'Bitte geben Sie einen Zeichensatz ein. Verwenden Sie nach Möglichkeit UTF-8, damit Sonderzeichen richtig dargestellt werden. Ändern Sie den Zeichensatz nur, wenn es Probleme bei der Darstellung gibt.');
$GLOBALS['TL_LANG']['tl_settings']['encryptionKey']       = array('Hashwert für Verschlüsselung', 'Bitte beachten Sie, dass verschlüsselte Daten nur mit diesem Wert wieder entschlüsselt werden können! Ändern Sie ihn daher nicht, wenn bereits verschlüsselte Daten vorhanden sind!');
$GLOBALS['TL_LANG']['tl_settings']['uploadPath']          = array('Files-Verzeichnis', 'Bitte geben Sie den relativen Pfad zum Files-Verzeichnis ein (Standard: tl_files).');
$GLOBALS['TL_LANG']['tl_settings']['maxFileSize']         = array('Maximale Upload-Dateigröße', 'Bitte geben Sie die maximale Dateigröße für Datei-Uploads in Bytes ein (Standard: 2 MB = 2048 kB = 2048000 Bytes).');
$GLOBALS['TL_LANG']['tl_settings']['imageWidth']          = array('Maximale Bildbreite', 'Bitte geben Sie die maximale Bildbreite für Datei-Uploads in Pixeln ein.');
$GLOBALS['TL_LANG']['tl_settings']['imageHeight']         = array('Maximale Bildhöhe', 'Bitte geben Sie die maximale Bildhöhe für Datei-Uploads in Pixeln ein.');
$GLOBALS['TL_LANG']['tl_settings']['jpgQuality']          = array('Qualität der Vorschaubilder', 'Bitte geben Sie die Qualität der Vorschaubilder in Prozent ein (funktioniert nur mit JPGs).');
$GLOBALS['TL_LANG']['tl_settings']['uploadFields']        = array('Simultane Datei-Uploads', 'Bitte geben Sie die maximale Anzahl simultaner Datei-Uploads an.');
$GLOBALS['TL_LANG']['tl_settings']['undoPeriod']          = array('Speicherzeit für Undo-Schritte', 'Bitte geben Sie die Speicherzeit für Undo-Schritte in Sekunden ein (Standard: 24 Stunden = 86400 Sekunden).');
$GLOBALS['TL_LANG']['tl_settings']['versionPeriod']       = array('Speicherzeit für Versionen', 'Bitte geben Sie die Speicherzeit für verschiedene Versionen eines Datensatzes in Sekunden ein (Standard: 90 Tage = 7776000 Sekunden).');
$GLOBALS['TL_LANG']['tl_settings']['logPeriod']           = array('Speicherzeit für Log-Einträge', 'Bitte geben Sie die Speicherzeit für Log-Einträge in Sekunden ein (Standard: 14 Tage = 1209600 Sekunden).');
$GLOBALS['TL_LANG']['tl_settings']['sessionTimeout']      = array('Verfallszeit einer Session', 'Geben Sie die Verfallszeit einer Session in Sekunden ein (Standard: 60 Minuten = 3600 Sekunden). Erhält das System länger als diese Verfallszeit keine Eingaben, verfällt die Session und der Benutzer muss sich erneut anmelden.');
$GLOBALS['TL_LANG']['tl_settings']['lockPeriod']          = array('Wartezeit bei gesperrtem Konto', 'Bitte geben Sie hier die Zeit in Sekunden ein, die ein Benutzer eines gesperrten Kontos bis zur erneuten Anmeldung warten muss (Standard: 5 Minuten = 300 Sekunden). Dieses Feature soll Brute-Force-Attacken ein wenig erschweren.');
$GLOBALS['TL_LANG']['tl_settings']['useSMTP']             = array('SMTP für den Mailversand verwenden', 'Standardmäßig verwendet TYPOlight die PHP-Funktion <em>mail()</em> um Emails zu versenden. Hier können Sie anstatt dessen einen SMTP-Server auswählen.');
$GLOBALS['TL_LANG']['tl_settings']['smtpHost']            = array('SMTP-Hostname', 'Bitte geben Sie den Hostnamen des SMTP-Servers ein (Standard ist localhost).');
$GLOBALS['TL_LANG']['tl_settings']['smtpPort']            = array('SMTP-Portnummer', 'Bitte geben Sie die Portnummer des SMTP-Servers ein (Standard ist 25 bzw. 465 für SSL).');
$GLOBALS['TL_LANG']['tl_settings']['smtpUser']            = array('SMTP-Benutzername', 'Falls der SMTP-Server Authentifizierung erfordert, geben Sie bitte hier einen Benutzernamen ein.');
$GLOBALS['TL_LANG']['tl_settings']['smtpPass']            = array('SMTP-Passwort', 'Falls der SMTP-Server Authentifizierung erfordert, geben Sie bitte hier einen Passwort ein.');
$GLOBALS['TL_LANG']['tl_settings']['useFTP']              = array('FTP für Dateizugriff verwenden', 'Wenn PHP-Skripte auf Ihrem Server aufgrund von Safemode oder Zugriffsbeschränkungen keine Dateien modifizieren dürfen, können Sie hier den Zugriff über FTP aktivieren.');
$GLOBALS['TL_LANG']['tl_settings']['ftpHost']             = array('FTP-Host', 'Bitte geben Sie den Hostnamen des FTP-Servers ein (z.B. <em>domain.com</em> oder <em>domain.com:21</em>).');
$GLOBALS['TL_LANG']['tl_settings']['ftpPath']             = array('FTP-Pfad', 'Bitte geben Sie den Pfad zu Ihrer TYPOlight-Installation ausgehend vom FTP-Stammverzeichnis ein (z.B. <em>html/typolight/</em>).');
$GLOBALS['TL_LANG']['tl_settings']['ftpUser']             = array('FTP-Benutzer', 'Bitte geben Sie den Benutzernamen für den FTP-Server ein.');
$GLOBALS['TL_LANG']['tl_settings']['ftpPass']             = array('FTP-Passwort', 'Bitte geben Sie das Passwort für den FTP-Server ein.');
$GLOBALS['TL_LANG']['tl_settings']['customSections']      = array('Eigene Layoutbereiche', 'Hier können Sie eine durch Kommata getrennte Liste eigener Layoutbereiche eingeben. Diese Bereiche stehen Ihnen dann im Modul <em>Seitenlayout</em> zusätzlich zu den Bereichen <em>header</em>, <em>left</em>, <em>main</em>, <em>right</em> und <em>footer</em> zur Verfügung.');
$GLOBALS['TL_LANG']['tl_settings']['maxImageWidth']       = array('Maximale Frontend-Bildbreite', 'Hier können Sie die maximale Breite von Bildern und Mediendateien festlegen. Inhaltselemente deren Breite den hier festgelegten Wert überschreiten, werden automatisch verkleinert.');
$GLOBALS['TL_LANG']['tl_settings']['validImageTypes']     = array('Unterstützte Bildformate', 'Bitte geben sie eine durch Kommata getrennte Liste von Dateiendungen unterstützter Bildformate ein. Nehmen Sie nur solche Formate auf, die von TYPOlight oder Ihrem eigenen Script verarbeitet werden können.');
$GLOBALS['TL_LANG']['tl_settings']['editableFiles']       = array('Editierbare Dateien', 'Bitte geben Sie eine durch Kommata getrennte Liste von Dateiendungen editierbarer Dateien ein, die mit dem Quelltexteditor bearbeitet werden können.');
$GLOBALS['TL_LANG']['tl_settings']['allowedDownload']     = array('Erlaubte Dateiendungen für Downloads', 'Bitte geben Sie eine durch Kommata getrennte Liste von Dateiendungen downloadbarer Dateien ein. Bitte beachten Sie, dass der Dateidownload auf das Files-Verzeichnis beschränkt ist.');
$GLOBALS['TL_LANG']['tl_settings']['uploadTypes']         = array('Erlaubte Dateiendungen für Uploads', 'Bitte geben Sie eine durch Kommata getrennte Liste von Dateiendungen uploadbarer Dateien ein. Bitte beachten Sie, dass der Dateiupload auf das Files-Verzeichnis beschränkt ist.');
$GLOBALS['TL_LANG']['tl_settings']['allowedTags']         = array('Erlaubte HTML-Tags', 'Bitte geben Sie eine Liste erlaubter HTML-Tags ein. Alle anderen Tags werden aus Benutzereingaben entfernt.');
$GLOBALS['TL_LANG']['tl_settings']['displayErrors']       = array('Fehler anzeigen', 'Wenn Sie diese Option wählen, werden Fehlermeldungen auf dem Bildschirm ausgegeben. Diese Option ist daher nur für Arbeiten am System empfohlen!');
$GLOBALS['TL_LANG']['tl_settings']['rewriteURL']          = array('URLs umschreiben', 'Mit dieser Option können Sie TYPOlight dazu veranlassen, statische URLs ohne "index.php" zu erzeugen (z.B. <em>alias.html</em> anstatt <em>index.php/alias.html</em>). Dieses Feature benötigt das Apache-Modul <em>mod_rewrite</em>!');
$GLOBALS['TL_LANG']['tl_settings']['extractTplVars']      = array('Template-Variablen extrahieren', 'Wählen Sie diese Option wenn Sie normale Template-Variablen (z.B. <em>$language</em> anstatt <em>$this->language</em>) benutzen möchten. Wenn Sie keine eigenen Vorlagen verwenden, sollten Sie diese Option aus Gründen der Performance deaktivieren.');
$GLOBALS['TL_LANG']['tl_settings']['disableRefererCheck'] = array('Referer-Prüfung deaktivieren', 'Wählen Sie diese Option, um die Referer-Prüfung beim Absenden eines Formulares zu deaktivieren. Beachten Sie, dass das Deaktivieren der Referer-Prüfung ein großes Sicherheitsrisiko darstellt!');
$GLOBALS['TL_LANG']['tl_settings']['disableAlias']        = array('Keine Seitenaliase verwenden', 'Wählen Sie diese Option um die numerische ID einer Seiten anstelle des gespeicherten Seitenalias zu verwenden (z.B. <em>index.php?id=12</em> anstatt <em>startseite.html</em>).');
$GLOBALS['TL_LANG']['tl_settings']['enableGZip']          = array('GZip-Kompression aktivieren', 'Wenn Sie diese Option wählen, werden Frontend und Backend Seiten komprimiert an den Browser geschickt.');
$GLOBALS['TL_LANG']['tl_settings']['useDompdf']           = array('DOMPDF für die PDF-Erstellung verwenden', 'DOMPDF unterstützt keine Unicode-Zeichen und braucht länger für die PDF-Erstellung als TCPDF. TCPDF unterstützt Unicode-Zeichen aber liest keine Stylesheets ein, so dass die Ausgabe nicht so einfach formatiert werden kann.');
$GLOBALS['TL_LANG']['tl_settings']['debugMode']           = array('Debugmodus', 'Im Debugmodus werden bestimmte Laufzeitinformationen (z.B. Datenbankabfragen) auf dem Bildschirm ausgegeben. Diese Option ist nur für Wartungsarbeiten empfohlen und sollte während des normalen Betriebs deaktiviert werden!');
$GLOBALS['TL_LANG']['tl_settings']['resultsPerPage']      = array('Datensätze pro Seite', 'Bitte geben Sie an, wie viele Datensätze standardmäßig im Backend angezeigt werden sollen.');
$GLOBALS['TL_LANG']['tl_settings']['dynamicStopword']     = array('Dynamische Stopwörter (Suchmaschine)', 'Stopwörter sind Wörter, die nicht für die Suche indiziert werden. Dynamische Stopwörter sind Wörter, die sehr oft vorkommen und daher ignoriert werden. Sie können den Schwellenwert für dynamische Stopwörter hier verändern.');
$GLOBALS['TL_LANG']['tl_settings']['minWordLength']       = array('Minimale Wortlänge (Suchmaschine)', 'Bitte geben Sie die minimale Wortlänge ein. Wörter mit weniger Buchstaben werden nicht für die Suche indiziert. Bitte beachten Sie, dass eine kleinere Zahl die Ladezeit der Webseite und die Größe der Datenbank erhöht.');
$GLOBALS['TL_LANG']['tl_settings']['backendTheme']        = array('Backendmotiv', 'Wählen Sie das Backendmotiv, das für Ihre TYPOlight-Installation angezeigt werden soll.');
$GLOBALS['TL_LANG']['tl_settings']['inactiveModules']     = array('Inaktive Backendmodule', 'Hier können Sie nicht benötigte Backendmodule deaktivieren.');
$GLOBALS['TL_LANG']['tl_settings']['defaultUser']         = array('Standardbenutzer', 'Falls eine Seite keinen Besitzer hat, wird der hier angegebene Standardbenutzer verwendet. Ist weder ein Besitzer noch ein Standardbenutzer definiert, gelten keinerlei Zugriffsbeschränkung für eine Seite!');
$GLOBALS['TL_LANG']['tl_settings']['defaultGroup']        = array('Standardgruppe', 'Falls keine Gruppe als Besitzer hat, wird die hier angegebene Standardgruppe verwendet. Ist weder eine besitzende Gruppe noch eine Standardgruppe definiert, gelten keinerlei Zugriffsbeschränkung für eine Seite!');
$GLOBALS['TL_LANG']['tl_settings']['defaultChmod']        = array('Standardzugriffsrechte', 'Standardmäßig verwendet eine Seite die gleichen Zugriffsrechte wie ihre übergeordnete Seite. Sind keine Zugriffsrechte definiert, werden diese Standardzugriffsrechte verwendet.');


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_settings']['edit'] = 'Lokale Konfiguration bearbeiten';

?>