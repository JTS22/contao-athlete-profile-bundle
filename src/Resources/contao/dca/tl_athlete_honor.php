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
use Contao\Input;
use Contao\Config;
use Contao\Image;
use Contao\CoreBundle\Exception\AccessDeniedException;
use Contao\DataContainer;
use Contao\Versions;
use Contao\BackendUser;
use Contao\StringUtil;
use Jts22\ContaoAthleteProfileBundle\Model\AthleteHonorModel;

/**
 * Table tl_athlete_profile
 */
$GLOBALS['TL_DCA']['tl_athlete_honor'] = array(

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
    'list'        => array(
        'sorting'           => array(
            'mode'        => 2,
            'fields'      => array('name'),
            'flag'        => 1,
            'panelLayout' => 'filter;sort,search,limit'
        ),
        'label'             => array(
            'fields' => array('name', 'category', 'year'),
            'format' => '%s',
            'showColumns' => true
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
                'label' => &$GLOBALS['TL_LANG']['tl_athlete_honor']['edit'],
                'href'  => 'act=edit',
                'icon'  => 'edit.gif'
            ),
            'copy'   => array(
                'label' => &$GLOBALS['TL_LANG']['tl_athlete_honor']['copy'],
                'href'  => 'act=copy',
                'icon'  => 'copy.gif'
            ),
            'delete' => array(
                'label'      => &$GLOBALS['TL_LANG']['tl_athlete_honor']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ),
			'toggle' => array(
				'icon'                => 'visible.svg',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'     => array('tl_athlete_honor', 'toggleIcon')
			),
            'show'   => array(
                'label'      => &$GLOBALS['TL_LANG']['tl_athlete_honor']['show'],
                'href'       => 'act=show',
                'icon'       => 'show.gif',
                'attributes' => 'style="margin-right:3px"'
            ),
        )
    ),
    // Palettes
    'palettes'    => array(
        'default'      => '{profile_legend},name,category,year,picture,text,profile,published'
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
		'category'    => array(
            'inputType' => 'text',
            'search'    => true,
            'filter'    => true,
            'sorting'   => true,
            'flag'      => 1,
            'eval'      => array('mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'),
            'sql'       => ['type' => 'string', 'length' => 255, 'default' => '']
        ),
        'year' => array(
            'inputType' => 'text',
            'search'    => true,
            'filter'    => true,
            'sorting'   => true,
            'flag'      => 11,
            'eval'      => array('mandatory' => true, 'maxlength' => 4, 'rgxp' => 'natural', 'tl_class' => 'w50'),
            'sql'       => ['type' => 'string', 'length' => 8, 'default' => '']
        ),
        'picture'          => array(
            'inputType' => 'fileTree',
            'eval'      => array(
                            'mandatory' => true,
                            'extensions'=> Config::get('validImageTypes'),
                            'fieldType'=>'checkbox',
                            'files'=> true,
                            'tl_class' => 'w50'),
            'sql'       => "blob NULL"
        ),
        'text' => array(
            'inputType' => 'textarea',
            'eval'      => array('rte' => 'tinyMCE', 'tl_class' => 'clr'),
            'sql'       => "mediumtext NULL"
        ),
        'profile'          => array(
            'inputType' => 'picker',
            'foreignKey' => 'tl_athlete_profile.id',
            'relation' => array('type' => 'hasOne', 'table' => 'tl_athlete_profile'),
            'eval'      => array('tl_class' => 'clr'),
            'sql'       => ['type' => 'string', 'length' => 255, 'default' => '']
        ),
        'published'     => array(
            'exclude'   => true,
			'filter'    => true,
			'inputType' => 'checkbox',
			'eval'      => array('doNotCopy'=>true),
			'sql'       => "char(1) NOT NULL default ''"
		)
    )
);

/**
 * Class tl_athlete_honor
 */
class tl_athlete_honor extends Backend
{

