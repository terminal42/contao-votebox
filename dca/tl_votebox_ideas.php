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

\System::loadLanguageFile('tl_member');

/**
 * Table tl_votebox_ideas 
 */
$GLOBALS['TL_DCA']['tl_votebox_ideas'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'                 => 'Table',
        'ptable'                        => 'tl_votebox_archives',
        'ctable'                        => array('tl_votebox_votes'),
        'onload_callback'               => array
        (
            array('tl_votebox_ideas', 'cleanUpVoteTable'),
            array('tl_votebox_ideas', 'adjustPalette')
        ),
        'sql' => array
        (
            'keys' => array
            (
                'id'            => 'primary',
                'pid'           => 'index',
                'member_id'     => 'index'
            )
        ),
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                      => 4,
            'fields'                    => array(
                '(SELECT COUNT(votes.id) AS voteCount FROM tl_votebox_votes AS votes WHERE votes.pid=id)'
            ),
            'flag'                      => 11,
            'headerFields'              => array('title', 'moderate', 'receiver_mail', 'allowComments'),
            'child_record_callback'     => array('tl_votebox_ideas', 'listIdea')
        ),
        'label' => array
        (
            'fields'                    => array(''),
            'format'                    => '%s'
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label'                 => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                  => 'act=select',
                'class'                 => 'header_edit_all',
                'attributes'            => 'onclick="Backend.getScrollOffset();" accesskey="e"'
            )
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'                 => &$GLOBALS['TL_LANG']['tl_votebox_ideas']['edit'],
                'href'                  => 'act=edit',
                'icon'                  => 'edit.gif'
            ),
            'copy' => array
            (
                'label'                 => &$GLOBALS['TL_LANG']['tl_votebox_ideas']['copy'],
                'href'                  => 'act=copy',
                'icon'                  => 'copy.gif'
            ),
            'delete' => array
            (
                'label'                 => &$GLOBALS['TL_LANG']['tl_votebox_ideas']['delete'],
                'href'                  => 'act=delete',
                'icon'                  => 'delete.gif',
                'attributes'            => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
            ),
            'toggle' => array
            (
                'label'                 => &$GLOBALS['TL_LANG']['tl_votebox_ideas']['toggle'],
                'icon'                  => 'visible.gif',
                'attributes'            => 'onclick="Backend.getScrollOffset(); return AjaxRequest.toggleVisibility(this, %s);"',
                'button_callback'       => array('tl_votebox_ideas', 'toggleIcon')
            ),
            'show' => array
            (
                'label'                 => &$GLOBALS['TL_LANG']['tl_votebox_ideas']['show'],
                'href'                  => 'act=show',
                'icon'                  => 'show.gif'
            ),
            'backers' => array
            (
                'label'                 => &$GLOBALS['TL_LANG']['tl_votebox_ideas']['backers'],
                'href'                  => 'table=tl_votebox_votes',
                'icon'                  => 'member.gif'
            )
        )
    ),

    // Palettes
    'palettes' => array
    (
        'default'                       => '{votebox_ideas_legend},title,creation_date,member_id,published,text;'
    ),

    // Fields
    'fields' => array
    (
        'id' => array
        (
            'sql'                 =>  "int(10) unsigned NOT NULL auto_increment",
        ),
        'pid' => array
        (
            'foreignKey'          => 'tl_votebox_archives.title',
            'sql'                 =>  "int(10) unsigned NOT NULL default '0'",
            'relation'            => array('type'=>'belongsTo', 'load'=>'lazy')
        ),
        'tstamp' => array
        (
            'sql'                 =>  "int(10) unsigned NOT NULL default '0'",
        ),
        'title' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_votebox_ideas']['title'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'firstname' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_member']['firstname'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'lastname' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_member']['lastname'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'email' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_member']['email'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50', 'rgxp'=>'friendly'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'creation_date' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_votebox_ideas']['creation_date'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'default'                  => time(),
            'eval'                    => array('mandatory'=>true, 'rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'member_id' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_votebox_ideas']['member_id'],
            'exclude'                 => true,
            'inputType'               => 'select',
            'foreignKey'              => 'tl_member.CONCAT(lastname, \' \', firstname)',
            'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'published' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_votebox_ideas']['published'],
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('tl_class'=>'w50 m12'),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'text' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_votebox_ideas']['text'],
            'exclude'                 => true,
            'inputType'               => 'textarea',
            'eval'                    => array('mandatory'=>true, 'rte'=>'tinyMCE', 'tl_class'=>'clr'),
            'sql'                     => "text NULL"
        )
    )
);

