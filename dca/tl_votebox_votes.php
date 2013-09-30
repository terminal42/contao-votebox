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
 * Table tl_votebox_votes
 */
$GLOBALS['TL_DCA']['tl_votebox_votes'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'                 => 'Table',
        'ptable'                        => 'tl_votebox_ideas',
        'closed'                        => true,
        'notDeletable'                  => true,
        'notEditable'                   => true,
        'sql' => array
        (
            'keys' => array
            (
                'id'            => 'primary',
                'pid'           => 'index',
                'member_id'     => 'index'
            )
        ),
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                      => 1,
            'fields'                    => array('vote_date'),
            'flag'                      => 5
        ),
        'label' => array
        (
            'fields'                => array('member_id', 'vote_date'),
            'format'                => '%s (%s)',
            'label_callback'        => array('tl_votebox_votes', 'getLabel')
        ),
        'global_operations' => array
        (
            '' => array() // dummy array to display the back button *sigh*
        )
    ),


    // Fields
    'fields' => array
    (
        'id' => array
        (
            'sql'                 =>  "int(10) unsigned NOT NULL auto_increment",
        ),
        'pid' => array
        (
            'sql'                 =>  "int(10) unsigned NOT NULL default '0'",
        ),
        'tstamp' => array
        (
            'sql'                 =>  "int(10) unsigned NOT NULL default '0'",
        ),
        'member_id' => array
        (
            'label'                     => &$GLOBALS['TL_LANG']['tl_votebox_votes']['member_id'],
            'inputType'                 => 'text',
            'foreignKey'                => 'tl_member.name',
            'sql'                       => "int(10) unsigned NOT NULL default '0'"
        ),
        'vote_date' => array
        (
            'label'                     => &$GLOBALS['TL_LANG']['tl_votebox_votes']['vote_date'],
            'inputType'                 => 'text',
            'eval'                      => array('rgxp'=>'date'),
            'sql'                       => "int(10) unsigned NOT NULL default '0'"
        )
    )
);

class tl_votebox_votes extends \Backend
{
    /**
     * Generate label
     * @param array
     * @param string
     * @return string
     */
    public function getLabel($row, $label)
    {
        $objMember = \Database::getInstance()->prepare('SELECT firstname,lastname,username FROM tl_member WHERE id=?')->execute($row['member_id']);
        return $objMember->lastname . ', ' . $objMember->firstname . ' <span style="color:#ccc">[' . $objMember->username . ']</span>';
    }
}