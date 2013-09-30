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

namespace Votebox\Model;


class Idea extends \Model
{

    /**
     * Table name
     * @var string
     */
    protected static $strTable = 'tl_votebox_ideas';


    /**
     * Get URL
     * @return  string
     */
    public function getUrl()
    {
        return \Controller::generateFrontendUrl($this->getRelated('pid')->getRelated('jumpTo')->row(), '/idea/' . $this->id);
    }

    /**
     * Returns the table
     * @return  string
     */
    public static function getTable()
    {
        return static::$strTable;
    }

    /**
     * Find by archive
     * @param   Archive
     * @param   array
     * @return  \Collection
     */
    public static function findPublishedByArchiveAndPk(Archive $objArchive, $intPk, $arrOptions=array())
    {
        $t = static::$strTable;
        $arrColumns[] = "$t.id=?";
        $arrColumns[] = "$t.pid=?";

        if (!BE_USER_LOGGED_IN) {
            $arrColumns[] = "$t.published=1";
        }

        $arrOptions = array_merge(
            array(
                'return' => 'Model'
            ),
            $arrOptions
        );

        return static::findBy($arrColumns, array($intPk, $objArchive->id), $arrOptions);
    }


    /**
     * Find by archive
     * @param   Archive
     * @param   array
     * @return  \Collection
     */
    public static function findPublishedByArchive(Archive $objArchive, $arrOptions=array())
    {
        $t = static::$strTable;
        $arrColumns[] = "$t.pid=?";

        if (!BE_USER_LOGGED_IN) {
            $arrColumns[] = "$t.published=1";
        }

        return static::findBy($arrColumns, array($objArchive->id), $arrOptions);
    }


    /**
     * Find by archive
     * @param   Archive
     * @param   array
     * @return  \Collection
     */
    public static function countPublishedByArchive(Archive $objArchive, $arrOptions=array())
    {
        $t = static::$strTable;
        $arrColumns[] = "$t.pid=?";

        if (!BE_USER_LOGGED_IN) {
            $arrColumns[] = "$t.published=1";
        }

        return static::countBy($arrColumns, array($objArchive->id), $arrOptions);
    }
}
