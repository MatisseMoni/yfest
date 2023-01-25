<?php

namespace YOOtheme\Builder\Joomla\Source\Type;

use Joomla\CMS\Categories\Categories;
use Joomla\CMS\Factory;
use function YOOtheme\app;
use YOOtheme\Path;
use function YOOtheme\trans;
use YOOtheme\View;

class TagItemType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [
            'fields' => [
                'core_title' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Title'),
                        'filters' => ['limit'],
                    ],
                ],

                'content' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Content'),
                        'filters' => ['limit'],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::content',
                    ],
                ],

                'teaser' => [
                    'type' => 'String',
                    'args' => [
                        'show_excerpt' => [
                            'type' => 'Boolean',
                        ],
                    ],
                    'metadata' => [
                        'label' => trans('Teaser'),
                        'arguments' => [
                            'show_excerpt' => [
                                'label' => trans('Excerpt'),
                                'description' => trans(
                                    'Display the excerpt field if it has content, otherwise the content. To use an excerpt field, create a custom field with the name excerpt.'
                                ),
                                'type' => 'checkbox',
                                'default' => true,
                                'text' => trans('Prefer excerpt over regular text'),
                            ],
                        ],
                        'filters' => ['limit'],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::teaser',
                    ],
                ],

                'core_publish_up' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Published'),
                        'filters' => ['date'],
                    ],
                ],

                'created_time' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Created'),
                        'filters' => ['date'],
                    ],
                ],

                'modified_time' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Modified'),
                        'filters' => ['date'],
                    ],
                ],

                'metaString' => [
                    'type' => 'String',
                    'args' => [
                        'format' => [
                            'type' => 'String',
                        ],
                        'separator' => [
                            'type' => 'String',
                        ],
                        'link_style' => [
                            'type' => 'String',
                        ],
                        'show_publish_date' => [
                            'type' => 'Boolean',
                        ],
                        'show_author' => [
                            'type' => 'Boolean',
                        ],
                        'show_taxonomy' => [
                            'type' => 'String',
                        ],
                        'parent_id' => [
                            'type' => 'String',
                        ],
                        'date_format' => [
                            'type' => 'String',
                        ],
                    ],
                    'metadata' => [
                        'label' => trans('Meta'),
                        'arguments' => [
                            'format' => [
                                'label' => trans('Format'),
                                'description' => trans(
                                    'Display the meta text in a sentence or a horizontal list.'
                                ),
                                'type' => 'select',
                                'default' => 'list',
                                'options' => [
                                    trans('List') => 'list',
                                    trans('Sentence') => 'sentence',
                                ],
                            ],
                            'separator' => [
                                'label' => trans('Separator'),
                                'description' => trans('Set the separator between fields.'),
                                'default' => '|',
                                'enable' => 'arguments.format === "list"',
                            ],
                            'link_style' => [
                                'label' => trans('Link Style'),
                                'description' => trans('Set the link style.'),
                                'type' => 'select',
                                'default' => '',
                                'options' => [
                                    'Default' => '',
                                    'Muted' => 'link-muted',
                                    'Text' => 'link-text',
                                    'Heading' => 'link-heading',
                                    'Reset' => 'link-reset',
                                ],
                            ],
                            'show_publish_date' => [
                                'label' => trans('Display'),
                                'description' => trans('Show or hide fields in the meta text.'),
                                'type' => 'checkbox',
                                'default' => true,
                                'text' => trans('Show date'),
                            ],
                            'show_author' => [
                                'type' => 'checkbox',
                                'default' => true,
                                'text' => trans('Show author'),
                            ],
                            'show_taxonomy' => [
                                'type' => 'select',
                                'default' => 'category',
                                'options' => [
                                    trans('Hide Term List') => '',
                                    trans('Show Category') => 'category',
                                    trans('Show Tags') => 'tag',
                                ],
                            ],
                            'parent_id' => [
                                'label' => trans('Parent Tag'),
                                'description' => trans(
                                    'Tags are only loaded from the selected parent tag.'
                                ),
                                'type' => 'select',
                                'default' => '0',
                                'show' => 'arguments.show_taxonomy === "tag"',
                                'options' => [
                                    ['value' => '0', 'text' => trans('Root')],
                                    ['evaluate' => 'config.tags'],
                                ],
                            ],
                            'date_format' => [
                                'label' => trans('Date Format'),
                                'description' => trans(
                                    'Select a predefined date format or enter a custom format.'
                                ),
                                'type' => 'data-list',
                                'default' => '',
                                'options' => [
                                    'Aug 6, 1999 (M j, Y)' => 'M j, Y',
                                    'August 06, 1999 (F d, Y)' => 'F d, Y',
                                    '08/06/1999 (m/d/Y)' => 'm/d/Y',
                                    '08.06.1999 (m.d.Y)' => 'm.d.Y',
                                    '6 Aug, 1999 (j M, Y)' => 'j M, Y',
                                    'Tuesday, Aug 06 (l, M d)' => 'l, M d',
                                ],
                                'enable' => 'arguments.show_publish_date',
                                'attrs' => [
                                    'placeholder' => 'Default',
                                ],
                            ],
                        ],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::metaString',
                    ],
                ],

                'images' => [
                    'type' => 'Images',
                    'metadata' => [
                        'label' => '',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::images',
                    ],
                ],

                'link' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Link'),
                    ],
                ],

                'event' => [
                    'type' => 'ArticleEvent',
                    'metadata' => [
                        'label' => trans('Events'),
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::event',
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

                'author' => [
                    'type' => 'User',
                    'metadata' => [
                        'label' => trans('Author'),
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::author',
                    ],
                ],

                'content_type_title' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Content Type Title'),
                    ],
                ],
            ],

            'metadata' => [
                'type' => true,
                'label' => trans('Tag Item'),
            ],
        ];
    }

    public static function content($item)
    {
        return $item->text;
    }

    public static function teaser($item, $args)
    {
        $args += ['show_excerpt' => true];

        if ($args['show_excerpt'] && !empty($item->jcfields['excerpt']->rawvalue)) {
            return $item->jcfields['excerpt']->rawvalue;
        }

        return $item->text;
    }

    public static function metaString($item, array $args)
    {
        if ($item->type_alias !== 'com_content.article') {
            return;
        }

        $args += [
            'format' => 'list',
            'separator' => '|',
            'link_style' => '',
            'show_publish_date' => true,
            'show_author' => true,
            'show_taxonomy' => 'category',
            'date_format' => '',
        ];

        $props = [
            'id',
            'author',
            'contact_link',
            'core_catid' => 'catid',
            'category_title',
            'core_created_user_id' => 'created_by',
            'core_created_by_alias' => 'created_by_alias',
            'core_publish_up' => 'publish_up',
        ];

        $article = new \stdClass();
        foreach ($props as $field => $prop) {
            if (isset($item->$prop)) {
                $article->$prop = $item->$prop;
            } elseif (isset($item->$field)) {
                $article->$prop = $item->$field;
            } else {
                $article->$prop = null;
            }
        }

        $tags = $args['show_taxonomy'] === 'tag' ? ArticleType::tags($article, $args) : null;

        return app(View::class)->render(
            Path::get('../../templates/meta'),
            compact('article', 'tags', 'args')
        );
    }

    public static function images($item)
    {
        return json_decode($item->core_images);
    }

    public static function author($item)
    {
        $user = Factory::getUser($item->core_created_user_id);

        if ($item->core_created_by_alias) {
            $user = clone $user;
            $user->name = $item->core_created_by_alias;
        }

        return $user;
    }

    public static function category($item)
    {
        return isset($item->catid)
            ? Categories::getInstance('content', ['countItems' => true])->get($item->catid)
            : null;
    }

    public static function event($item)
    {
        return isset($item->event) ? $item : null;
    }
}
