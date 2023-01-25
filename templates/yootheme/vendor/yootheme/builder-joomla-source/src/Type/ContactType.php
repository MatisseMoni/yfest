<?php

namespace YOOtheme\Builder\Joomla\Source\Type;

use Joomla\CMS\Categories\Categories;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\TagsHelper;
use Joomla\Component\Contact\Site\Helper\RouteHelper;
use function YOOtheme\app;
use YOOtheme\Builder\Joomla\Source\ArticleHelper;
use YOOtheme\Path;
use function YOOtheme\trans;
use YOOtheme\View;

class ContactType
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
                        'filters' => ['limit'],
                    ],
                ],

                'image' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Image'),
                        'filters' => ['limit'],
                    ],
                ],

                'email_to' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Email'),
                        'filters' => ['limit'],
                    ],
                ],

                'con_position' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Contacts Position'),
                        'filters' => ['limit'],
                    ],
                ],

                'address' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Address'),
                        'filters' => ['limit'],
                    ],
                ],

                'suburb' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('City or Suburb'),
                        'filters' => ['limit'],
                    ],
                ],

                'state' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('State or County'),
                        'filters' => ['limit'],
                    ],
                ],

                'postcode' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Postal/ZIP Code'),
                        'filters' => ['limit'],
                    ],
                ],

                'country' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Country'),
                        'filters' => ['limit'],
                    ],
                ],

                'telephone' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Telephone'),
                        'filters' => ['limit'],
                    ],
                ],

                'mobile' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Mobile'),
                        'filters' => ['limit'],
                    ],
                ],

                'fax' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Fax'),
                        'filters' => ['limit'],
                    ],
                ],

                'webpage' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Website'),
                        'filters' => ['limit'],
                    ],
                ],

                'text' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Miscellaneous Information'),
                        'filters' => ['limit'],
                    ],
                ],

                'hits' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Hits'),
                        'filters' => ['limit'],
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

                'user' => [
                    'type' => 'User',
                    'metadata' => [
                        'label' => trans('User'),
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::user',
                    ],
                ],

                'tags' => [
                    'type' => [
                        'listOf' => 'Tag',
                    ],
                    'metadata' => [
                        'label' => trans('Tags'),
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::tags',
                    ],
                ],

                'tagString' => [
                    'type' => 'String',
                    'args' => [
                        'separator' => [
                            'type' => 'String',
                        ],
                        'show_link' => [
                            'type' => 'Boolean',
                        ],
                        'link_style' => [
                            'type' => 'String',
                        ],
                    ],
                    'metadata' => [
                        'label' => trans('Tags'),
                        'arguments' => [
                            'separator' => [
                                'label' => trans('Separator'),
                                'description' => trans('Set the separator between tags.'),
                                'default' => ', ',
                            ],
                            'show_link' => [
                                'label' => trans('Link'),
                                'type' => 'checkbox',
                                'default' => true,
                                'text' => trans('Show link'),
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
                                'enable' => 'arguments.show_link',
                            ],
                        ],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::tagString',
                    ],
                ],

                'created' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Created'),
                        'filters' => ['date'],
                    ],
                ],

                'modified' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Modified'),
                        'filters' => ['date'],
                    ],
                ],

                'link' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Link'),
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::link',
                    ],
                ],

                'articles' => [
                    'type' => [
                        'listOf' => 'Article',
                    ],
                    'metadata' => [
                        'label' => trans('Articles'),
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::articles',
                    ],
                ],
            ],

            'metadata' => [
                'type' => true,
                'label' => trans('Contact'),
            ],
        ];
    }

    public static function category($contact)
    {
        return Categories::getInstance('contact', ['countItems' => true])->get($contact->catid);
    }

    public static function user($contact)
    {
        return Factory::getUser($contact->user_id);
    }

    public static function tags($contact)
    {
        if (!isset($contact->tags)) {
            return (new TagsHelper())->getItemTags('com_contact.contact', $contact->id);
        }

        return $contact->tags->itemTags;
    }

    public static function tagString($contact, array $args)
    {
        $tags = static::tags($contact);
        $args += ['separator' => ', ', 'show_link' => true, 'link_style' => ''];

        return app(View::class)->render(Path::get('../../templates/tags'), compact('tags', 'args'));
    }

    public static function link($contact)
    {
        return RouteHelper::getContactRoute($contact->id, $contact->catid, $contact->language);
    }

    public static function articles($contact)
    {
        if (empty($contact->articles)) {
            return;
        }

        $ids = array_column($contact->articles, 'id');
        $articles = ArticleHelper::get($ids);

        usort($articles, function ($a, $b) use ($ids) {
            return array_search($a->id, $ids) - array_search($b->id, $ids);
        });

        return $articles;
    }
}
