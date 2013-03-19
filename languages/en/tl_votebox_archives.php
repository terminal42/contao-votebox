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
$GLOBALS['TL_LANG']['tl_votebox_archives']['title']				= array('Title', 'Please add a title for this archive.');
$GLOBALS['TL_LANG']['tl_votebox_archives']['numberOfVotes']		= array('Maximum number of votes', 'Here you can eneter a maximum number of votes per question.');
$GLOBALS['TL_LANG']['tl_votebox_archives']['moderate']			= array('Moderate archive', 'Enable if ideas should not be published automatically.');
$GLOBALS['TL_LANG']['tl_votebox_archives']['receiver_mail']		= array('Email address', 'Enter the email address to which the notification that there is a new idea to publish should be sent.');
$GLOBALS['TL_LANG']['tl_votebox_archives']['allowComments']     = array('Enable comments', 'Allow visitors to comment news items.');
$GLOBALS['TL_LANG']['tl_votebox_archives']['notify']            = array('Notify', 'Please choose who to notify when comments are added.');
$GLOBALS['TL_LANG']['tl_votebox_archives']['sortOrder']         = array('Sort order', 'By default, comments are sorted ascending, starting with the oldest one.');
$GLOBALS['TL_LANG']['tl_votebox_archives']['perPage']           = array('Comments per page', 'Number of comments per page. Set to 0 to disable pagination.');
$GLOBALS['TL_LANG']['tl_votebox_archives']['moderate']          = array('Moderate comments', 'Approve comments before they are published on the website.');
$GLOBALS['TL_LANG']['tl_votebox_archives']['bbcode']            = array('Allow BBCode', 'Allow visitors to format their comments with BBCode.');
$GLOBALS['TL_LANG']['tl_votebox_archives']['requireLogin']      = array('Require login to comment', 'Allow only authenticated users to create comments.');
$GLOBALS['TL_LANG']['tl_votebox_archives']['disableCaptcha']    = array('Disable the security question', 'Use this option only if you have limited comments to authenticated users.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_votebox_archives']['archive_legend'] 	= 'Votebox archive settings';
$GLOBALS['TL_LANG']['tl_votebox_archives']['comments_legend']	= 'Comments';

/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_votebox_archives']['notify_admin']  = 'System administrator';
$GLOBALS['TL_LANG']['tl_votebox_archives']['notify_author'] = 'Author of the news item';
$GLOBALS['TL_LANG']['tl_votebox_archives']['notify_both']   = 'Author and system administrator';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_votebox_archives']['new']           = array('New archive', 'Add a new archive.');
$GLOBALS['TL_LANG']['tl_votebox_archives']['edit']          = array('Edit archive', 'Edit archive ID %s.');
$GLOBALS['TL_LANG']['tl_votebox_archives']['editheader']    = array('Edit archive settings', 'Edit the settings of archive ID %s');
$GLOBALS['TL_LANG']['tl_votebox_archives']['copy']          = array('Duplicate archive', 'Duplicate archive ID %s.');
$GLOBALS['TL_LANG']['tl_votebox_archives']['delete']        = array('Delete archive', 'Delete archive ID %s.');
$GLOBALS['TL_LANG']['tl_votebox_archives']['show']          = array('Show details', 'Show details of archive ID %s.');