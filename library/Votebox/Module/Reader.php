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

use Votebox\Model\Idea;

class Reader extends Votebox
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
     * Detail Template
     * @var object
     */
    protected $objDetailTemplate = null;

    /**
     * Messages
     * @var array
     */
    protected $arrMessages = array();


    /**
     * Display a wildcard in the back end
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE') {
            $objTemplate = new \BackendTemplate('be_wildcard');
            $objTemplate->wildcard = '### VOTEBOX: READER ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        if (\Input::get('idea') == '') {
            return '';
        }

        $this->intIdeaId = \Input::get('idea');

        return parent::generate();
    }



    /**
     * Generate module
     */
    protected function compile()
    {
        $objIdea = Idea::findPublishedByArchiveAndPk($this->objArchive, $this->intIdeaId);

        if ($objIdea === null) {
            $this->Template->hasData = false;
            $this->Template->lblNoContent = $GLOBALS['TL_LANG']['MSC']['vb_no_idea'];
            return;
        }

        // check if the user has voted and store it
        if ($_POST['FORM_SUBMIT'] == 'vote_form_' . $this->id) {
            $this->storeVote();
        }

        $this->Template->hasData = true;

        // detail template
        $this->objDetailTemplate = new \FrontendTemplate(($this->vb_reader_tpl) ? $this->vb_reader_tpl : 'votebox_reader_default');
        $this->objDetailTemplate->id = $this->id;

        if (\Environment::get('isAjaxRequest')) {
            $this->objDetailTemplate->isAjax = true;
        }

        // messages
        $this->objDetailTemplate->messages = $this->arrMessages;

        // idea
        $this->objDetailTemplate->arrIdea = $this->prepareIdea($objIdea);

        // vote data
        $this->objDetailTemplate->vote_formId = 'vote_form_' . $this->id;
        $this->objDetailTemplate->vote_action = \Environment::get('request');

        // labels
        $this->objDetailTemplate->lblVote                   = $GLOBALS['TL_LANG']['MSC']['vb_vote'];
        $this->objDetailTemplate->lblUnvote                 = $GLOBALS['TL_LANG']['MSC']['vb_unvote'];
        $this->objDetailTemplate->lblSuccessfullyVoted      = $GLOBALS['TL_LANG']['MSC']['vb_successfully_voted'];
        $this->objDetailTemplate->lblSuccessfullyUnvoted    = $GLOBALS['TL_LANG']['MSC']['vb_successfully_unvoted'];
        $this->objDetailTemplate->lblTooManyVotes           = $GLOBALS['TL_LANG']['MSC']['vb_too_many_votes'];

        if (!$objIdea->canVote()) {
            $this->objDetailTemplate->tooManyVotes = true;
        }

        // add comments
        $this->addComments($this->objDetailTemplate->arrIdea);

        // parse the detail template
        $strBuffer = $this->objDetailTemplate->parse();

        if (\Environment::get('isAjaxRequest') && $_POST['FORM_SUBMIT'] == 'vote_form_' . $this->id) {
            ob_end_clean();
            header('Content-Type: text/html');
            header('Content-Length: ' . strlen($strBuffer));
            echo $strBuffer;
            exit;
        }

        $this->Template->content = $strBuffer;
    }


    /**
     * Store or delete vote (may also be an ajax request) if everything is fine
     */
    protected function storeVote()
    {
        $this->arrMessages['success'] = 'Successfully voted';
        /*
        if (!Votebox::hasVoted($this->intIdeaId, $this->intMemberId)) {
            if (!Votebox::canMemberVote($this->intIdeaId, $this->intMemberId)) {
                if (\Environment::get('isAjaxRequest')) {
                    echo 'too_many_votes';
                    exit;
                } else {
                    $_SESSION['VOTEBOX_TOO_MANY_VOTES'][$this->intIdeaId] = true;
                }
                return;
            }

            Votebox::storeVote($this->intIdeaId, $this->intMemberId);

            if ($this->Environment->isAjaxRequest) {
                echo 'successfully_voted';
                exit;
            }
            else {
                $_SESSION['VOTEBOX_SUCCESSFULLY_VOTED'][$this->intIdeaId] = true;
            }
        } else {
            Votebox::deleteVote($this->intIdeaId, $this->intMemberId);

            if ($this->Environment->isAjaxRequest) {
                echo 'successfully_unvoted';
                exit;
            } else {
                $_SESSION['VOTEBOX_SUCCESSFULLY_UNVOTED'][$this->intIdeaId] = true;
            }
        }*/
    }


    /**
     * Add comments to the template
     */
    protected function addComments($arrIdea)
    {
        $this->objDetailTemplate->allowComments = false;

        if ($this->arrArchiveData['allowComments'] == 1) {
            $this->objDetailTemplate->allowComments = true;
            $this->import('Comments');
            $arrNotifies = array();

            // Notify system administrator
            if ($this->arrArchiveData['notify'] != 'notify_author') {
                $arrNotifies[] = $GLOBALS['TL_ADMIN_EMAIL'];
            }

            // Notify author
            if ($this->arrArchiveData['notify'] != 'notify_admin'
                && $arrIdea['email'] != ''
                && !$this->isValidEmailAddress($arrIdea['email'])) {
                $arrNotifies[] = $arrIdea['email'];
            }

            // Adjust the comments headline level
            $intHl = min(intval(str_replace('h', '', $this->hl)), 5);
            $this->objDetailTemplate->hlc = 'h' . ($intHl + 1);

            $objConfig = new \stdClass();
            $objConfig->requireLogin = true;
            $objConfig->perPage = $this->arrArchiveData['perPage'];
            $objConfig->order = $this->arrArchiveData['sortOrder'];
            $objConfig->template = $this->com_template;
            $objConfig->disableCaptcha = $this->arrArchiveData['disableCaptcha'];
            $objConfig->bbcode = $this->arrArchiveData['bbcode'];
            $objConfig->moderate = $this->arrArchiveData['comments_moderate'];

            $this->Comments->addCommentsToTemplate(
                $this->objDetailTemplate,
                $objConfig,
                'tl_votebox_ideas',
                $this->intIdeaId,
                $arrNotifies);
        }
    }
}