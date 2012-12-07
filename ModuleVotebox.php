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
 * Class ModuleVotebox
 *
 * @copyright  terminal42 gmbh 2012
 * @author     Yanick Witschi <yanick.witschi@terminal42.ch>
 * @package    Controller
 */
abstract class ModuleVotebox extends Module
{
	
	/**
	 * Votebox archive data
	 * @var array
	 */
	protected $arrArchiveData = array();
	

	/**
	 * Make sure the module is only used when a user is logged in and check votebox archive id
	 * @param Database_Result
	 * @param string
	 */
	public function __construct(Database_Result $objModule, $strColumn='main')
	{
		// call parent constructor
		parent::__construct($objModule, $strColumn);

		if (TL_MODE == 'FE')
		{
			if (!FE_USER_LOGGED_IN)
			{
				$this->log('Votebox can only be accessed when logged in', 'ModuleVotebox __construct()', TL_ERROR);
				return '';
			}
			// check for the votebox
			$objVoteBox = $this->Database->prepare("SELECT * FROM tl_votebox_archives WHERE id=?")->limit(1)->execute($this->vb_archive);
			
			if (!$objVoteBox->numRows)
			{
				$this->log('Votebox archive with ID "' . $this->vb_archive . '" does not exist', __METHOD__, TL_ERROR);
				return '';
			}
			
			$this->arrArchiveData = $objVoteBox->fetchAssoc();
		}
	}


	/**
	 * Gets an array of ideas
	 * @param int
	 * @param int
	 * @param int
	 * @return array|false
	 */
	public function getIdeas($intArchiveId, $intIdeaId=false, $intJumpToId=false)
	{
		$strWhere = ' WHERE vb.pid=? AND vb.published=?';
		$arrExecute = array($intArchiveId, 1);
		
		if ($intIdeaId)
		{
			$strWhere .= ' AND vb.id=? LIMIT 1';
			$arrExecute[] = $intIdeaId;
		}
		
		// get ideas for this votebox
		$objIdeas = $this->Database->prepare("SELECT
												vb.id AS id,
												vb.title AS title,
												vb.creation_date AS creation_date,
												vb.text AS text,
												m.firstname AS firstname,
												m.lastname AS lastname,
												(
													SELECT COUNT(id) FROM tl_votebox_votes AS votes WHERE vb.id=votes.pid
												) AS voteCount
											FROM
												tl_votebox_ideas AS vb
											LEFT JOIN
												tl_member AS m
											ON
												vb.member_id=m.id" . $strWhere)
								  ->execute($arrExecute);
								  
		if (!$objIdeas->numRows)
		{
			return false;
		}
		
		$arrData = $objIdeas->fetchAllAssoc();
		
		// get jumpTo page data
		if ($intJumpToId)
		{
			$objJumpTo = $this->Database->prepare("SELECT id,alias FROM tl_page WHERE id=?")->limit(1)->execute($this->vb_reader_jumpTo);
		}
		
		foreach ($arrData as $k => $arrRow)
		{
			$arrData[$k]['creation_date']	= $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $arrRow['creation_date']);
			
			if($intJumpToId)
			{
				$arrData[$k]['reader_url']	= $this->generateFrontendUrl($objJumpTo->row(), '/ideaId/' . $arrRow['id']);
			}
		}
		
		return $arrData;
	}
}

