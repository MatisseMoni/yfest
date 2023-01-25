<?php

namespace YOOtheme\Builder\Joomla\Source\Type;

use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Factory;
use function YOOtheme\trans;

class SiteQueryType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [
            'fields' => [
                'site' => [
                    'type' => 'Site',
                    'metadata' => [
                        'label' => trans('Site'),
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolve',
                    ],
                ],
            ],
        ];
    }

    public static function resolve()
    {
        /** @var SiteApplication $app */
        $app = Factory::getApplication();
        $user = Factory::getUser();

        return [
            'title' => Factory::getConfig()->get('sitename'),
            'page_title' => $app->getParams()->get('page_heading', ''),
            'user' => $user,
            'is_guest' => $user->guest,
        ];
    }
}
