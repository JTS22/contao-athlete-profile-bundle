<?php

/*
 * This file is part of Athlete Profile Bundle.
 * 
 * (c) JTS 2021 <website@tlv-germania.de>
 * @license MIT
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/jts22/contao-athlete-profile-bundle
 */

use Contao\Backend;
use Contao\DC_Table;
use Contao\Input;
use Contao\Config;

/**
 * Table tl_athlete_profile
 */
$GLOBALS['TL_DCA']['tl_athlete_profile'] = array(

    // Config
    'config'      => array(
        'dataContainer'    => 'Table',
        'enableVersioning' => true,
        'sql'              => array(
            'keys' => array(
                'id' => 'primary'
            )
        ),
    ),
    'edit'        => array(
        'buttons_callback' => array(
            array('tl_athlete_profile', 'buttonsCallback')
        )
    ),
    'list'        => array(
        'sorting'           => array(
            'mode'        => 2,
            'fields'      => array('name'),
            'flag'        => 1,
            'panelLayout' => 'filter;sort,search,limit'
        ),
        'label'             => array(
            'fields' => array('title'),
            'format' => '%s',
        ),
        'global_operations' => array(
            'all' => array(
                'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'       => 'act=select',
                'class'      => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            )
        ),
        'operations'        => array(
            'edit'   => array(
                'label' => &$GLOBALS['TL_LANG']['tl_athlete_profile']['edit'],
                'href'  => 'act=edit',
                'icon'  => 'edit.gif'
            ),
            'copy'   => array(
                'label' => &$GLOBALS['TL_LANG']['tl_athlete_profile']['copy'],
                'href'  => 'act=copy',
                'icon'  => 'copy.gif'
            ),
            'delete' => array(
                'label'      => &$GLOBALS['TL_LANG']['tl_athlete_profile']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ),
            'show'   => array(
                'label'      => &$GLOBALS['TL_LANG']['tl_athlete_profile']['show'],
                'href'       => 'act=show',
                'icon'       => 'show.gif',
                'attributes' => 'style="margin-right:3px"'
            ),
        )
    ),
    // Palettes
    'palettes'    => array(
        'default'      => '{test_legend},name,pictures,year_of_birth,favorite_disciplines,tlv_entry_date,trainer,special_tlv_moment,tlv_appreciation,biggest_achievement,other_interests,goals,published'
    ),
    // Fields
    'fields'      => array(
        'id'             => array(
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp'         => array(
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'name'          => array(
            'inputType' => 'text',
            'search'    => true,
            'filter'    => true,
            'sorting'   => true,
            'flag'      => 1,
            'eval'      => array('mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'),
            'sql'       => ['type' => 'string', 'length' => 255, 'default' => '']
        ),
        'pictures'          => array(
            'inputType' => 'fileTree',
            'eval'      => array(
                            'mandatory' => true,
                            'multiple' => true,
                            'isSortable'=> true,
                            'extensions'=> Config::get('validImageTypes'),
                            'fieldType'=>'checkbox',
                            'orderField'=>'pictures_order',
                            'files'=> true,
                            'tl_class' => 'clr'),
            'sql'       => "blob NULL"
        ),
        'pictures_order' => array(
			'label'     => &$GLOBALS['TL_LANG']['MSC']['sortOrder'],
			'sql'       => "blob NULL"
		),
        'year_of_birth' => array(
            'inputType' => 'text',
            'search'    => true,
            'filter'    => true,
            'sorting'   => true,
            'flag'      => 11,
            'eval'      => array('maxlength' => 4, 'rgxp' => 'natural', 'tl_class' => 'w50'),
            'sql'       => ['type' => 'string', 'length' => 8, 'default' => '']
        ),
        'favorite_disciplines'          => array(
            'inputType' => 'text',
            'eval'      => array('maxlength' => 255, 'tl_class' => 'w50'),
            'sql'       => ['type' => 'string', 'length' => 255, 'default' => '']
        ),
        'tlv_entry_date'=> array(
            'inputType' => 'text',
            'eval'      => array('maxlength' => 16, 'tl_class' => 'w50'),
            'sql'       => ['type' => 'string', 'length' => 16, 'default' => '']
        ),
        'trainer'       => array(
            'inputType' => 'text',
            'search'    => true,
            'filter'    => true,
            'sorting'   => true,
            'flag'      => 1,
            'eval'      => array('maxlength' => 255, 'tl_class' => 'w50'),
            'sql'       => ['type' => 'string', 'length' => 255, 'default' => '']
        ),
        'special_tlv_moment'  => array(
            'inputType' => 'textarea',
            'eval'      => array('tl_class' => 'clr'),
            'sql'       => 'text NOT NULL'
        ),
        'tlv_appreciation'  => array(
            'inputType' => 'textarea',
            'eval'      => array('tl_class' => 'clr'),
            'sql'       => 'text NOT NULL'
        ),
        'biggest_achievement'  => array(
            'inputType' => 'textarea',
            'eval'      => array('tl_class' => 'clr'),
            'sql'       => 'text NOT NULL'
        ),
        'other_interests'  => array(
            'inputType' => 'textarea',
            'eval'      => array('tl_class' => 'clr'),
            'sql'       => 'text NOT NULL'
        ),
        'goals'  => array(
            'inputType' => 'textarea',
            'eval'      => array('tl_class' => 'clr'),
            'sql'       => 'text NOT NULL'
        ),
        'published'     => array(
			'filter'    => true,
			'inputType' => 'checkbox',
			'eval'      => array('doNotCopy'=>true),
			'sql'       => "char(1) NOT NULL default ''"
		)
    )
);

/**
 * Class tl_athlete_profile
 */
class tl_athlete_profile extends Backend
{
    /**
     * @param $arrButtons
     * @param  DC_Table $dc
     * @return mixed
     */
    public function buttonsCallback($arrButtons, DC_Table $dc)
    {
        if (Input::get('act') === 'edit')
        {
            $arrButtons['customButton'] = '<button type="submit" name="customButton" id="customButton" class="tl_submit customButton" accesskey="x">' . $GLOBALS['TL_LANG']['tl_athlete_profile']['customButton'] . '</button>';
        }

        return $arrButtons;
    }
}
