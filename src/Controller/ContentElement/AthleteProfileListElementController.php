<?php

declare(strict_types=1);

/*
 * This file is part of Athlete Profile Bundle.
 * 
 * (c) JTS 2021 <website@tlv-germania.de>
 * @license MIT
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/jts22/contao-athlete-profile-bundle
 */

namespace Jts22\ContaoAthleteProfileBundle\Controller\ContentElement;

use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\FilesModel;
use Contao\StringUtil;
use Contao\Template;
use Jts22\ContaoAthleteProfileBundle\Model\AthleteProfileModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AthleteProfileListElementController
 */
class AthleteProfileListElementController extends AbstractContentElementController
{
    /**
     * Generate the content element
     */
    protected function getResponse(Template $template, ContentModel $model, Request $request): ?Response
    {

        $this->addHeadlineToTemplate($template, $model->headline);
        $all_profiles = AthleteProfileModel::findBy('published', 1, array('order' => 'name ASC'))->fetchAll();
        $template->profiles = array_map(function($profile) {
            $pictures = StringUtil::deserialize($profile['pictures']);
            $profile['pictures'] = $pictures;
            $profile['main_image'] = $pictures[0];
            return $profile;
        }, $all_profiles);
        return $template->getResponse();
    }
}
