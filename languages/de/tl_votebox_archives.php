<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  terminal42 gmbh 2012
 * @author     Yanick Witschi <yanick.witschi@terminal42.ch>
 * @package    votebox 
 * @license    LGPL 
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_votebox_archives']['title']				= array('Titel', 'Geben Sie einen Titel für dieses Archiv ein.');
$GLOBALS['TL_LANG']['tl_votebox_archives']['moderate']			= array('Archiv moderieren', 'Aktivieren Sie diese Checkbox wenn Sie die Ideen bei Erstellung nicht direkt veröffentlichen möchten.');
$GLOBALS['TL_LANG']['tl_votebox_archives']['receiver_mail']		= array('E-Mail-Adresse Benachrichtigter', 'Wählen Sie die E-Mail-Adresse an die eine Benachrichtigung gesendet werden soll, wenn eine neue Idee freizuschalten ist.');
$GLOBALS['TL_LANG']['tl_votebox_archives']['allowComments']  	= array('Kommentare aktivieren', 'Besuchern das Kommentieren von Nachrichtenbeiträgen erlauben.');
$GLOBALS['TL_LANG']['tl_votebox_archives']['notify']         	= array('Benachrichtigung an', 'Bitte legen Sie fest, wer beim Hinzufügen neuer Kommentare benachrichtigt wird.');
$GLOBALS['TL_LANG']['tl_votebox_archives']['sortOrder']      	= array('Sortierung', 'Standardmäßig werden Kommentare aufsteigend sortiert, beginnend mit dem ältesten.');
$GLOBALS['TL_LANG']['tl_votebox_archives']['perPage']        	= array('Kommentare pro Seite', 'Anzahl an Kommentaren pro Seite. Geben Sie 0 ein, um den automatischen Seitenumbruch zu deaktivieren.');
$GLOBALS['TL_LANG']['tl_votebox_archives']['comments_moderate']	= array('Kommentare moderieren', 'Kommentare erst nach Bestätigung auf der Webseite veröffentlichen.');
$GLOBALS['TL_LANG']['tl_votebox_archives']['bbcode']         	= array('BBCode erlauben', 'Besuchern das Formatieren ihrer Kommentare mittels BBCode erlauben.');
$GLOBALS['TL_LANG']['tl_votebox_archives']['disableCaptcha'] 	= array('Sicherheitsfrage deaktivieren', 'Wählen Sie diese Option nur, wenn das Erstellen von Kommentaren auf authentifizierte Benutzer beschränkt ist.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_votebox_archives']['archive_legend'] 	= 'Votebox Archiv Einstellungen';
$GLOBALS['TL_LANG']['tl_votebox_archives']['comments_legend']	= 'Kommentare';

/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_votebox_archives']['notify']['notify_admin']  = 'Systemadministrator';
$GLOBALS['TL_LANG']['tl_votebox_archives']['notify']['notify_author'] = 'Autor der Idee';
$GLOBALS['TL_LANG']['tl_votebox_archives']['notify']['notify_both']   = 'Autor und Systemadministrator';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_votebox_archives']['new']    = array('Neues Archiv', 'Legen Sie ein neues Archiv an.');
$GLOBALS['TL_LANG']['tl_votebox_archives']['edit']   = array('Archiv editieren', 'Editieren Sie das Archiv mit der ID %s.');
$GLOBALS['TL_LANG']['tl_votebox_archives']['copy']   = array('Archiv duplizieren', 'Duplizieren Sie das Archiv mit der ID %s.');
$GLOBALS['TL_LANG']['tl_votebox_archives']['delete'] = array('Archiv löschen', 'Löschen Sie das Archiv mit der ID %s.');
$GLOBALS['TL_LANG']['tl_votebox_archives']['show']   = array('Details zeigen', 'Zeigen Sie die Details des Archivs mit der ID %s.');