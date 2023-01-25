<?php

namespace YOOtheme\Builder\Joomla\Source\Type;

use YOOtheme\Builder\Joomla\Source\TagHelper;
use function YOOtheme\trans;

class CustomTagQueryType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [
            'fields' => [
                'customTag' => [
                    'type' => 'Tag',

                    'args' => [
                        'id' => [
                            'type' => 'String',
                        ],
                    ],

                    'metadata' => [
                        'label' => trans('Custom Tag'),
                        'group' => trans('Custom'),
                        'fields' => [
                            'id' => [
                                'label' => trans('Tag'),
                                'type' => 'select',
                                'defaultIndex' => 0,
                                'options' => [['evaluate' => 'config.tags']],
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
        $tags = TagHelper::get($args['id']);
        return array_shift($tags);
    }
}
