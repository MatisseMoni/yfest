<?php

use YOOtheme\Arr;

foreach ($items as $item) {

    // Config
    $navbar = '~theme.navbar';
    $menuposition = '~menu';
    $menuitem = "~theme.menu.items.{$item->id}";

    // Children
    $children = !empty($item->children) || !empty($item->builder);
    $indention = str_pad("\n", $level + 1, "\t");

    // List item
    $attrs = ['class' => [$item->class ?? '']];

    if ($item->active) {
        $attrs['class'][] = 'uk-active';
    }

    // Title
    $title = $item->title;

    // Parent Icon
    if ($children && $config("$navbar.parent_icon")) {
        $title .= ' <span uk-navbar-parent-icon></span>';
    }

    // Subtitle
    if ($title && $subtitle = $config("$menuitem.subtitle")) {
        $title = "{$title}<div class=\"" . ($level == 1 ? 'uk-navbar-subtitle' : 'uk-nav-subtitle') . "\">{$subtitle}</div>";
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

        if (!$children && $level == 1) {
            continue;
        }

        if ($level > 1 && $item->divider && !$children) {
            $title = '';
            $attrs['class'][] = 'uk-nav-divider';
        } elseif ($children) {
            $link = [];
            if (isset($item->anchor_css)) {
                $link['class'][] = $item->anchor_css;
                $link['role'][] = 'button';
            }
            $title = "<a{$this->attrs($link)}>{$title}</a>";
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

        if ($image) {
            $link['class'][] = 'uk-preserve-width';
        }

        $title = "<a{$this->attrs($link)}>{$title}</a>";

    }

    if ($children) {

        $attrs['class'][] = 'uk-parent';

        $children = [
            'class' => [],
            'style' => []
        ];

        if ($level == 1) {

            $children['class'][] = 'uk-navbar-dropdown';
            if ($config("$menuitem.dropdown.size")) {
                $children['class'][] = !$config("$navbar.dropbar") ? 'uk-navbar-dropdown-large' : '';
                $children['class'][] = $config("$navbar.dropbar") ? 'uk-navbar-dropdown-dropbar-large' : '';
            }

            // Use `hover` instead of `hover, click` so dropdown can't be closed on click if in hover mode
            $mode = $item->type === 'heading' ? ($config("$navbar.dropdown_click") ? 'click' : 'hover') : false;

            $stretch = $config("$menuitem.dropdown.stretch");

            if ($mode || $config("$menuitem.dropdown.align") || $stretch || $config("$menuitem.dropdown.size")) {

                $align = $config("$menuitem.dropdown.align") ?: $config("$navbar.dropdown_align");

                $children['uk-drop'] = json_encode(array_filter([
                    // Default
                    'clsDrop' => 'uk-navbar-dropdown',
                    'flip' => 'false',
                    'container' => $config("$navbar.sticky") ? '.tm-header > [uk-sticky]' : '.tm-header',
                    'target-x' => $config("$navbar.dropdown_target") ? '.tm-header .uk-navbar' : null,
                    'target-y' => $config("$navbar.dropbar") ? '.tm-header .uk-navbar-container' : null,
                    // New
                    'mode' => $mode,
                    'pos' => "bottom-{$align}",
                    'stretch' => $stretch ? 'x' : null,
                    'boundary' => $stretch ? ".tm-header .uk-{$stretch}" : null,
                ]));
            }

            if (!$stretch) {
                $children['style'][] = $config("$menuitem.dropdown.width") ? "width: {$config("$menuitem.dropdown.width")}px;" : '';
            }

            if (isset($item->builder)) {

                if (!$stretch && !$config("$menuitem.dropdown.width")) {
                    $children['style'][] = 'width: 400px;';
                }

                if ($config("$menuitem.dropdown.padding_remove_horizontal")) {
                    if ($config("$navbar.dropbar")) {
                        $children['style'][] = '--uk-position-viewport-offset: 0;';
                    } else {
                        $children['class'][] = 'uk-padding-remove-horizontal';
                    }

                }
                if ($config("$menuitem.dropdown.padding_remove_vertical")) {
                    $children['class'][] = 'uk-padding-remove-vertical';
                }

                $children = "{$indention}<div{$this->attrs($children)}>{$item->builder}</div>";

            } else {

                $columns = Arr::columns($item->children, $config("$menuitem.dropdown.columns", 1));
                $columnsCount = count($columns);

                $wrapper = [
                    'class' => [
                        'uk-navbar-dropdown-grid',
                        "uk-child-width-1-{$columnsCount}",
                    ],
                    'uk-grid' => true,
                ];

                if ($columnsCount > 1 && !$stretch) {
                    $children['class'][] = "uk-navbar-dropdown-width-{$columnsCount}";
                }

                $nav_style = $config("$menuitem.dropdown.nav_style") == 'secondary' ? 'uk-nav-secondary' : 'uk-navbar-dropdown-nav';
                $columnsStr = '';
                foreach ($columns as $column) {
                    $columnsStr .= "<div><ul class=\"uk-nav {$nav_style}\">\n{$this->self(['items' => $column, 'level' => $level + 1])}</ul></div>";
                }

                $children = "{$indention}<div{$this->attrs($children)}><div{$this->attrs($wrapper)}>{$columnsStr}</div></div>";
            }

        } else {

            if ($level == 2) {
                $children['class'][] = 'uk-nav-sub';
            }

            $children = "{$indention}<ul{$this->attrs($children)}>\n{$this->self(['items' => $item->children, 'level' => $level + 1])}</ul>";
        }
    }

    echo "{$indention}<li{$this->attrs($attrs)}>{$title}{$children}</li>";
}
