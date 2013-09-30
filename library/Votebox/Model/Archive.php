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


class Archive extends \Model
{

    /**
     * Table name
     * @var string
     */
    protected static $strTable = 'tl_votebox_archives';

    /**
     * Can the user vote on this archive?
     * @return  boolean
     */
    public function canVote()
    {
        if (!$this->numberOfVotes) {
            return true;
        }

        $arrValues = array();

        $strQuery = 'SELECT
                            COUNT(id) as voteCount
                         FROM
                            tl_votebox_votes AS votes
                         INNER JOIN
                            tl_votebox_ideas AS ideas
                         ON
                            votes.pid=ideas.id
                         WHERE
                            ideas.pid=?';

        $arrValues[] = $this->id;

        if ($this->mode == 'member') {
            if (FE_USER_LOGGED_IN !== true) {
                return false;
            }

            $strQuery .= ' AND member_id=?';
            $arrValues[] = \FrontendUser::getInstance()->id;
        } else {
            $strQuery .= ' AND ip=?';
            $arrValues[] = \Environment::get('ip');

            if ($this->ipRestrictionTime) {
                $strQuery .= ' AND vote_date >=?';
                $arrValues[] = (time() - $this->ipRestrictionTime);
            }
        }

        $intTotalVotes = \Database::getInstance()->prepare($strQuery)->execute($arrValues)->voteCount;

        if ($intTotalVotes >= $this->numberOfVotes) {
            return false;
        }

        return true;
    }
}
