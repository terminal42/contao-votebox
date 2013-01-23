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
 * Back end modules
 */
$GLOBALS['TL_LANG']['MOD']['votebox'] = array('Votebox', 'Let your members vote on ideas.');


/**
 * Front end modules
 */
$GLOBALS['TL_LANG']['FMD']['votebox']			= 'Votebox';
$GLOBALS['TL_LANG']['FMD']['votebox_list']		= array('Votebox list', 'Displays a list of all ideas of a votebox archive.');
$GLOBALS['TL_LANG']['FMD']['votebox_reader']	= array('Votebox reader', 'Shows details of a certain idea.');
$GLOBALS['TL_LANG']['FMD']['votebox_new_idea']	= array('Votebox New ideas', 'Provides the form to add a new idea.');