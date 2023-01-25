<?php

namespace YOOtheme\Builder\Joomla\Source\Type;

use Joomla\Component\Tags\Site\Helper\RouteHelper;
use YOOtheme\Builder\Joomla\Source\TagHelper;
use function YOOtheme\trans;

class TagType
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
                        'label' => trans('Title'),
                        'filters' => ['limit'],
                    ],
                ],

                'description' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Description'),
                        'filters' => ['limit'],
                    ],
                ],

                'images' => [
                    'type' => 'Images',
                    'metadata' => [
                        'label' => '',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::images',
                    ],
                ],

                'hits' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Hits'),
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

                'tags' => [
                    'type' => [
                        'listOf' => 'Tag',
                    ],
                    'metadata' => [
                        'label' => trans('Child Tags'),
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::tags',
                    ],
                ],
            ],

            'metadata' => [
                'type' => true,
                'label' => trans('Tag'),
            ],
        ];
    }

    public static function images($tag)
    {
        return json_decode($tag->images);
    }

    public static function link($tag)
    {
        return RouteHelper::getTagRoute("{$tag->id}:{$tag->alias}");
    }

    public static function tags($tag)
    {
        return TagHelper::query(['parent_id' => $tag->id]);
    }
}
