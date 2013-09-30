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

namespace Votebox\Module;

use Votebox\Model\Archive;

abstract class Votebox extends \Module
{

    /**
     * Votebox archive model
     * @var Votebox\Model\Archive
     */
    protected $objArchive = null;


    /**
     * Make sure the module is only used when a user is logged in and check votebox archive id
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'FE') {

            // Check for the votebox archive
            if (($this->objArchive = Archive::findByPk($this->vb_archive)) === null) {
                $this->log('Votebox archive with ID "' . $this->vb_archive . '" does not exist', __METHOD__, TL_ERROR);
                return '';
            }
        }

        return parent::generate();
    }


    /**
     * Gets an array of ideas
     * @param int archive id
     * @param int idea id
     * @param int jumpTo page id
     * @param string ORDER BY definition
     * @param array limit in style array('offset'=>0,'limit'=>10)
     * @return array|false
     */
    public function getIdeas($intArchiveId, $intIdeaId=false, $intJumpToId=false, $strOrderBy=false, $arrLimit=false)
    {
        $strWhere = ' WHERE vb.pid=? AND vb.published=?';
        $arrExecute = array($intArchiveId, 1);

        if ($intIdeaId)
        {
            $strWhere .= ' AND vb.id=? LIMIT 1';
            $arrExecute[] = $intIdeaId;
        }

        if (!$strOrderBy)
        {
            $strOrderBy = '';
        }
        else
        {
            $strOrderBy = ' ORDER BY ' . $strOrderBy;
        }

        // get ideas for this votebox
        $objIdeas = \Database::getInstance()->prepare("SELECT
                                                vb.id AS id,
                                                vb.title AS title,
                                                vb.creation_date AS creation_date,
                                                vb.text AS text,
                                                m.firstname AS firstname,
                                                m.lastname AS lastname,
                                                m.email AS email,
                                                (
                                                    SELECT COUNT(id) FROM tl_votebox_votes AS votes WHERE vb.id=votes.pid
                                                ) AS voteCount
                                            FROM
                                                tl_votebox_ideas AS vb
                                            LEFT JOIN
                                                tl_member AS m
                                            ON
                                                vb.member_id=m.id" . $strWhere . $strOrderBy);

        if ($arrLimit)
        {
            $objIdeas->limit($arrLimit['limit'], $arrLimit['offset']);
        }

        $objIdeas = $objIdeas->execute($arrExecute);

        if (!$objIdeas->numRows)
        {
            return false;
        }

        $arrData = $objIdeas->fetchAllAssoc();

        // get jumpTo page data
        if ($intJumpToId)
        {
            $objJumpTo = \Database::getInstance()->prepare("SELECT id,alias FROM tl_page WHERE id=?")->limit(1)->execute($this->vb_reader_jumpTo);
        }

        foreach ($arrData as $k => $arrRow)
        {
            $arrData[$k]['creation_date']    = \System::parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $arrRow['creation_date']);
            $arrData[$k]['hasVoted'] = \Votebox\Votebox::hasVoted($arrRow['id'], \FrontendUser::getInstance()->id);

            if ($intJumpToId)
            {
                $arrData[$k]['reader_url']    = \Controller::generateFrontendUrl($objJumpTo->row(), '/idea/' . $arrRow['id']);
            }
        }

        return $arrData;
    }
}

