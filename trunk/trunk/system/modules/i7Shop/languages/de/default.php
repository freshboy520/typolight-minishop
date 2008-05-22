<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * include7 MiniShop – extension for TYPOLight from Leo Feyer
 * Copyright (C) 2008 Jonas Schnelli / include7 AG
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
 * @copyright  Jonas Schnelli / include7 AG 2008
 * @author     Jonas Schnelli / include7 AG <jonas.schnelli@incude7.ch>
 * @package    i7Shop
 * @license    LGPL
 * @filesource
 */



/* -todo- 
			-  */


// Order 

$GLOBALS['TL_LANG']['i7SHOP']['text_loginusername']	= 'E-Mail Adresse';
$GLOBALS['TL_LANG']['i7SHOP']['text_loginpassword']	= 'Passwort';

$GLOBALS['TL_LANG']['i7SHOP']['text_registerusername']	= 'Ihre E-Mail Adresse (username)';
$GLOBALS['TL_LANG']['i7SHOP']['text_registerpassword']	= 'Passwort';

$GLOBALS['TL_LANG']['i7SHOP']['text_loginconfirm'] = 'Bestätigung';
$GLOBALS['TL_LANG']['i7SHOP']['remove_article']	= 'Artikel löschen';
$GLOBALS['TL_LANG']['i7SHOP']['text_company']		= 'Firma';
$GLOBALS['TL_LANG']['i7SHOP']['text_firstname']	= 'Vorname';
$GLOBALS['TL_LANG']['i7SHOP']['text_lastname']	= 'Nachname';
$GLOBALS['TL_LANG']['i7SHOP']['text_address1']	= 'Strasse';
$GLOBALS['TL_LANG']['i7SHOP']['text_address2']	= 'Postfach';
$GLOBALS['TL_LANG']['i7SHOP']['text_address3']	= 'Adresszusatz';
$GLOBALS['TL_LANG']['i7SHOP']['text_zipcode']		= 'PLZ';
$GLOBALS['TL_LANG']['i7SHOP']['text_location']	= 'Stadt';
$GLOBALS['TL_LANG']['i7SHOP']['text_country']		= 'Land';
$GLOBALS['TL_LANG']['i7SHOP']['text_billingaddress']		= 'Rechnungsadresse';

$GLOBALS['TL_LANG']['i7SHOP']['text_email']		= 'E-Mail';




$GLOBALS['TL_LANG']['i7SHOP']['i7shop_hide_on_page'] = array("Warenkorb-Info auf folgenden Seiten ausblenden", "Sie können hier defiieren, auf welchen Seiten dieses Module nicht angezeigt wird");
$GLOBALS['TL_LANG']['i7SHOP']['i7shop_basket_info_always_visible'] = array("Immer sichtbar", "Wenn eingeschaltet wird die Warenkorb-Info immer angezeigt, auch bei leerem Warenkorb");


$GLOBALS['TL_LANG']['i7SHOP']['cctype']		= 'Kreditkarten-Type';
$GLOBALS['TL_LANG']['i7SHOP']['ccnumber']		= 'Kreditkarten Nummer';
$GLOBALS['TL_LANG']['i7SHOP']['ccname']		= 'Name auf der Kreditkarte';
$GLOBALS['TL_LANG']['i7SHOP']['ccdatem']		= 'Gültig bis (Monat)';
$GLOBALS['TL_LANG']['i7SHOP']['ccdatey']		= 'Gültig bis (Jahr)';
$GLOBALS['TL_LANG']['i7SHOP']['ccv']		= 'CCV Prüfziffer (auf der Rückseite der Kreditkarte)';

$GLOBALS['TL_LANG']['i7SHOP']['termsaccepted']		= 'Ich akzeptiere die AGB';

$GLOBALS['TL_LANG']['i7SHOP']['PAYMENT_PREPAY']			= "Vorauskasse";


// TEMPLATS
// BASKET
$GLOBALS['TL_LANG']['i7SHOP']['BASKET_TITLE']	= "Warenkorb";
$GLOBALS['TL_LANG']['i7SHOP']['BASKET_AMOUNT']	= "Menge";
$GLOBALS['TL_LANG']['i7SHOP']['BASKET_ITEM']	= "Artikel";
$GLOBALS['TL_LANG']['i7SHOP']['BASKET_UNITPRICE']	= "Preis pro Einheit";
$GLOBALS['TL_LANG']['i7SHOP']['BASKET_SUBTOTAL']	= "Subtotal";
$GLOBALS['TL_LANG']['i7SHOP']['BASKET_TOTAL']	= "Total";
$GLOBALS['TL_LANG']['i7SHOP']['BASKET_ORDER_COMMAND']	= "Jetzt bestellen!";
$GLOBALS['TL_LANG']['i7SHOP']['BASKET_EMPTY']	= "Ihr Warenkorb ist leer";
$GLOBALS['TL_LANG']['i7SHOP']['BASKET_DELETE_COMMAND']	= "Löschen";

