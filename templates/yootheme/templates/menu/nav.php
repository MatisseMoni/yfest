<?php

$hasActiveChild = function ($item) use (&$hasActiveChild) {
    foreach ($item->children ?? [] as $child) {
        if ($child->active || $hasActiveChild($child)) {
            return true;
        }
    }
    return false;
};

foreach ($items as $item) {

    // Config
    $menuposition = '~menu';
    $menuitem = "~theme.menu.items.{$item->id}";

    // Children
    $children = isset($item->children);
    $indention = str_pad("\n", $level + 1, "\t");

    // List item
    $attrs = ['class' => [$item->class ?? '']];

    if ($item->active) {
        $attrs['class'][] = 'uk-active';
    }

    // Title
    $title = $item->title;

    // Parent Icon
    if ($children && $level == 1 && $config('~menu.accordion')) {
        $title .= ' <span uk-nav-parent-icon></span>';
    }

    // Subtitle
    if ($title && $subtitle = $config("$menuitem.subtitle")) {
        $title = "{$title}<div class=\"uk-nav-subtitle\">{$subtitle}</div>";
    }

    // Image
    $image = $view('~theme/templates/menu/image', ['item' => $item]);

    if ($image && $config("$menuitem.image_only")) {
        $title = '';
    }

    // Title Suffix, e.g. cart quantity
    if ($suffix = $config("$menuitem.title-suffix")) {
        $title .= " {$suffix}";
    }

    // Markup
    if ($title && $subtitle && $image) {
        $title = "<div class=\"uk-grid uk-grid-small" . ($level >= 1 && ($config("$menuposition.image_align") == 'center') ? ' uk-flex-middle' : '') . "\"><div class=\"uk-width-auto\">{$image}</div><div class=\"uk-width-expand\">{$title}</div></div>";
    } elseif ($title && $subtitle) {
        $title = "<div>{$title}</div>";
    } else {
        $title = "{$image} {$title}";
    }

    // Heading
    if ($item->type === 'heading') {

        // Divider
        if ($item->divider && !$children) {
            $title = '';
            $attrs['class'][] = 'uk-nav-divider';
        } elseif ($config('~menu.accordion') && $children) {
            $title = "<a href>{$title}</a>";
            if ($level === 1) {
                $attrs['class'][] = 'js-accordion';
                if ($hasActiveChild($item)) {
                    $attrs['class'][] = 'uk-open';
                }
            }
        } else {
            $attrs['class'][] = 'uk-nav-header';
        }

    // Link
    } else {

        $link = [];

        if (isset($item->url)) {
            $link['href'] = $item->url;

            if (str_contains((string) $item->url, '#')) {
                $link['uk-scroll'] = true;
            }

        }

        if (isset($item->target)) {
            $link['target'] = $item->target;
        }

        if (isset($item->anchor_title)) {
            $link['title'] = $item->anchor_title;
        }

        if (isset($item->anchor_rel)) {
            $link['rel'] = $item->anchor_rel;
        }

        if (isset($item->anchor_css)) {
            $link['class'][] = $item->anchor_css;
        }

        $title = "<a{$this->attrs($link)}>{$title}</a>";
    }

    if ($children) {

        $attrs['class'][] = 'uk-parent';

        $children = ['class' => []];

        if ($level == 1) {
            $children['class'][] = 'uk-nav-sub';
        }

        $children = "{$indention}<ul{$this->attrs($children)}>\n{$this->self(['items' => $item->children, 'level' => $level + 1])}</ul>";
    }

    echo "{$indention}<li{$this->attrs($attrs)}>{$title}{$children}</li>";
}
