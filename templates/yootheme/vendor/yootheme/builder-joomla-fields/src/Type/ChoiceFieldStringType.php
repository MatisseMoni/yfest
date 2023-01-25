<?php

namespace YOOtheme\Builder\Joomla\Fields\Type;

use Joomla\CMS\Language\Text;
use function YOOtheme\trans;

class ChoiceFieldStringType
{
    /**
     * @return array
     */
    public static function config()
    {
        $field = [
            'type' => 'String',
            'args' => [
                'separator' => [
                    'type' => 'String',
                ],
            ],
            'metadata' => [
                'arguments' => [
                    'separator' => [
                        'label' => trans('Separator'),
                        'description' => trans('Set the separator between fields.'),
                        'default' => ', ',
                    ],
                ],
            ],
        ];

        return [
            'fields' => [
                'name' => array_merge_recursive($field, [
                    'metadata' => [
                        'label' => trans('Names'),
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveNames',
                    ],
                ]),

                'value' => array_merge_recursive($field, [
                    'metadata' => [
                        'label' => trans('Values'),
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveValues',
                    ],
                ]),
            ],
        ];
    }

    public static function resolveNames($item, $args)
    {
        $args += ['separator' => ', '];

        $result = array_map(function ($item) {
            return Text::_($item);
        }, array_column($item, 'name'));

        return join($args['separator'], $result);
    }

    public static function resolveValues($item, $args)
    {
        $args += ['separator' => ', '];

        return join($args['separator'], array_column($item, 'value'));
    }
}
