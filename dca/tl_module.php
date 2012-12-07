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
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['votebox_list']			= '{title_legend},name,headline,type;{config_legend},perPage,vb_archive;{redirect_legend},vb_reader_jumpTo;{template_legend},vb_list_tpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['votebox_reader']		= '{title_legend},name,headline,type;{config_legend},vb_archive;{template_legend},vb_reader_tpl;{comment_legend},com_template;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['votebox_new_idea']		= '{title_legend},name,headline,type;{config_legend},vb_archive;{redirect_legend},vb_new_idea_jumpTo;{template_legend},vb_new_idea_tpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';

/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['vb_archive'] = array
(
	'label'						=> &$GLOBALS['TL_LANG']['tl_module']['vb_archive'],
	'exclude'					=> true,
	'inputType'					=> 'radio',
	'foreignKey'				=> 'tl_votebox_archives.title',
	'eval'						=> array('mandatory'=>true, 'tl_class'=>'clr'),
);
$GLOBALS['TL_DCA']['tl_module']['fields']['vb_reader_jumpTo'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['vb_reader_jumpTo'],
	'exclude'                 => true,
	'inputType'               => 'pageTree',
	'explanation'             => 'jumpTo',
	'eval'                    => array('fieldType'=>'radio', 'mandatory'=>true)
);
$GLOBALS['TL_DCA']['tl_module']['fields']['vb_list_tpl'] = array
(
	'label'						=> &$GLOBALS['TL_LANG']['tl_module']['vb_list_tpl'],
	'exclude'					=> true,
	'default'					=> 'votebox_list_default',
	'inputType'					=> 'select',
	'options_callback'			=> array('tl_module_votebox', 'getListTemplates'),
	'eval'						=> array('mandatory'=>true, 'tl_class'=>'w50')
);
$GLOBALS['TL_DCA']['tl_module']['fields']['vb_reader_tpl'] = array
(
	'label'						=> &$GLOBALS['TL_LANG']['tl_module']['vb_reader_tpl'],
	'exclude'					=> true,
	'default'					=> 'votebox_reader_default',
	'inputType'					=> 'select',
	'options_callback'			=> array('tl_module_votebox', 'getReaderTemplates'),
	'eval'						=> array('mandatory'=>true, 'tl_class'=>'w50')
);
$GLOBALS['TL_DCA']['tl_module']['fields']['vb_new_idea_jumpTo'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['vb_new_idea_jumpTo'],
	'exclude'                 => true,
	'inputType'               => 'pageTree',
	'explanation'             => 'jumpTo',
	'eval'                    => array('fieldType'=>'radio', 'mandatory'=>true)
);
$GLOBALS['TL_DCA']['tl_module']['fields']['vb_new_idea_tpl'] = array
(
	'label'						=> &$GLOBALS['TL_LANG']['tl_module']['vb_new_idea_tpl'],
	'exclude'					=> true,
	'default'					=> 'votebox_new_idea_default',
	'inputType'					=> 'select',
	'options_callback'			=> array('tl_module_votebox', 'getNewIdeaTemplates'),
	'eval'						=> array('mandatory'=>true, 'tl_class'=>'w50')
);


class tl_module_votebox extends Backend
{
	/**
	 * Initialize the object
	 */
	public function __construct()
	{
		parent::__construct();
	}
	

	/**
	 * Return list templates as array
	 * @param DataContainer
	 * @return array
	 */
	public function getListTemplates(DataContainer $dc)
	{
		$intPid = $dc->activeRecord->pid;

		if ($this->Input->get('act') == 'overrideAll')
		{
			$intPid = $this->Input->get('id');
		}

		return $this->getTemplateGroup('votebox_list_', $intPid);
	}
	

	/**
	 * Return reader templates as array
	 * @param DataContainer
	 * @return array
	 */
	public function getReaderTemplates(DataContainer $dc)
	{
		$intPid = $dc->activeRecord->pid;

		if ($this->Input->get('act') == 'overrideAll')
		{
			$intPid = $this->Input->get('id');
		}

		return $this->getTemplateGroup('votebox_reader_', $intPid);
	}
	

	/**
	 * Return new idea templates as array
	 * @param DataContainer
	 * @return array
	 */
	public function getNewIdeaTemplates(DataContainer $dc)
	{
		$intPid = $dc->activeRecord->pid;

		if ($this->Input->get('act') == 'overrideAll')
		{
			$intPid = $this->Input->get('id');
		}

		return $this->getTemplateGroup('votebox_new_idea_', $intPid);
	}
}