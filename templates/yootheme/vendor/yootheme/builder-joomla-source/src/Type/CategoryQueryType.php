<?php

namespace YOOtheme\Builder\Joomla\Source\Type;

use function YOOtheme\trans;

class CategoryQueryType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [
            'fields' => [
                'category' => [
                    'type' => 'Category',
                    'metadata' => [
                        'label' => trans('Category'),
                        'view' => ['com_content.category'],
                        'group' => trans('Page'),
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolve',
                    ],
                ],
            ],
        ];
    }

    public static function resolve($root)
    {
        if (isset($root['category'])) {
            return $root['category'];
        }
    }
}
