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
 * Class ModuleVoteboxReader 
 *
 * @copyright  terminal42 gmbh 2012
 * @author     Yanick Witschi <yanick.witschi@terminal42.ch>
 * @package    Controller
 */
class ModuleVoteboxReader extends ModuleVotebox
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_votebox_reader';

	/**
	 * Idea id
	 * @var int
	 */
	protected $intIdeaId = 0;

	/**
	 * Member id
	 * @var int
	 */
	protected $intMemberId = 0;

	/**
	 * Detail Template
	 * @var object
	 */
	protected $objDetailTemplate = null;


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### VOTEBOX: READER ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		return parent::generate();
	}


	/**
	 * Generate module
	 */
	protected function compile()
	{
		$this->import('FrontendUser', 'Member');
		$this->intMemberId = $this->Member->id;
		$this->intIdeaId = $this->Input->get('idea');
		
		$arrData = $this->getIdeas($this->vb_archive, $this->intIdeaId);

		if (!$arrData)
		{
			$this->Template->hasData = false;
			$this->Template->lblNoContent = $GLOBALS['TL_LANG']['MSC']['vb_no_idea'];
			return;
		}

		// reset session data
		unset($_SESSION['VOTEBOX_SUCCESSFULLY_VOTED'][$this->intIdeaId]);
		unset($_SESSION['VOTEBOX_ALREADY_VOTED'][$this->intIdeaId]);

		// check if the user has voted and store it
		if ($_POST['FORM_SUBMIT'] == 'vote_form_' . $this->id)
		{
			$this->storeVote();
		}

		$this->Template->hasData = true;
		
		// detail template
		$this->objDetailTemplate = new FrontendTemplate(($this->vb_reader_tpl) ? $this->vb_reader_tpl : 'votebox_reader_default');

		// error and success messages
		$this->objDetailTemplate->errorStyle = $this->objDetailTemplate->successStyle = 'display:none;';
		if ($_SESSION['VOTEBOX_SUCCESSFULLY_VOTED'][$this->intIdeaId])
		{
			$this->objDetailTemplate->successStyle = '';
		}
		if ($_SESSION['VOTEBOX_ALREADY_VOTED'][$this->intIdeaId])
		{
			$this->objDetailTemplate->errorStyle = '';
		}

			// idea
		$this->objDetailTemplate->arrIdea = array_shift($arrData);

		// vote data
		$this->objDetailTemplate->vote_formId = 'vote_form_' . $this->id;
		$this->objDetailTemplate->vote_action = $this->Environment->request;

		// labels
		$this->objDetailTemplate->lblVote 				= $GLOBALS['TL_LANG']['MSC']['vb_vote'];
		$this->objDetailTemplate->lblAlreadyVoted		= $GLOBALS['TL_LANG']['MSC']['vb_already_voted'];
		$this->objDetailTemplate->lblSuccessfullyVoted	= $GLOBALS['TL_LANG']['MSC']['vb_successfully_voted'];
		// member id
		$this->objDetailTemplate->memberId = $this->intMemberId;

		// check if the user has alredy voted (useful for e.g. CSS classes)
		if (Votebox::hasVoted($this->intIdeaId, $this->intMemberId))
		{
			$this->objDetailTemplate->hasVoted = true;
		}

		// add a default CSS class to the container
		if ($this->objDetailTemplate->hasVoted)
		{
			$this->objDetailTemplate->class = 'hasVoted';
		}
		else
		{
			$this->objDetailTemplate->class = 'notYetVoted';
		}

		// add comments
		$this->addComments();
		
		// parse the detail template
		$this->Template->content = $this->objDetailTemplate->parse();
	}


	/**
	 * Store vote (may also be an ajax request) if everything is fine
	 */
	protected function storeVote()
	{
		if (!Votebox::hasVoted($this->intIdeaId, $this->intMemberId))
		{
			Votebox::storeVote($this->intIdeaId, $this->intMemberId);

			if ($this->Environment->isAjaxRequest)
			{
				echo 'successfully_voted';
				exit;
			}
			else
			{
				$_SESSION['VOTEBOX_SUCCESSFULLY_VOTED'][$this->intIdeaId] = true;
			}
		}
		else
		{
			if ($this->Environment->isAjaxRequest)
			{
				echo 'already_voted';
				exit;
			}
			else
			{
				$_SESSION['VOTEBOX_ALREADY_VOTED'][$this->intIdeaId] = true;
			}
		}
	}


	/**
	 * Add comments to the template
	 */
	protected function addComments()
	{
		$this->objDetailTemplate->allowComments = false;
		
		if ($this->arrArchiveData['allowComments'] == 1)
		{
			$this->objDetailTemplate->allowComments = true;
			$this->import('Comments');
			$arrNotifies = array();
			
			// Notify system administrator
			if ($this->arrArchiveData['notify'] != 'notify_author')
			{
				$arrNotifies[] = $GLOBALS['TL_ADMIN_EMAIL'];
			}
	
			// Notify author
			if ($this->arrArchiveData['notify'] != 'notify_admin')
			{
				$this->import('FrontendUser', 'Member');
				$arrNotifies[] = $this->Member->email;
			}
			
			// Adjust the comments headline level
			$intHl = min(intval(str_replace('h', '', $this->hl)), 5);
			$this->objDetailTemplate->hlc = 'h' . ($intHl + 1);
	
			$objConfig = new stdClass();
			$objConfig->requireLogin = true;
			$objConfig->perPage = $this->arrArchiveData['perPage'];
			$objConfig->order = $this->arrArchiveData['sortOrder'];
			$objConfig->template = $this->com_template;
			$objConfig->disableCaptcha = $this->arrArchiveData['disableCaptcha'];
			$objConfig->bbcode = $this->arrArchiveData['bbcode'];
			$objConfig->moderate = $this->arrArchiveData['comments_moderate'];
	
			$this->Comments->addCommentsToTemplate($this->objDetailTemplate, $objConfig, 'tl_votebox_ideas', $this->intIdeaId, $arrNotifies);
		}
	}
}