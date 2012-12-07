<?php
if (!defined('TL_ROOT'))
	die('You cannot access this file directly!');

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
 * @author     Yanick Witschi <yanick.witschi@certo-net.ch>
 * @package    votebox
 * @license    LGPL
 * @filesource
 */

/**
 * Class ModuleVoteboxNewIdea
 *
 * @copyright  terminal42 gmbh 2012
 * @author     Yanick Witschi <yanick.witschi@certo-net.ch>
 * @package    Controller
 */
class ModuleVoteboxNewIdea extends ModuleVotebox {

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_votebox_new_idea';

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate() {
		if (TL_MODE == 'BE') {
			$objTemplate = new BackendTemplate('be_wildcard');
			$objTemplate -> wildcard = '### VOTEBOX: NEW IDEA ###';
			$objTemplate -> title = $this -> headline;
			$objTemplate -> id = $this -> id;
			$objTemplate -> link = $this -> name;
			$objTemplate -> href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this -> id;

			return $objTemplate -> parse();
		}

		return parent::generate();
	}

	/**
	 * Generate module
	 */
	protected function compile()
	{
		$dca = array
		(
			'title' => array
			(
				'label'						=> &$GLOBALS['TL_LANG']['MSC']['form_votebox_new_idea']['title'],
				'inputType'					=> 'text',
				'eval'						=> array('mandatory'=>true)
			),
			'text' => array
			(
				'label'						=> &$GLOBALS['TL_LANG']['MSC']['form_votebox_new_idea']['text'],
				'inputType'					=> 'textarea',
				'eval'						=> array('rte'=>'tinyMCE', 'mandatory'=>true)
			),	
			'captcha' => array
			(
				'label'						=> &$GLOBALS['TL_LANG']['MSC']['form_votebox_new_idea']['captcha'],
				'inputType'					=> 'captcha',
				'eval'						=> array('mandatory'=>true)	
			),	
			'submit' => array
			(
				'label'						=> &$GLOBALS['TL_LANG']['MSC']['form_votebox_new_idea']['submit'],
				'inputType'					=> 'submit'
			)	
		);
			
		$objForm = new Formular('form_votebox_new_idea');
		$objForm->setDCA($dca);
		$objForm->setConfig('attributes',array('tableless'=>true));
		
		if($objForm->isSubmitted() && $objForm->validate())
		{
			$this->processData($objForm->getData());
			
			// redirect or reload
			$this->jumpToOrReload($this->vb_new_idea_jumpTo);
		}
		
		$objTemplate = new FrontendTemplate((strlen($this->vb_new_idea_tpl)) ? $this->vb_new_idea_tpl : 'votebox_new_idea_default');
		$objTemplate->arrData = array
		(
			'newIdeaForm'	=> $objForm->parse()
		);
		
		$this->Template->content = $objTemplate->parse();
	}
	
	
	/**
	 * Process the form data
	 * @param array
	 */
	protected function processData($arrFormData)
	{
		$this->import('FrontendUser', 'Member');
		
		$arrData = array();
		$arrData['pid']				= $this->vb_archive;
		$arrData['tstamp']			= time();
		$arrData['title']			= $arrFormData['title'];
		$arrData['creation_date']	= time();
		$arrData['member_id']		= $this->Member->id;
		$arrData['text']			= $arrFormData['text'];
		
		// send notification if it is moderated
		if($this->arrArchiveData['moderate'] == 1)
		{
			$objEmail = new Email();
			$objEmail->from = $GLOBALS['TL_ADMIN_EMAIL'];
			$objEmail->fromName = $GLOBALS['TL_ADMIN_NAME'];
			$objEmail->subject = 'New idea in votebox to moderate!';
			$objEmail->text = 'There\'s been a new idea submitted to your votebox. Because this votebox is moderated, you should go and see whether you want to publish this idea or not.';
			$objEmail->sendTo($this->arrArchiveData['receiver_mail']);			
		}
		// only publish it directly if it is not moderated
		else
		{
			$arrData['published']	= 1;
		}
		
		// databaaaaaaase
		$this->Database->prepare("INSERT INTO tl_votebox_ideas %s")
					   ->set($arrData)
					   ->execute();
	}
}