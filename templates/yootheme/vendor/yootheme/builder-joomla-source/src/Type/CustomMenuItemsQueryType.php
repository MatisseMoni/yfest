<?php

namespace YOOtheme\Builder\Joomla\Source\Type;

use Joomla\CMS\Factory;
use Joomla\CMS\Menu\MenuItem;
use function YOOtheme\trans;

class CustomMenuItemsQueryType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [
            'fields' => [
                'customMenuItems' => [
                    'type' => [
                        'listOf' => 'MenuItem',
                    ],

                    'args' => [
                        'id' => [
                            'type' => 'String',
                        ],
                        'parent' => [
                            'type' => 'String',
                        ],
                        'heading' => [
                            'type' => 'String',
                        ],
                        'include_heading' => [
                            'type' => 'Boolean',
                            'defaultValue' => true,
                        ],
                        'ids' => [
                            'type' => [
                                'listOf' => 'String',
                            ],
                        ],
                    ],

                    'metadata' => [
                        'label' => trans('Custom Menu Items'),
                        'group' => trans('Custom'),
                        'fields' => [
                            'id' => [
                                'label' => trans('Menu'),
                                'type' => 'select',
                                'defaultIndex' => 0,
                                'options' => [['evaluate' => 'config.menus']],
                            ],
                            'parent' => [
                                'label' => trans('Parent Menu Item'),
                                'description' => trans(
                                    'Menu items are only loaded from the selected parent item.'
                                ),
                                'type' => 'select',
                                'defaultIndex' => 0,
                                'options' => [
                                    ['value' => '', 'text' => trans('Root')],
                                    ['evaluate' => 'this.$root.Menu.menuItems[id]'],
                                ],
                            ],
                            'heading' => [
                                'label' => trans('Limit by Menu Heading'),
                                'type' => 'select',
                                'defaultIndex' => 0,
                                'options' => [
                                    ['value' => '', 'text' => trans('None')],
                                    ['evaluate' => 'this.$root.Menu.headingItems(id, parent)'],
                                ],
                            ],
                            'include_heading' => [
                                'description' => trans(
                                    'Only load menu items from the selected menu heading.'
                                ),
                                'type' => 'checkbox',
                                'default' => true,
                                'text' => trans('Include heading itself'),
                            ],
                            'ids' => [
                                'label' => trans('Select Manually'),
                                'description' => trans(
                                    'Select menu items manually. Use the <kbd>shift</kbd> or <kbd>ctrl/cmd</kbd> key to select multiple menu items.'
                                ),
                                'type' => 'select',
                                'options' => [['evaluate' => 'this.$root.Menu.menuItems[id]']],
                                'attrs' => [
                                    'multiple' => true,
                                    'class' => 'uk-height-small',
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
        $found = false;
        return array_filter(
            Factory::getApplication()
                ->getMenu('site')
                ->getItems('menutype', $args['id']),
            function (MenuItem $item) use ($args, &$found) {
                if (!$item->getParams()->get('menu_show', true)) {
                    return false;
                }

                if (!empty($args['ids'])) {
                    return in_array($item->id, $args['ids']);
                }

                if (!empty($args['heading'])) {
                    if (!$found) {
                        if ($item->id == $args['heading']) {
                            $found = $item;
                            return !empty($args['include_heading']);
                        }
                        return false;
                    }

                    if ($item->parent_id !== $found->parent_id) {
                        return false;
                    }

                    if (!in_array($item->type, ['heading', 'separator'])) {
                        return true;
                    }

                    return $found = false;
                }

                if (!empty($args['parent'])) {
                    return $item->parent_id == $args['parent'];
                }

                return $item->level == '1';
            }
        );
    }
}
