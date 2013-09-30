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

    protected function prepareIdea($objIdea)
    {
        $arrIdea = $objIdea->row();
        $arrIdea['creation_date_formatted'] = \System::parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $objIdea->creation_date);
        $arrIdea['reader_url']              = $objIdea->getUrl();
        $arrIdea['voteCount']               = $objIdea->getVotes();
        // @todo implement
        //$arrData[$k]['hasVoted'] = \Votebox\Votebox::hasVoted($arrRow['id'], \FrontendUser::getInstance()->id);

        return $arrIdea;
    }
}

