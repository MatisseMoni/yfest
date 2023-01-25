<?php

namespace YOOtheme\Builder\Joomla\Source\Type;

use function YOOtheme\trans;

class SearchType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [
            'fields' => [
                'searchword' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Search Word'),
                    ],
                ],

                'total' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => trans('Item Count'),
                    ],
                ],

                'error' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Error'),
                    ],
                ],
            ],

            'metadata' => [
                'type' => true,
                'label' => trans('Search'),
            ],
        ];
    }
}
