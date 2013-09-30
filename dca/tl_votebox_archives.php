<?php

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
 * @copyright  terminal42 gmbh 2012-2013
 * @author     Yanick Witschi <yanick.witschi@terminal42.ch>
 * @package    votebox
 * @license    LGPL
 * @filesource
 */


/**
 * Table tl_votebox_archives
 */
$GLOBALS['TL_DCA']['tl_votebox_archives'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'ctable'                      => array('tl_votebox_ideas'),
        'enableVersioning'            => true,
        'sql' => array
        (
            'keys' => array
            (
                'id'            => 'primary'
            )
        )
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 1,
            'fields'                  => array('title'),
            'flag'                    => 1
        ),
        'label' => array
        (
            'fields'                  => array('title'),
            'format'                  => '%s'
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
            )
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_votebox_archives']['edit'],
                'href'                => 'table=tl_votebox_ideas',
                'icon'                => 'edit.gif',
                'attributes'          => 'class="contextmenu"'
            ),
            'editheader' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_votebox_archives']['editheader'],
                'href'                => 'act=edit',
                'icon'                => 'header.gif',
                'attributes'          => 'class="edit-header"'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_votebox_archives']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_votebox_archives']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_votebox_archives']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
        )
    ),

    // Palettes
    'palettes' => array
    (
        '__selector__'                => array('moderate','allowComments'),
        'default'                     => '{archive_legend},title,numberOfVotes,moderate;{comments_legend},allowComments;'
    ),

    // Subpalettes
    'subpalettes' => array
    (
        'moderate'                    => 'receiver_mail',
        'allowComments'               => 'notify,sortOrder,perPage,comments_moderate,bbcode,disableCaptcha'
    ),

    // Fields
    'fields' => array
    (
        'id' => array
        (
            'sql'                 =>  "int(10) unsigned NOT NULL auto_increment",
        ),
        'tstamp' => array
        (
            'sql'                 =>  "int(10) unsigned NOT NULL default '0'",
        ),
        'title' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_votebox_archives']['title'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'numberOfVotes' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_votebox_archives']['numberOfVotes'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>5, 'rgxp'=>'digit', 'tl_class'=>'w50'),
            'sql'                     => "smallint(5) unsigned NOT NULL default '0'"
        ),
        'moderate' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_votebox_archives']['moderate'],
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('tl_class'=>'clr'),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'receiver_mail' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_votebox_archives']['receiver_mail'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'rgxp'=>'email', 'maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'allowComments' => array
        (
            'label'                        => &$GLOBALS['TL_LANG']['tl_votebox_archives']['allowComments'],
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('tl_class'=>'clr', 'submitOnChange'=>true),
            'sql'                     => "varchar(32) NOT NULL default ''"
        ),
        'notify' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_votebox_archives']['notify'],
            'default'                 => 'notify_admin',
            'exclude'                 => true,
            'inputType'               => 'select',
            'options'                 => array('notify_admin', 'notify_author', 'notify_both'),
            'reference'               => &$GLOBALS['TL_LANG']['tl_votebox_archives']['notify'],
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'sortOrder' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_votebox_archives']['sortOrder'],
            'default'                 => 'ascending',
            'exclude'                 => true,
            'inputType'               => 'select',
            'options'                 => array('ascending', 'descending'),
            'reference'               => &$GLOBALS['TL_LANG']['MSC'],
            'eval'                    => array('tl_class'=>'w50'),
            'sql'                     => "varchar(32) NOT NULL default ''"
        ),
        'perPage' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_votebox_archives']['perPage'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50'),
            'sql'                     => "smallint(5) unsigned NOT NULL default '0'"
        ),
        'comments_moderate' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_votebox_archives']['comments_moderate'],
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('tl_class'=>'w50'),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'bbcode' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_votebox_archives']['bbcode'],
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('tl_class'=>'w50'),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'disableCaptcha' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_votebox_archives']['disableCaptcha'],
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('tl_class'=>'w50'),
            'sql'                     => "char(1) NOT NULL default ''"
        )
    )
);