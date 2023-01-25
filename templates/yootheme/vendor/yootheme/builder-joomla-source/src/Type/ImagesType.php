<?php

namespace YOOtheme\Builder\Joomla\Source\Type;

use function YOOtheme\trans;

class ImagesType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [
            'fields' => [
                'image_intro' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Intro Image'),
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::image',
                    ],
                ],

                'image_intro_alt' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Intro Image Alt'),
                        'filters' => ['limit'],
                    ],
                ],

                'image_intro_caption' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Intro Image Caption'),
                        'filters' => ['limit'],
                    ],
                ],

                'image_fulltext' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Full Article Image'),
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::image',
                    ],
                ],

                'image_fulltext_alt' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Full Article Image Alt'),
                        'filters' => ['limit'],
                    ],
                ],

                'image_fulltext_caption' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Full Article Image Caption'),
                        'filters' => ['limit'],
                    ],
                ],
            ],
        ];
    }

    public static function image($data, $args, $context, $info)
    {
        $key = $info->fieldName;

        if (!empty($data->$key)) {
            return $data->$key;
        }
    }
}
