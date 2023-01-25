<?php

namespace YOOtheme\Builder\Joomla\Source;

use Joomla\CMS\HTML\HTMLHelper;
use YOOtheme\Builder\Source\Type\SiteType;
use YOOtheme\Config;
use function YOOtheme\trans;

class SourceListener
{
    public static function initSource($source)
    {
        $query = [
            Type\SiteQueryType::config(),
            Type\ArticleQueryType::config(),
            Type\CategoryQueryType::config(),
            Type\ContactQueryType::config(),
            Type\ArticlesQueryType::config(),
            Type\SearchQueryType::config(),
            Type\SearchItemsQueryType::config(),
            Type\SmartSearchQueryType::config(),
            Type\SmartSearchItemsQueryType::config(),
            Type\TagsQueryType::config(),
            Type\TagItemsQueryType::config(),
            Type\CustomArticleQueryType::config(),
            Type\CustomArticlesQueryType::config(),
            Type\CustomCategoryQueryType::config(),
            Type\CustomCategoriesQueryType::config(),
            Type\CustomTagQueryType::config(),
            Type\CustomTagsQueryType::config(),
            Type\CustomMenuItemsQueryType::config(),
            Type\CustomUserQueryType::config(),
            Type\CustomUsersQueryType::config(),
        ];

        $types = [
            ['Article', Type\ArticleType::config()],
            ['ArticleEvent', Type\ArticleEventType::config()],
            ['ArticleImages', Type\ArticleImagesType::config()],
            ['ArticleUrls', Type\ArticleUrlsType::config()],
            ['Category', Type\CategoryType::config()],
            ['CategoryParams', Type\CategoryParamsType::config()],
            ['Contact', Type\ContactType::config()],
            ['Event', Type\EventType::config()],
            ['Images', Type\ImagesType::config()],
            ['MenuItem', Type\MenuItemType::config()],
            ['Search', Type\SearchType::config()],
            ['SearchItem', Type\SearchItemType::config()],
            ['SmartSearch', Type\SmartSearchType::config()],
            ['SmartSearchItem', Type\SmartSearchItemType::config()],
            ['Site', SiteType::config()],
            ['Tag', Type\TagType::config()],
            ['TagItem', Type\TagItemType::config()],
            ['User', Type\UserType::config()],
        ];

        foreach ($query as $args) {
            $source->queryType($args);
        }

        foreach ($types as $args) {
            $source->objectType(...$args);
        }
    }

