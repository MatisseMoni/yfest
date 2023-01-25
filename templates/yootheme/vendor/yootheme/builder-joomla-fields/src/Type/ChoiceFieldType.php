<?php

namespace YOOtheme\Builder\Joomla\Fields\Type;

use Joomla\CMS\Language\Text;
use function YOOtheme\trans;

class ChoiceFieldType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [
            'fields' => [
                'name' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Name'),
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::name',
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

    public static function name($choice)
    {
        $name = $choice->name ?? ($choice['name'] ?? null);

        if ($name) {
            return Text::_($name);
        }
    }
}
