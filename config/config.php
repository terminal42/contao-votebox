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
$GLOBALS['BE_MOD']['content']['votebox'] = array
(
	'tables'       => array('tl_votebox_archives', 'tl_votebox_ideas', 'tl_votebox_votes'),
	'icon'         => 'system/modules/votebox/assets/be_mod_icon.png'
);


/**
 * Front end modules
 */
$GLOBALS['FE_MOD']['votebox'] = array
(
	'votebox_list'		=> 'ModuleVoteboxList',
	'votebox_reader'	=> 'ModuleVoteboxReader',
	'votebox_new_idea'	=> 'ModuleVoteboxNewIdea'
);

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['dispatchAjax'][] = array('Votebox', 'dispatchAjax');