    public static function initCustomizer(Config $config)
    {
        $languages = array_map(function ($lang) {
            return [
                'value' => $lang->value == '*' ? '' : strtolower($lang->value),
                'text' => $lang->text,
            ];
        }, HTMLHelper::_('contentlanguage.existing', true, true));

        $languageField = [
            'label' => trans('Limit by Language'),
            'type' => 'select',
            'defaultIndex' => 0,
            'options' => [['evaluate' => 'config.languages']],
            'show' => '$customizer.languages[\'length\'] > 2 || lang',
        ];

        $templates = [
            'com_content.article' => [
                'label' => trans('Single Article'),
                'fieldset' => [
                    'default' => [
                        'fields' => [
                            'catid' => ($category = [
                                'label' => trans('Limit by Categories'),
                                'description' => trans(
                                    'The template is only assigned to articles from the selected categories. Articles from child categories are not included. Use the <kbd>shift</kbd> or <kbd>ctrl/cmd</kbd> key to select multiple categories.'
                                ),
                                'type' => 'select',
                                'default' => [],
                                'options' => [['evaluate' => 'config.categories']],
                                'attrs' => [
                                    'multiple' => true,
                                    'class' => 'uk-height-small',
                                ],
                            ]),
                            'tag' => ($tag = [
                                'label' => trans('Limit by Tags'),
                                'description' => trans(
                                    'The template is only assigned to articles with the selected tags. Use the <kbd>shift</kbd> or <kbd>ctrl/cmd</kbd> key to select multiple tags.'
                                ),
                                'type' => 'select',
                                'default' => [],
                                'options' => [['evaluate' => 'config.tags']],
                                'attrs' => [
                                    'multiple' => true,
                                    'class' => 'uk-height-small',
                                ],
                            ]),
                            'lang' => $languageField,
                        ],
                    ],
                ],
            ],

            'com_content.category' => [
                'label' => trans('Category Blog'),
                'fieldset' => [
                    'default' => [
                        'fields' => [
                            'catid' =>
                                [
                                    'label' => trans('Limit by Categories'),
                                    'description' => trans(
                                        'The template is only assigned to the selected categories. Child categories are not included. Use the <kbd>shift</kbd> or <kbd>ctrl/cmd</kbd> key to select multiple categories.'
                                    ),
                                ] + $category,
                            'tag' =>
                                [
                                    'description' => trans(
                                        'The template is only assigned to categories with the selected tags. Use the <kbd>shift</kbd> or <kbd>ctrl/cmd</kbd> key to select multiple tags.'
                                    ),
                                ] + $tag,
                            'pages' => [
                                'label' => trans('Limit by Page Number'),
                                'description' => trans(
                                    'The template is only assigned to the selected pages.'
                                ),
                                'type' => 'select',
                                'options' => [
                                    trans('All pages') => '',
                                    trans('First page') => 'first',
                                    trans('All except first page') => 'except_first',
                                ],
                            ],
                            'lang' => $languageField,
                        ],
                    ],
                ],
            ],

            'com_content.featured' => [
                'label' => trans('Featured Articles'),
                'fieldset' => [
                    'default' => [
                        'fields' => [
                            'pages' => [
                                'label' => trans('Limit by Page Number'),
                                'description' => trans(
                                    'The template is only assigned to the selected pages.'
                                ),
                                'type' => 'select',
                                'options' => [
                                    trans('All pages') => '',
                                    trans('First page') => 'first',
                                    trans('All except first page') => 'except_first',
                                ],
                            ],
                            'lang' => $languageField,
                        ],
                    ],
                ],
            ],

            'com_tags.tag' => [
                'label' => trans('Tagged Items'),
                'fieldset' => [
                    'default' => [
                        'fields' => [
                            'pages' => [
                                'label' => trans('Limit by Page Number'),
                                'description' => trans(
                                    'The template is only assigned to the selected pages.'
                                ),
                                'type' => 'select',
                                'options' => [
                                    trans('All pages') => '',
                                    trans('First page') => 'first',
                                    trans('All except first page') => 'except_first',
                                ],
                            ],
                            'lang' => $languageField,
                        ],
                    ],
                ],
            ],

            'com_tags.tags' =>
                [
                    'label' => trans('List All Tags'),
                ] +
                (count($languages) > 1
                    ? [
                        'fieldset' => [
                            'default' => [
                                'fields' => [
                                    'lang' => $languageField,
                                ],
                            ],
                        ],
                    ]
                    : []),

            'com_contact.contact' =>
                [
                    'label' => trans('Single Contact'),
                ] +
                (count($languages) > 1
                    ? [
                        'fieldset' => [
                            'default' => [
                                'fields' => [
                                    'catid' =>
                                        [
                                            'description' => trans(
                                                'The template is only assigned to contacts from the selected categories. Contacts from child categories are not included. Use the <kbd>shift</kbd> or <kbd>ctrl/cmd</kbd> key to select multiple categories.'
                                            ),
                                            'options' => [
                                                ['evaluate' => 'config.com_contact.categories'],
                                            ],
                                        ] + $category,
                                    'tag' =>
                                        [
                                            'description' => trans(
                                                'The template is only assigned to contacts with the selected tags. Use the <kbd>shift</kbd> or <kbd>ctrl/cmd</kbd> key to select multiple tags.'
                                            ),
                                        ] + $tag,
                                    'lang' => $languageField,
                                ],
                            ],
                        ],
                    ]
                    : []),

            'com_search.search' =>
                [
                    'label' => trans('Search'),
                ] +
                (count($languages) > 1
                    ? [
                        'fieldset' => [
                            'default' => [
                                'fields' => [
                                    'lang' => $languageField,
                                ],
                            ],
                        ],
                    ]
                    : []),

            'com_finder.search' =>
                [
                    'label' => trans('Smart Search'),
                ] +
                (count($languages) > 1
                    ? [
                        'fieldset' => [
                            'default' => [
                                'fields' => [
                                    'lang' => $languageField,
                                ],
                            ],
                        ],
                    ]
                    : []),

            'error-404' =>
                [
                    'label' => trans('Error 404'),
                ] +
                (count($languages) > 1
                    ? [
                        'fieldset' => [
                            'default' => [
                                'fields' => [
                                    'lang' => $languageField,
                                ],
                            ],
                        ],
                    ]
                    : []),
        ];

        $config->add('customizer.templates', $templates);

        $config->add(
            'customizer.categories',
            array_map(function ($category) {
                return ['value' => (string) $category->value, 'text' => $category->text];
            }, HTMLHelper::_('category.options', 'com_content'))
        );

        $config->add(
            'customizer.com_contact.categories',
            array_map(function ($category) {
                return ['value' => (string) $category->value, 'text' => $category->text];
            }, HTMLHelper::_('category.options', 'com_contact'))
        );

        $config->add(
            'customizer.menus',
            array_map(function ($menu) {
                return ['value' => (string) $menu->value, 'text' => $menu->text];
            }, HTMLHelper::_('menu.menus', 'com_content'))
        );

        $config->add(
            'customizer.tags',
            array_map(function ($tag) {
                return ['value' => (string) $tag->value, 'text' => $tag->text];
            }, HTMLHelper::_('tag.options'))
        );

        $config->add(
            'customizer.authors',
            array_map(function ($user) {
                return ['value' => (string) $user->value, 'text' => $user->text];
            }, UserHelper::getAuthorList())
        );

        $config->add(
            'customizer.usergroups',
            array_map(function ($group) {
                return ['value' => (string) $group->value, 'text' => $group->text];
            }, HTMLHelper::_('user.groups'))
        );

        $config->add('customizer.languages', $languages);
    }
}
