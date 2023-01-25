<?php

namespace YOOtheme\Builder\Joomla\Source\Type;

use YOOtheme\Builder\Joomla\Source\UserHelper;
use function YOOtheme\trans;

class UserType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [
            'fields' => [
                'name' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Name'),
                        'filters' => ['limit'],
                    ],
                ],

                'username' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Username'),
                        'filters' => ['limit'],
                    ],
                ],

                'email' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Email'),
                        'filters' => ['limit'],
                    ],
                ],

                'registerDate' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Registered'),
                        'filters' => ['date'],
                    ],
                ],

                'lastvisitDate' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Last visit date'),
                        'filters' => ['date'],
                    ],
                ],

                'link' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Link'),
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::link',
                    ],
                ],
            ],

            'metadata' => [
                'type' => true,
                'label' => trans('User'),
            ],
        ];
    }

    public static function link($user)
    {
        return UserHelper::getContactLink($user->id);
    }
}
