<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\HTML\Helpers\Menu;
use Joomla\CMS\Menu\AbstractMenu;

class MenusHelper
{
    public function getMenus()
    {
        return array_map(function ($menu) {
            return [
                'id' => $menu->value,
                'name' => $menu->text,
            ];
        }, Menu::menus());
    }

    public function getItems()
    {
        return array_values(
            array_map(function ($item) {
                return [
                    'id' => (string) $item->id,
                    'title' => $item->title,
                    'level' => $item->level - 1,
                    'menu' => $item->menutype,
                    'parent' => (string) $item->parent_id,
                    'type' => $item->type == 'separator' ? 'heading' : $item->type,
                ];
            }, AbstractMenu::getInstance('site')->getMenu())
        );
    }
}
