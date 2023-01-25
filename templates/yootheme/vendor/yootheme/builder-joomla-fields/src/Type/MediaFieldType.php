<?php

namespace YOOtheme\Builder\Joomla\Fields\Type;

use function YOOtheme\trans;

class MediaFieldType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [
            'fields' =>
                [
                    'imagefile' => [
                        'type' => 'String',
                        'metadata' => [
                            'label' => trans('Url'),
                        ],
                        'extensions' => [
                            'call' => __CLASS__ . '::imagefile',
                        ],
                    ],
                ] +
                (version_compare(JVERSION, '4.0', '>')
                    ? [
                        'alt_text' => [
                            'type' => 'String',
                            'metadata' => [
                                'label' => trans('Alt'),
                            ],
                        ],
                    ]
                    : []),
        ];
    }

    public static function imagefile($data, $args, $context, $info)
    {
        $key = $info->fieldName;

        if (!empty($data[$key])) {
            return $data[$key];
        }
    }
}
