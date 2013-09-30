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


class Votebox extends Controller
{	
	/**
	 * Check if a member has already voted for a certain idea
	 * @param int | idea id
	 * @param int | member id
	 * @return boolean
	 */
	public static function hasVoted($intIdeaId, $intMemberId)
	{
		$objVote = Database::getInstance()->prepare("SELECT id FROM tl_votebox_votes WHERE pid=? AND member_id=?")->limit(1)->executeUncached($intIdeaId, $intMemberId);
		if ($objVote->numRows)
		{
			return true;
		}
		
		return false;
	}


	/**
	 * Return true if member can still vote
	 * @param int | idea id
	 * @param int | member id
	 * @return boolean
	 */
	public static function canMemberVote($intIdeaId, $intMemberId)
	{
		$objArchive = Database::getInstance()->prepare("SELECT * FROM tl_votebox_archives WHERE id=(SELECT pid FROM tl_votebox_ideas WHERE id=?)")->executeUncached($intIdeaId);
		$intVotes = Database::getInstance()->prepare("SELECT COUNT(*) AS total FROM tl_votebox_votes WHERE pid IN (SELECT id FROM tl_votebox_ideas WHERE pid=?) AND member_id=?")->executeUncached($objArchive->id, $intMemberId)->total;

		return ($objArchive->numberOfVotes && ($objArchive->numberOfVotes <= $intVotes)) ? false : true;
	}


	/**
	 * Store the vote
	 * @param int | idea id
	 * @param int | member id
	 */
	public static function storeVote($intIdeaId, $intMemberId)
	{
		$arrData = array();
		$arrData['pid']				= $intIdeaId;
		$arrData['tstamp']			= time();
		$arrData['vote_date']		= time();
		$arrData['member_id']		= $intMemberId;
		
		Database::getInstance()->prepare("INSERT INTO tl_votebox_votes %s")->set($arrData)->execute();
	}


	/**
	 * Delete the vote
	 * @param int | idea id
	 * @param int | member id
	 */
	public static function deleteVote($intIdeaId, $intMemberId)
	{
		Database::getInstance()->prepare("DELETE FROM tl_votebox_votes WHERE pid=? AND member_id=?")->execute($intIdeaId, $intMemberId);
	}
}

