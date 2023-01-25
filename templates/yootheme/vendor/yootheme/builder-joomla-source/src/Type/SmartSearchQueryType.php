<?php

namespace YOOtheme\Builder\Joomla\Source\Type;

use function YOOtheme\trans;

class SmartSearchQueryType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [
            'fields' => [
                'smartSearch' => [
                    'type' => 'SmartSearch',
                    'metadata' => [
                        'label' => trans('Search'),
                        'view' => ['com_finder.search'],
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
        if (isset($root['search'])) {
            return $root['search'];
        }
    }
}
