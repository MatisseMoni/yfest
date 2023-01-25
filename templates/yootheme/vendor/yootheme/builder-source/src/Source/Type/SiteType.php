<?php

namespace YOOtheme\Builder\Source\Type;

use function YOOtheme\trans;

class SiteType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [
            'fields' => [
                'title' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Site Title'),
                        'filters' => ['limit'],
                    ],
                ],

                'page_title' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Page Title'),
                        'filters' => ['limit'],
                    ],
                ],

                'user' => [
                    'type' => 'User',
                    'metadata' => [
                        'label' => trans('Current User'),
                    ],
                ],

                'is_guest' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => trans('Guest User'),
                    ],
                ],
            ],

            'metadata' => [
                'type' => true,
                'label' => trans('Site'),
            ],
        ];
    }
}
