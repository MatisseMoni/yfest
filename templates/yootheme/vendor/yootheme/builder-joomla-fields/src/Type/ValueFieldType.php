<?php

namespace YOOtheme\Builder\Joomla\Fields\Type;

use function YOOtheme\trans;

class ValueFieldType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [
            'fields' => [
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
