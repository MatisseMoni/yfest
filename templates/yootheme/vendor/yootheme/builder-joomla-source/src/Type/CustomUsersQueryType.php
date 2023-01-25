<?php

namespace YOOtheme\Builder\Joomla\Source\Type;

use YOOtheme\Builder\Joomla\Source\UserHelper;
use function YOOtheme\trans;

class CustomUsersQueryType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [
            'fields' => [
                'customUsers' => [
                    'type' => [
                        'listOf' => 'User',
                    ],

                    'args' => [
                        'groups' => [
                            'type' => [
                                'listOf' => 'String',
                            ],
                        ],
                        'offset' => [
                            'type' => 'Int',
                        ],
                        'limit' => [
                            'type' => 'Int',
                        ],
                        'order' => [
                            'type' => 'String',
                        ],
                        'order_direction' => [
                            'type' => 'String',
                        ],
                    ],

                    'metadata' => [
                        'label' => trans('Custom Users'),
                        'group' => trans('Custom'),
                        'fields' => [
                            'groups' => [
                                'label' => trans('User Group'),
                                'description' => trans(
                                    'Users are only loaded from the selected user groups. Use the <kbd>shift</kbd> or <kbd>ctrl/cmd</kbd> key to select multiple user groups.'
                                ),
                                'type' => 'select',
                                'attrs' => [
                                    'multiple' => true,
                                ],
                                'options' => [['evaluate' => 'config.usergroups']],
                            ],
                            '_offset' => [
                                'description' => trans(
                                    'Set the starting point and limit the number of users.'
                                ),
                                'type' => 'grid',
                                'width' => '1-2',
                                'fields' => [
                                    'offset' => [
                                        'label' => trans('Start'),
                                        'type' => 'number',
                                        'default' => 0,
                                        'modifier' => 1,
                                        'attrs' => [
                                            'min' => 1,
                                            'required' => true,
                                        ],
                                    ],
                                    'limit' => [
                                        'label' => trans('Quantity'),
                                        'type' => 'limit',
                                        'default' => 10,
                                        'attrs' => [
                                            'min' => 1,
                                        ],
                                    ],
                                ],
                            ],
                            '_order' => [
                                'type' => 'grid',
                                'width' => '1-2',
                                'fields' => [
                                    'order' => [
                                        'label' => trans('Order'),
                                        'type' => 'select',
                                        'default' => 'a.name',
                                        'options' => [
                                            trans('Alphabetical') => 'a.name',
                                            trans('Register date') => 'a.registerDate',
                                            trans('Last visit date') => 'a.lastvisitDate',
                                        ],
                                    ],
                                    'order_direction' => [
                                        'label' => trans('Direction'),
                                        'type' => 'select',
                                        'default' => 'ASC',
                                        'options' => [
                                            trans('Ascending') => 'ASC',
                                            trans('Descending') => 'DESC',
                                        ],
                                    ],
                                ],
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
        return UserHelper::query($args);
    }
}
