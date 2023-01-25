<?php

namespace YOOtheme\Builder\Joomla\Source\Type;

use function YOOtheme\trans;

class SmartSearchType
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
            ],

            'metadata' => [
                'type' => true,
                'label' => trans('Search'),
            ],
        ];
    }
}
