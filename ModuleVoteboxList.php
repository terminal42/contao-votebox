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
 * Class ModuleVoteboxList 
 *
 * @copyright  terminal42 gmbh 2012
 * @author     Yanick Witschi <yanick.witschi@terminal42.ch>
 * @package    Controller
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
		$arrData = $this->getIdeas($this->vb_archive, false, $this->vb_reader_jumpTo);

		if (!$arrData)
		{
			$this->Template->hasData = false;
			$this->Template->lblNoContent = $GLOBALS['TL_LANG']['MSC']['vb_no_ideas'];
			return;
		}

		$this->Template->hasData = true;
		$objTemplate = new FrontendTemplate(($this->vb_list_tpl) ? $this->vb_list_tpl : 'votebox_list_default');
		$objTemplate->arrIdeas = $arrData;
		$this->Template->content = $objTemplate->parse();
	}
}