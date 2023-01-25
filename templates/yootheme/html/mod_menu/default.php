<?php

namespace YOOtheme;

defined('_JEXEC') or die;

[$view, $config] = app(View::class, Config::class);

$items = [];
$parents = [];

// Add module class
$attrs = ['class' => [$params['class_sfx']]];

foreach ($list as $_item) {

    $item = clone $_item;
    $item_params = $_item->getParams();
    $alias_id = $item_params['aliasoptions'];

    // set active state
    if ($item->id == $active_id || ($item->type == 'alias' && $alias_id == $active_id)) {
        $item->active = true;
    }

    if (in_array($item->id, $path)) {
        $item->active = true;
    }

    if ($item->active) {
        foreach ($parents as $parent) {
            $parent->active = true;
        }
    }

    // normalize menu item
    $item->url = $item->flink;
    $item->target = ($item->browserNav == 1 || $item->browserNav == 2) ? '_blank' : '';
    $item->active = $item->active ?: false;
    $item->divider = $item->type === 'separator';
    $item->type = $item->type == 'separator' ? 'heading' : $item->type;
    $item->class = "item-{$item->id}";

    // update menu item params
    $config->update("~theme.menu.items.{$item->id}", function ($values) use ($item_params, $view) {
        return array_merge($values ?: [], [
            'image' => empty($values['image']) ? $item_params['menu_image'] : $values['image'],
            'image_only' => empty($values['image_only']) ? !$item_params['menu_text'] : $values['image_only'],
            'image_classes' => $item_params['menu_image_css'],
        ]);
    });

    // add to parent
    if ($parent = end($parents)) {
        $parent->children[] = $item;
    } else {
        $items[] = $item;
    }

    // set/remove parent
    if ($item->deeper) {
        $parents[] = $item;
        $item->children = [];
    } elseif ($item->shallower) {
        array_splice($parents, -$item->level_diff);
    }
}

// Add "module-{id}" to <ul> on navbar position if no tag_id is specified
// See Navbar positions in module.php which don't render module markup
$layout = $config('~theme.header.layout');
if ($module->id && is_numeric($module->id) && !$params['tag_id']

    && (in_array($module->position, ['navbar', 'navbar-push','navbar-mobile', 'header-mobile']) ||
    (in_array($module->position, ['header', 'header-split']) && str_starts_with($layout, 'horizontal')) ||
    ($module->position == 'logo' && preg_match('/^(horizontal|stacked-center-split-[ab])/', $layout)) ||
    $module->position == 'logo-mobile')

    && in_array($config("~theme.modules.{$module->id}.menu_type"), ['', 'nav'])
) {
    $params['tag_id'] = "module-{$module->id}";
}

$settings = [];
foreach ($config("~theme.modules.{$module->id}", []) as $key => $value) {
    if (str_starts_with($key, 'menu_')) {
        $settings[substr($key, 5)] = $value;
    }
}

// set menu config
$config->set('~menu', [
    'tag_id' => $params['tag_id'],
    'position' => $module->position,
] + $settings);

echo $view('~theme/templates/menu/menu', ['items' => $items, 'attrs' => $attrs]);
