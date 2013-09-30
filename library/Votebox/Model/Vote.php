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


class Vote extends \Model
{

    /**
     * Table name
     * @var string
     */
    protected static $strTable = 'tl_votebox_votes';

    /**
     * Set IP if not already done
     * @param   array The data array
     * @return  array The modified data array
     */
    protected function preSave(array $arrSet)
    {
        if (!$arrSet['ip']) {
            $arrSet['ip'] = \Environment::get('ip');
        }

        return $arrSet;
    }

    /**
     * Find by idea and user
     * @param   Idea
     * @param   array
     * @return  Vote
     */
    public static function findByIdeaAndUser(Idea $objIdea, $arrOptions=array())
    {
        $t = static::$strTable;
        $arrColumns = array();
        $arrValues = array();

        $arrColumns[] = "$t.pid=?";
        $arrValues[] = $objIdea->id;

        if (FE_USER_LOGGED_IN === true) {
            $arrColumns[] = "$t.member_id=?";
            $arrValues[] = \FrontendUser::getInstance()->id;
        } else {
            $arrColumns[] = "$t.ip=?";
            $arrValues[] = \Environment::get('ip');
        }

        $arrOptions = array_merge(
            array(
                'return' => 'Model'
            ),
            $arrOptions
        );

        return static::findBy($arrColumns, $arrValues, $arrOptions);
    }

}
