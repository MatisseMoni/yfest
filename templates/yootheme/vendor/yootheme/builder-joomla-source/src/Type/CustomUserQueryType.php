<?php

namespace YOOtheme\Builder\Joomla\Source\Type;

use Joomla\CMS\Factory;
use function YOOtheme\trans;

class CustomUserQueryType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [
            'fields' => [
                'customUser' => [
                    'type' => 'User',

                    'args' => [
                        'id' => [
                            'type' => 'String',
                        ],
                    ],

                    'metadata' => [
                        'label' => trans('Custom User'),
                        'group' => trans('Custom'),
                        'fields' => [
                            'id' => [
                                'label' => trans('User'),
                                'type' => 'select-item',
                                'module' => 'com_users',
                                'labels' => ['type' => trans('User')],
                            ],
                        ],
                    ],

                    'extensions' => [
                        'call' => __CLASS__ . '::resolve',
                    ],
                ],
            ],
        ];
    }

    public static function resolve($root, array $args)
    {
        if (empty($args['id'])) {
            return;
        }

        return Factory::getUser($args['id']);
    }
}
