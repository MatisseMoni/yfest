<?php

namespace YOOtheme\Builder\Joomla\Source\Type;

use Joomla\CMS\Categories\Categories;
use function YOOtheme\trans;

class SearchItemType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [
            'fields' => [
                'title' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Title'),
                        'filters' => ['limit'],
                    ],
                ],

                'text' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Content'),
                        'filters' => ['limit'],
                    ],
                ],

                'created' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Created'),
                        'filters' => ['date'],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::created',
                    ],
                ],

                'category' => [
                    'type' => 'Category',
                    'metadata' => [
                        'label' => trans('Category'),
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::category',
                    ],
                ],

                'href' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Link'),
                    ],
                ],
            ],

            'metadata' => [
                'type' => true,
                'label' => trans('Search Item'),
            ],
        ];
    }

    public static function category($item)
    {
        if (empty($item->catid)) {
            return;
        }

        return Categories::getInstance('content', ['countItems' => true])->get($item->catid);
    }

    public static function created($item)
    {
        if (empty($item->created)) {
            return;
        }

        if (!empty($item->created_raw)) {
            return $item->created_raw;
        }

        return strtotime($item->created) ?: null;
    }
}
