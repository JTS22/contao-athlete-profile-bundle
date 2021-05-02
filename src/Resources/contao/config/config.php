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

use Jts22\ContaoAthleteProfileBundle\Model\AthleteHonorModel;
use Jts22\ContaoAthleteProfileBundle\Model\AthleteProfileModel;

/**
 * Backend modules
 */
$GLOBALS['BE_MOD']['content']['athlete_profiles'] = array(
    'tables' => array('tl_athlete_profile')
);
$GLOBALS['BE_MOD']['content']['athlete_honor'] = array(
    'tables' => array('tl_athlete_honor')
);

/**
 * Models
 */
$GLOBALS['TL_MODELS']['tl_athlete_profile'] = AthleteProfileModel::class;
$GLOBALS['TL_MODELS']['tl_athlete_honor'] = AthleteHonorModel::class;
