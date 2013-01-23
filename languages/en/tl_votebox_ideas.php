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
$GLOBALS['TL_LANG']['tl_votebox_ideas']['title']						= array('Title', 'Enter a title for the idea.');
$GLOBALS['TL_LANG']['tl_votebox_ideas']['creation_date']				= array('Creation date', 'Please choose creation date and time.');
$GLOBALS['TL_LANG']['tl_votebox_ideas']['member_id']					= array('Author', 'Choose an author.');
$GLOBALS['TL_LANG']['tl_votebox_ideas']['published']					= array('Publish idea', 'Publishes this idea.');
$GLOBALS['TL_LANG']['tl_votebox_ideas']['text']							= array('Idea text', 'Present your idea.');

/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_votebox_ideas']['votebox_ideas_legend'] = 'Details of the idea';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_votebox_ideas']['new'] 		= array('New idea', 'Add a new idea.');
$GLOBALS['TL_LANG']['tl_votebox_ideas']['edit']		= array('Edit idea', 'Edit idea ID %s.');
$GLOBALS['TL_LANG']['tl_votebox_ideas']['copy']		= array('Duplicate idea', 'Duplicate idea ID %s.');
$GLOBALS['TL_LANG']['tl_votebox_ideas']['delete']	= array('Delete idea', 'Delete idea ID %s.');
$GLOBALS['TL_LANG']['tl_votebox_ideas']['toggle']	= array('Publish/Unpublish idea', 'Publish/Unpublish idea ID %s.');
$GLOBALS['TL_LANG']['tl_votebox_ideas']['show']		= array('Show details', 'Show details of idea ID %s.');
$GLOBALS['TL_LANG']['tl_votebox_ideas']['backers']	= array('Show backers', 'Show all backers of idea ID %s.');