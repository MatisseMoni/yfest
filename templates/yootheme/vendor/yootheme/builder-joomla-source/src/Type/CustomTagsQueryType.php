<?php

namespace YOOtheme\Builder\Joomla\Source\Type;

use YOOtheme\Builder\Joomla\Source\TagHelper;
use function YOOtheme\trans;

class CustomTagsQueryType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [
            'fields' => [
                'customTags' => [
                    'type' => [
                        'listOf' => 'Tag',
                    ],

                    'args' => [
                        'parent_id' => [
                            'type' => 'String',
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
                        'label' => trans('Custom Tags'),
                        'group' => trans('Custom'),
                        'fields' => [
                            'parent_id' => [
                                'label' => trans('Parent Tag'),
                                'description' => trans(
                                    'Tags are only loaded from the selected parent tag.'
                                ),
                                'type' => 'select',
                                'default' => '0',
                                'options' => [
                                    ['value' => '0', 'text' => trans('Root')],
                                    ['evaluate' => 'config.tags'],
                                ],
                            ],
                            '_offset' => [
                                'description' => trans(
                                    'Set the starting point and limit the number of tags.'
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
                                        'default' => 'a.title',
                                        'options' => [
                                            trans('Alphabetical') => 'a.title',
                                            trans('Hits') => 'a.hits',
                                            trans('Random') => 'rand',
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
        $args += ['parent_id' => 0];
        return TagHelper::query($args);
    }
}
