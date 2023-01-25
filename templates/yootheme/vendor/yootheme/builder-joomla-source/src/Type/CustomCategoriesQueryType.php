<?php

namespace YOOtheme\Builder\Joomla\Source\Type;

use Joomla\CMS\Categories\Categories;
use function YOOtheme\trans;

class CustomCategoriesQueryType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [
            'fields' => [
                'customCategories' => [
                    'type' => [
                        'listOf' => 'Category',
                    ],

                    'args' => [
                        'catid' => [
                            'type' => 'String',
                        ],
                        'offset' => [
                            'type' => 'Int',
                        ],
                        'limit' => [
                            'type' => 'Int',
                        ],
                        'order' => [
                            'type' => 'String',
                        ],
                        'order_direction' => [
                            'type' => 'String',
                        ],
                    ],

                    'metadata' => [
                        'label' => trans('Custom Categories'),
                        'group' => trans('Custom'),
                        'fields' => [
                            'catid' => [
                                'label' => trans('Parent Category'),
                                'description' => trans(
                                    'Categories are only loaded from the selected parent category.'
                                ),
                                'type' => 'select',
                                'default' => '0',
                                'options' => [
                                    ['value' => '0', 'text' => trans('Root')],
                                    ['evaluate' => 'config.categories'],
                                ],
                            ],
                            '_offset' => [
                                'description' => trans(
                                    'Set the starting point and limit the number of categories.'
                                ),
                                'type' => 'grid',
                                'width' => '1-2',
                                'fields' => [
                                    'offset' => [
                                        'label' => trans('Start'),
                                        'type' => 'number',
                                        'default' => 0,
                                        'modifier' => 1,
                                        'attrs' => [
                                            'min' => 1,
                                            'required' => true,
                                        ],
                                    ],
                                    'limit' => [
                                        'label' => trans('Quantity'),
                                        'type' => 'limit',
                                        'default' => 10,
                                        'attrs' => [
                                            'min' => 1,
                                        ],
                                    ],
                                ],
                            ],
                            '_order' => [
                                'type' => 'grid',
                                'width' => '1-2',
                                'fields' => [
                                    'order' => [
                                        'label' => trans('Order'),
                                        'type' => 'select',
                                        'default' => 'ordering',
                                        'options' => [
                                            trans('Alphabetical') => 'title',
                                            trans('Category Order') => 'ordering',
                                            trans('Random') => 'rand',
                                        ],
                                    ],
                                    'order_direction' => [
                                        'label' => trans('Direction'),
                                        'type' => 'select',
                                        'default' => 'ASC',
                                        'options' => [
                                            trans('Ascending') => 'ASC',
                                            trans('Descending') => 'DESC',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],

                    'extensions' => [
                        'call' => __CLASS__ . '::resolve',
                    ],
                ],
            ],
        ];
    }

    public static function resolve($root, array $args)
    {
        if (
            $category = Categories::getInstance('content', ['countItems' => true])->get(
                $args['catid']
            )
        ) {
            $categories = $category->getChildren();

            if ($args['order'] === 'rand') {
                shuffle($categories);
            } elseif ($args['order']) {
                $prop = $args['order'] === 'ordering' ? 'lft' : $args['order'];
                usort($categories, function ($article, $other) use ($prop) {
                    return strnatcmp($article->$prop, $other->$prop);
                });
            }

            if ($args['offset'] || $args['limit']) {
                $categories = array_slice(
                    $categories,
                    (int) $args['offset'],
                    (int) $args['limit'] ?: null
                );
            }

            if ($args['order_direction'] === 'DESC') {
                $categories = array_reverse($categories);
            }

            return $categories;
        }
    }
}
