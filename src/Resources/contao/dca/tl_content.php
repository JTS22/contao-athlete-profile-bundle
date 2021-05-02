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

/**
 * Content elements
 */
$GLOBALS['TL_DCA']['tl_content']['palettes']['athlete_profile_list_element'] = '{type_legend},type,headline;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['palettes']['athlete_honor_list_element'] = '{type_legend},type,headline;{honors_legend},athlete_profile_page;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['fields']['athlete_profile_page'] = array(
        'exclude'                 => true,
        'inputType'               => 'pageTree',
        'foreignKey'              => 'tl_page.id',
        'eval'                    => array('mandatory' => true, 'fieldType'=>'radio'),
        'sql'                     => "int(10) unsigned NOT NULL default 0",
        'relation'                => array('type'=>'hasOne', 'load'=>'lazy')
);