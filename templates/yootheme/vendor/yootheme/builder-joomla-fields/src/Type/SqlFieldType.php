<?php

namespace YOOtheme\Builder\Joomla\Fields\Type;

use function YOOtheme\trans;

class SqlFieldType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [
            'fields' => [
                'text' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Text'),
                    ],
                ],

                'value' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Value'),
                    ],
                ],
            ],
        ];
    }
}
