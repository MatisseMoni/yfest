<?php

namespace YOOtheme\Builder\Joomla\Source\Type;

use function YOOtheme\trans;

class EventType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [
            'fields' => [
                'afterDisplayTitle' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('After Display Title'),
                    ],
                    'extensions' => [
                        'call' => get_called_class() . '::resolve',
                    ],
                ],

                'beforeDisplayContent' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Before Display Content'),
                    ],
                    'extensions' => [
                        'call' => get_called_class() . '::resolve',
                    ],
                ],

                'afterDisplayContent' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('After Display Content'),
                    ],
                    'extensions' => [
                        'call' => get_called_class() . '::resolve',
                    ],
                ],
            ],

            'metadata' => [
                'label' => trans('Events'),
            ],
        ];
    }

    public static function resolve($article, $args, $context, $info)
    {
        $key = $info->fieldName;

        if (isset($article->event->$key)) {
            return $article->event->$key;
        }
    }
}
