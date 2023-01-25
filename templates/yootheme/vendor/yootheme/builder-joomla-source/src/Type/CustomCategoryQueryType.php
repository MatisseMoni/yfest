<?php

namespace YOOtheme\Builder\Joomla\Source\Type;

use Joomla\CMS\Categories\Categories;
use function YOOtheme\trans;

class CustomCategoryQueryType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [
            'fields' => [
                'customCategory' => [
                    'type' => 'Category',

                    'args' => [
                        'id' => [
                            'type' => 'String',
                        ],
                    ],

                    'metadata' => [
                        'label' => trans('Custom Category'),
                        'group' => trans('Custom'),
                        'fields' => [
                            'id' => [
                                'label' => trans('Category'),
                                'type' => 'select',
                                'defaultIndex' => 0,
                                'options' => [['evaluate' => 'config.categories']],
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
        return Categories::getInstance('content', ['countItems' => true])->get($args['id']);
    }
}
