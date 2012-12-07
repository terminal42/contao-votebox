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
 * Table tl_votebox_ideas 
 */
$GLOBALS['TL_DCA']['tl_votebox_ideas'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'					  => 'tl_votebox_archives'
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 4,
			'fields'                  => array('(SELECT COUNT(votes.id) AS voteCount FROM tl_votebox_votes AS votes WHERE votes.pid=id)'),
			'flag'                    => 11,
			'headerFields'            => array('title', 'moderate', 'receiver_mail', 'allowComments'),
			'child_record_callback'	  => array('tl_votebox_ideas', 'listIdea')
		),
		'label' => array
		(
			'fields'                  => array(''),
			'format'                  => '%s'
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_votebox_ideas']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_votebox_ideas']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_votebox_ideas']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_votebox_ideas']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset(); return AjaxRequest.toggleVisibility(this, %s);"',
				'button_callback'     => array('tl_votebox_ideas', 'toggleIcon')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_votebox_ideas']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => '{votebox_ideas_legend},title,creation_date,member_id,published,text;'
	),

	// Fields
	'fields' => array
	(
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_votebox_ideas']['title'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50')
		),
		'creation_date' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_votebox_ideas']['creation_date'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'default'				  => time(),
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard')
		),
		'member_id' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_votebox_ideas']['member_id'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'foreignKey'			  => 'tl_member.CONCAT(lastname, \' \', firstname)',
			'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50')
		),
		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_votebox_ideas']['published'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50 m12')
		),
		'text' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_votebox_ideas']['text'],
			'exclude'                 => true,
			'inputType'               => 'textarea',
			'eval'                    => array('mandatory'=>true, 'rte'=>'tinyMCE', 'tl_class'=>'clr')
		)
	)
);

class tl_votebox_ideas extends Backend
{
	/**
	 * Initialize the object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}
	

	/**
	 * List the idea (child_record_callback)
	 * @param array
	 * @return string
	 */
	public function listIdea($arrRow)
	{
		$isPublished = $arrRow['published'] == 1;
		
		$strText = '<div class="cte_type ' . (($isPublished) ? 'published' : 'unpublished') . '"><strong>' . $arrRow['title'] . '</strong> - ' . $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $arrRow['creation_date']) . '</div>';
		$strText .= '<div class="limit_height h64 block">' . $arrRow['text'] . '</div>';
		return $strText;
	}


	/**
	 * Return the "toggle visibility" button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen($this->Input->get('tid')))
		{
			$this->toggleVisibility($this->Input->get('tid'), ($this->Input->get('state') == 1));
			$this->redirect($this->getReferer());
		}

		// Check permissions AFTER checking the tid, so hacking attempts are logged
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_votebox_ideas::published', 'alexf'))
		{
			return '';
		}

		$href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);

		if (!$row['published'])
		{
			$icon = 'invisible.gif';
		}

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}


	/**
	 * Disable/enable a user group
	 * @param integer
	 * @param boolean
	 */
	public function toggleVisibility($intId, $blnVisible)
	{
		// Check permissions to edit
		$this->Input->setGet('id', $intId);
		$this->Input->setGet('act', 'toggle');

		// Check permissions to publish
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_votebox_ideas::published', 'alexf'))
		{
			$this->log('Not enough permissions to publish/unpublish idea ID "'.$intId.'"', 'tl_votebox_ideas toggleVisibility', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$this->createInitialVersion('tl_votebox_ideas', $intId);
	
		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_votebox_ideas']['fields']['published']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_votebox_ideas']['fields']['published']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_votebox_ideas SET tstamp=". time() .", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
					   ->execute($intId);

		$this->createNewVersion('tl_votebox_ideas', $intId);
	}
}