class tl_votebox_ideas extends \Backend
{

    /**
     * List the idea (child_record_callback)
     * @param array
     * @return string
     */
    public function listIdea($arrRow)
    {
        $isPublished = $arrRow['published'] == 1;
        
        $strText = '<div class="cte_type ' . (($isPublished) ? 'published' : 'unpublished') . '"><strong>' . $arrRow['title'] . '</strong> - ' . \System::parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $arrRow['creation_date']) . '</div>';
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
        if (strlen(\Input::get('tid'))) {
            $this->toggleVisibility(\Input::get('tid'), (\Input::get('state') == 1));
            \Controller::redirect(\System::getReferer());
        }

        // Check permissions AFTER checking the tid, so hacking attempts are logged
        if (!\BackendUser::getInstance()->isAdmin && !\BackendUser::getInstance()->hasAccess('tl_votebox_ideas::published', 'alexf')) {
            return '';
        }

        $href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);

        if (!$row['published']) {
            $icon = 'invisible.gif';
        }

        return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.\Image::getHtml($icon, $label).'</a> ';
    }


    /**
     * Disable/enable a user group
     * @param integer
     * @param boolean
     */
    public function toggleVisibility($intId, $blnVisible)
    {
        // Check permissions to edit
        \Input::setGet('id', $intId);
        \Input::setGet('act', 'toggle');

        // Check permissions to publish
        if (!\BackendUser::getInstance()->isAdmin && !\BackendUser::getInstance()->hasAccess('tl_votebox_ideas::published', 'alexf')) {
            $this->log('Not enough permissions to publish/unpublish idea ID "'.$intId.'"', 'tl_votebox_ideas toggleVisibility', TL_ERROR);
            \Controller::redirect('contao/main.php?act=error');
        }

        $this->createInitialVersion('tl_votebox_ideas', $intId);
    
        // Trigger the save_callback
        if (is_array($GLOBALS['TL_DCA']['tl_votebox_ideas']['fields']['published']['save_callback'])) {
            foreach ($GLOBALS['TL_DCA']['tl_votebox_ideas']['fields']['published']['save_callback'] as $callback) {
                $this->import($callback[0]);
                $blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
            }
        }

        // Update the database
        \Database::getInstance()->prepare("UPDATE tl_votebox_ideas SET tstamp=". time() .", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
                       ->execute($intId);

        $this->createNewVersion('tl_votebox_ideas', $intId);
    }


    /**
     * Clean up the vote table for ideas that don't exist anymore
     */
    public function cleanUpVoteTable()
    {
        $arrIdeas = \Database::getInstance()->query('SELECT id FROM tl_votebox_ideas')->fetchEach('id');
        if (empty($arrIdeas)) {
            return;
        }

        \Database::getInstance()->query('DELETE FROM tl_votebox_votes WHERE pid NOT IN (' . implode(',', $arrIdeas) . ')');
    }

    /**
     * Adjusts the palette depending on the archive settings
     * @param \DataContainer
     */
    public function adjustPalette(\DataContainer $dc)
    {
    	$objFindItemsByPk = \Votebox\Model\Idea::findByPk($dc->id);
    	
    	if($objFindItemsByPk)
    	{
    		$objArchive = $objFindItemsByPk->getRelated('pid');
    	}

        if ($objArchive->mode == 'guest') {
            $GLOBALS['TL_DCA']['tl_votebox_ideas']['palettes']['default'] = str_replace(
                'member_id',
                'firstname,lastname,email',
                $GLOBALS['TL_DCA']['tl_votebox_ideas']['palettes']['default']);
        }
    }
}