// O STEP 1
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP1_TITLE']	= "Schritt 1/4 - Login / Registrierung";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP3_SUBTITLE']	= "";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP1_TITLE_LOGIN']	= "Wenn Sie bereits einen Benutzer besitzen, können Sie sich hier anmelden";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP1_LOGIN_ERROR']	= "Benutzer nicht gefunden";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP1_LOGIN_COMMAND']	= "Login";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP1_TITLE_REGISTER']	= "Sind sie Neukunde? Dann melden Sie sich bitte hier an";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP1_REGISTER_USEREXISTS']	= "Diese E-Mail (username) wird bereits verwendet.";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP1_REGISTER_COMMAND']	= "Registrieren";


/* O STEP 2 */
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP2_TITLE']	= "Schritt 2/4 - Adresse";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP2_SUBTITLE']	= "Ihre Adressen";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP2_SHIPPING_ADDRESS_TITLE']	= "Lieferadresse";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP2_SHIPPING_ADDRESS_NO']	= "Ich möchte eine andere Lieferadresse";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP2_SHIPPING_ADDRESS_YES']	= "Lieferadresse ist gleich wie die Rechnungsadresse";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP2_BACK_COMMAND']	= "« Zurück";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP2_CONTINUE_COMMAND']	= "Weiter zu Schritt 2/4";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP2_LEGAL_TEXT']	= "%sAllgemeine Geschäftsbedingungen%s";

/* O STEP 3 */
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP3_TITLE']	= "Schritt 3/4 - Bezahlung";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP3_SUBTITLE']	= "";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP3_CHOOSE']	= "--- bitte wählen ---";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP3_BACK_COMMAND']	= "« Zurück";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP3_CONTINUE_COMMAND']	= "Weiter zu Schritt 3/4";

$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_TITLE']	= "Schritt 4/4 - Bestätigung";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_SUBTITLE']	= "Bitte überprüfen Sie Ihre Bestellung";

$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_SHIP_TIME']	= "Die Lieferung wird voraussichtlich %s Tage benötigen";

$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_BACK_COMMAND']	= "« Zurück";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_CONTINUE_COMMAND']	= "Bestätigen & bezahlen!";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_PAYMENT_ERROR_TITLE']	= "Ein Fehler ist aufgetreten";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_PAYMENT_ERROR_TEXT']	= "Gehen Sie zurück und ändern Sie ihre Kreditkarteninfos";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_BLOCK_PAYMENTINFO']	= "Bezahlungsdetails";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_BLOCK_SHIPPINGADDRESSINFO']	= "Lieferadresse";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_BLOCK_BILLADDRESSINFO']	= "Rechnungsadresse";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_ENDTOTAL']	= "Zu bezahlender Betrag";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_TOTAL_SHIPPMENT']	= "Versandkosten";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_TOTAL']	= "Total";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_TAXES_INCLUDED']	= "Enthaltene MWST (%s)";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_TAXES_EXCLUDED']	= "MWST (%s%)";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_BASKET_AMOUNT']	= $GLOBALS['TL_LANG']['i7SHOP']['BASKET_AMOUNT'];
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_BASKET_ITEM']	= $GLOBALS['TL_LANG']['i7SHOP']['BASKET_ITEM'];
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_BASKET_UNITPRICE']	= $GLOBALS['TL_LANG']['i7SHOP']['BASKET_UNITPRICE'];
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_BASKET_SUBTOTAL']	= $GLOBALS['TL_LANG']['i7SHOP']['BASKET_SUBTOTAL'];


$GLOBALS['TL_LANG']['i7SHOP']['ORDER_THANKS_TITLE']	= "Danke für Ihre Bestellung";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_THANKS_SUBTITLE']	= "Sie sollten in der nächsten Stunde ein bestätigungs E-Mail erhalten.";
$GLOBALS['TL_LANG']['i7SHOP']['ORDER_THANKS_BACK_COMMAND']	= "« zur Home-Seite";







$GLOBALS['TL_LANG']['i7SHOP']['BASKETINFO']	= "Sie haben %s Produkt(e) in Ihrem %sWarenkorb%s";





?>