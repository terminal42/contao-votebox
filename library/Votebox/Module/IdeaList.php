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
use Votebox\Model\Vote;

class IdeaList extends Votebox
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_votebox_list';


    /**
     * Display a wildcard in the back end
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new \BackendTemplate('be_wildcard');
            $objTemplate->wildcard = '### VOTEBOX: LIST ###';
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
        //;
        $intTotal = Idea::countPublishedByArchive($this->objArchive);

        if ($intTotal == 0) {
            $this->Template->hasData = false;
            $this->Template->lblNoContent = $GLOBALS['TL_LANG']['MSC']['vb_no_ideas'];
            return;
        }

        $intOffset = 0;
        $intLimit = 0;

        // Split the results
        if ($this->perPage > 0) {
            // Get the current page
            $intPage = \Input::get('page') ? \Input::get('page') : 1;

            // Do not index or cache the page if the page number is outside the range
            if ($intPage < 1 || $intPage > ceil($intTotal/$this->perPage)) {
                global $objPage;
                $objPage->noSearch = 1;
                $objPage->cache = 0;

                // Send a 404 header
                header('HTTP/1.1 404 Not Found');
                return;
            }

            // Set limit and offset
            $intLimit = $this->perPage;
            $intOffset = (max($intPage, 1) - 1) * $this->perPage;

            // Overall limit
            if ($intOffset + $intLimit > $intTotal) {
                $intLimit = $intTotal - $intOffset;
            }

            // Add the pagination menu
            $objPagination = new \Pagination($intTotal, $this->perPage);
            $this->Template->pagination = $objPagination->generate("\n  ");
        }

        $this->Template->hasData = true;
        $objTemplate = new \FrontendTemplate(($this->vb_list_tpl) ? $this->vb_list_tpl : 'votebox_list_default');
        $objTemplate->arrIdeas = $this->prepareIdeas($this->getIdeas($intLimit, $intOffset), $intTotal);
        $objTemplate->readOn = $GLOBALS['TL_LANG']['MSC']['more'];
        $this->Template->content = $objTemplate->parse();
    }


    protected function getIdeas($intLimit=0, $intOffset=0)
    {
        $it = Idea::getTable();
        $vt = Vote::getTable();
        $arrOptions = array();

        switch ($this->vb_orderBy) {
            case 'votes_asc':
                $arrOptions['order'] = "(SELECT COUNT(id) FROM $vt WHERE $it.id=$vt.pid) ASC";
                break;
            case 'votes_desc':
                $arrOptions['order'] = "(SELECT COUNT(id) FROM $vt WHERE $it.id=$vt.pid) DESC";
                break;
            case 'date_asc':
                $arrOptions['order'] = "$it.creation_date ASC";
                break;
            case 'date_desc':
                $arrOptions['order'] = "$it.creation_date DESC";
                break;


        }

        $arrOptions['limit']  = $intLimit;
        $arrOptions['offset'] = $intOffset;

        return Idea::findPublishedByArchive($this->objArchive, $arrOptions);
    }


    protected function prepareIdeas($objIdeas, $intTotal)
    {
        $arrIdeas = array();
        $i = 0;
        while ($objIdeas->next()) {
            $arrIdeas[$objIdeas->id] = $this->prepareIdea($objIdeas->current());

            $strClass = ($i % 2 === 0) ? 'odd' : 'even';

            if ($i === 0) {
                $strClass .= ' first';
            }
            if ($i === ($intTotal - 1)) {
                $strClass .= ' last';
            }

            $arrIdeas[$objIdeas->id]['class'] = $strClass;

            $i++;
        }

        return $arrIdeas;
    }
}