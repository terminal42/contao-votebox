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


class ModuleVoteboxList extends ModuleVotebox
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
			$objTemplate = new BackendTemplate('be_wildcard');
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
        // determine order
        $strOrderBy = '';

        switch ($this->vb_orderBy)
        {
            case 'votes_asc':
                $strOrderBy = 'voteCount ASC';
                break;
            case 'votes_desc':
                $strOrderBy = 'voteCount DESC';
                break;
            case 'date_asc':
                $strOrderBy = 'creation_date ASC';
                break;
            case 'date_desc':
                $strOrderBy = 'creation_date DESC';
                break;
            default:
                if (isset($GLOBALS['TL_HOOKS']['voteBoxListOrderBy']) && is_array($GLOBALS['TL_HOOKS']['voteBoxListOrderBy']))
                {
                    foreach ($GLOBALS['TL_HOOKS']['voteBoxListOrderBy'] as $callback)
                    {
                        $this->import($callback[0]);
                        $strOrderBy = $this->$callback[0]->$callback[1]($this->vb_orderBy);
                    }
                }
        }

        $arrData = $this->getIdeas($this->vb_archive, false, $this->vb_reader_jumpTo, $strOrderBy);

        if (!$arrData)
        {
            $this->Template->hasData = false;
            $this->Template->lblNoContent = $GLOBALS['TL_LANG']['MSC']['vb_no_ideas'];
            return;
        }

        $total = count($arrData);
        $offset = 0;
        $limit = null;

        // Split the results
        if ($this->perPage > 0)
        {
            // Get the current page
            $page = $this->Input->get('page') ? $this->Input->get('page') : 1;

            // Do not index or cache the page if the page number is outside the range
            if ($page < 1 || $page > ceil($total/$this->perPage))
            {
                global $objPage;
                $objPage->noSearch = 1;
                $objPage->cache = 0;

                // Send a 404 header
                header('HTTP/1.1 404 Not Found');
                return;
            }

            // Set limit and offset
            $limit = $this->perPage;
            $offset = (max($page, 1) - 1) * $this->perPage;

            // Overall limit
            if ($offset + $limit > $total)
            {
                $limit = $total - $offset;
            }

            // Add the pagination menu
            $objPagination = new Pagination($total, $this->perPage);
            $this->Template->pagination = $objPagination->generate("\n  ");
        }

        $arrData = $this->getIdeas($this->vb_archive, false, $this->vb_reader_jumpTo, $strOrderBy, array('offset'=>$offset,'limit'=>$limit));

		$this->Template->hasData = true;
		$objTemplate = new FrontendTemplate(($this->vb_list_tpl) ? $this->vb_list_tpl : 'votebox_list_default');
		$objTemplate->arrIdeas = $arrData;
        $objTemplate->readOn = $GLOBALS['TL_LANG']['MSC']['more'];
		$this->Template->content = $objTemplate->parse();
	}
}