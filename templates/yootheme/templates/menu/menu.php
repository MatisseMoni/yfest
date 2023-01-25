<?php

// Config
use YOOtheme\Arr;
use YOOtheme\Builder;
use function YOOtheme\app;

$mobile = '~theme.mobile';
$header = '~theme.header';
$dialog = '~theme.dialog';

// Menu ID
$attrs['id'] = $config('~menu.tag_id');

$hasHeaderParent = function ($items) {
    return Arr::some($items, function ($item) {
        return $item->type == 'heading' && !empty($item->children) && isset($item->url) && ($item->url === '#' || $item->url === '');
    });
};

// Set `nav` menu_type to default in header positions
if ($config('~menu.type') == 'nav' && preg_match('/^(toolbar-(left|right)|logo(-mobile)?|navbar(-split|-push|-mobile)?|header(-split|-mobile)?)$/', $config('~menu.position'))) {
    $config->set('~menu.type', '');
}

if (in_array($config('~menu.type'), ['iconnav', 'subnav', 'nav'])) {

    $type = $config('~menu.type');

// Default on Navbar
} elseif (in_array($config('~menu.position'), ['navbar', 'navbar-split', 'navbar-push', 'navbar-mobile'])) {

    $type = 'navbar';

    if (in_array($config('~menu.position'), ['navbar', 'navbar-split', 'navbar-push']) && in_array($config("$header.layout"), ['stacked-center-split-a', 'stacked-center-split-b'])) {

        // Split Auto
        $index = $config("$header.split_index") ?: ceil(count($items) / 2);

        if ($config('~menu.position') == 'navbar-split') {
            $items = array_slice($items, 0, $index);
        } else {
            $items = array_slice($items, $index);
        }
    }

// Default on Header
} elseif (in_array($config('~menu.position'), ['header', 'header-split', 'header-mobile'])) {

    if (in_array($config('~menu.position'), ['header', 'header-split']) && str_starts_with($config("$header.layout"), 'stacked')) {

        $type = 'subnav';

    // Render in navbar
    } else {

        $type = 'navbar';

    }

// Default on Toolbar and Logo
} elseif (in_array($config('~menu.position'), ['toolbar-left', 'toolbar-right', 'logo', 'logo-mobile'])) {

    $type = 'subnav';

// Default on Dialog
} elseif (in_array($config('~menu.position'), ['dialog', 'dialog-push', 'dialog-mobile', 'dialog-mobile-push'])) {

    $type = 'nav';

// Default on Sidebar, Top, Bottom, Builder 1-6
} else {

    $type = 'nav';

}

// Navbar
if ($type == 'navbar') {

    $attrs['class'][] = 'uk-navbar-nav';

}

// Subav
if ($type == 'subnav') {

    $attrs['class'][] = 'uk-subnav';
    $attrs['class'][] =  $config('~menu.divider') ? 'uk-subnav-divider' : '';

}

// Iconnav
if ($type == 'iconnav') {

    $attrs['class'][] = 'uk-iconnav';

}

// Nav
if ($type == 'nav') {

    $attrs['class'][] = 'uk-nav';
    $attrs['class'][] = "uk-nav-{$config('~menu.style')}";
    $attrs['class'][] =  $config('~menu.divider') ? 'uk-nav-divider' : '';

    // Accordion menu
    if ($hasHeaderParent($items)) {
        $config->set('~menu.accordion', true);
        $attrs['class'][] = 'uk-nav-accordion';
        $attrs['uk-nav'] = 'targets: > .js-accordion';
    }

}

// Dialog Center
if ((in_array($config('~menu.position'), ['dialog', 'dialog-push']) && $config("$dialog.text_center")) ||
    (in_array($config('~menu.position'), ['dialog-mobile', 'dialog-mobile-push']) && $config("$mobile.dialog.text_center"))) {

    if (in_array($type, ['subnav', 'iconnav'])) {
        $attrs['class'][] = 'uk-flex-center';
    } elseif ($type == 'nav') {
        $attrs['class'][] = 'uk-nav-center';
    }
}

// Builder
if ($type !== 'nav') {

    // Store menu config in case builder renders a menu module/widget
    $menuConfig = $config('~menu');

    $builder = app(Builder::class);
    foreach ($items as $item) {
        if ($content = $config("~theme.menu.items.{$item->id}.content")) {
            if ($config($recursionKey = "builderRecursion{$item->id}")) {
                continue;
            }

            $config->set($recursionKey, true);

            $item->builder = $builder->render(
                json_encode($content),
                ['prefix' => "menu-item-{$item->id}"]
            ) ?: null;

            $config->del($recursionKey);
        }
    }

    $menuConfig = $config->set('~menu', $menuConfig);
}

?>

<ul<?= $this->attrs($attrs) ?>>
    <?= $view("~theme/templates/menu/{$type}", ['items' => $items, 'level' => 1]) ?>
</ul>