    /**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import(BackendUser::class, 'User');
	}

    /**
	 * Disable/enable a user group
	 *
	 * @param integer       $intId
	 * @param boolean       $blnVisible
	 * @param DataContainer $dc
	 *
	 * @throws AccessDeniedException
	 */
	public function toggleVisibility($intId, $blnVisible, DataContainer $dc=null)
	{
		// Set the ID and action
		Input::setGet('id', $intId);
		Input::setGet('act', 'toggle');

		if ($dc)
		{
			$dc->id = $intId; // see #8043
		}

		// Trigger the onload_callback
		// if (is_array($GLOBALS['TL_DCA']['tl_athlete_profile']['config']['onload_callback'] ?? null))
		// {
		// 	foreach ($GLOBALS['TL_DCA']['tl_athlete_profile']['config']['onload_callback'] as $callback)
		// 	{
		// 		if (is_array($callback))
		// 		{
		// 			$this->import($callback[0]);
		// 			$this->{$callback[0]}->{$callback[1]}($dc);
		// 		}
		// 		elseif (is_callable($callback))
		// 		{
		// 			$callback($dc);
		// 		}
		// 	}
		// }

		$objRow = $this->Database->prepare("SELECT * FROM tl_athlete_honor WHERE id=?")
								 ->limit(1)
								 ->execute($intId);

		if ($objRow->numRows < 1)
		{
			throw new AccessDeniedException('Invalid page ID ' . $intId . '.');
		}

		// Set the current record
		if ($dc)
		{
			$dc->activeRecord = $objRow;
		}

		$objVersions = new Versions('tl_athlete_honor', $intId);
		$objVersions->initialize();

		// Trigger the save_callback
		// if (is_array($GLOBALS['TL_DCA']['tl_athlete_profile']['fields']['published']['save_callback'] ?? null))
		// {
		// 	foreach ($GLOBALS['TL_DCA']['tl_athlete_profile']['fields']['published']['save_callback'] as $callback)
		// 	{
		// 		if (is_array($callback))
		// 		{
		// 			$this->import($callback[0]);
		// 			$blnVisible = $this->{$callback[0]}->{$callback[1]}($blnVisible, $dc);
		// 		}
		// 		elseif (is_callable($callback))
		// 		{
		// 			$blnVisible = $callback($blnVisible, $dc);
		// 		}
		// 	}
		// }

		$time = time();

		// Update the database
		$this->Database->prepare("UPDATE tl_athlete_honor SET tstamp=$time, published='" . ($blnVisible ? '1' : '') . "' WHERE id=?")
					   ->execute($intId);

		if ($dc)
		{
			$dc->activeRecord->tstamp = $time;
			$dc->activeRecord->published = ($blnVisible ? '1' : '');
		}

		// Trigger the onsubmit_callback
		// if (is_array($GLOBALS['TL_DCA']['tl_athlete_profile']['config']['onsubmit_callback'] ?? null))
		// {
		// 	foreach ($GLOBALS['TL_DCA']['tl_athlete_profile']['config']['onsubmit_callback'] as $callback)
		// 	{
		// 		if (is_array($callback))
		// 		{
		// 			$this->import($callback[0]);
		// 			$this->{$callback[0]}->{$callback[1]}($dc);
		// 		}
		// 		elseif (is_callable($callback))
		// 		{
		// 			$callback($dc);
		// 		}
		// 	}
		// }

		$objVersions->create();

		if ($dc)
		{
			$dc->invalidateCacheTags();
		}
	}



    /**
	 * Return the "toggle visibility" button
	 *
	 * @param array  $row
	 * @param string $href
	 * @param string $label
	 * @param string $title
	 * @param string $icon
	 * @param string $attributes
	 *
	 * @return string
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (Input::get('tid'))
		{
			$this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1), (@func_get_arg(12) ?: null));
			$this->redirect($this->getReferer());
		}

		// Check permissions AFTER checking the tid, so hacking attempts are logged
		if (!$this->User->hasAccess('tl_athlete_honor::published', 'alexf'))
		{
			return '';
		}

		$href .= '&amp;tid=' . $row['id'] . '&amp;state=' . ($row['published'] ? '' : 1);

		if (!$row['published'])
		{
			$icon = 'invisible.svg';
		}

		if (!$this->User->hasAccess($row['type'], 'alpty') || (AthleteHonorModel::findById($row['id'])) === null)
		{
			return Image::getHtml($icon) . ' ';
		}

		return '<a href="' . $this->addToUrl($href) . '" title="' . StringUtil::specialchars($title) . '"' . $attributes . '>' . Image::getHtml($icon, $label, 'data-state="' . ($row['published'] ? 1 : 0) . '"') . '</a> ';
	}
}
