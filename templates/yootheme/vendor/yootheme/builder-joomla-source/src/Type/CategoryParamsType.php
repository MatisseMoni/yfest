<?php

namespace YOOtheme\Builder\Joomla\Source\Type;

use function YOOtheme\trans;

class CategoryParamsType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [
            'fields' => [
                'image' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Image'),
                    ],
                ],

                'image_alt' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Image Alt'),
                        'filters' => ['limit'],
                    ],
                ],
            ],
        ];
    }
}
