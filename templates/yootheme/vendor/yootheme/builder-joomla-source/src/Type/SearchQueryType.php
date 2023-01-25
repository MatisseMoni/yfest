<?php

namespace YOOtheme\Builder\Joomla\Source\Type;

use function YOOtheme\trans;

class SearchQueryType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [
            'fields' => [
                'search' => [
                    'type' => 'Search',
                    'metadata' => [
                        'label' => trans('Search'),
                        'view' => ['com_search.search'],
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
