<?php

// Config
$mobile = '~theme.mobile';
$header = '~theme.header';
$modules = "~theme.modules.{$module->id}";

$class = [];
$badge = [];
$title = [];

$layout = $config("$header.layout");

// Set type in Joomla dummy module for template preview
if (empty($module->type)) {
    $module->type = '';
}

// Raw positions
if ($position == 'debug' ||
    ($position == 'navbar-split' && $module->type == 'menu') ||
    (in_array($position, ['logo', 'logo-mobile']) && $module->type == 'logo') ||
    $module->type == 'dialog-toggle'
) {
    echo $module->content;
    return;
}

// Navbar positions
if (in_array($position, ['navbar', 'navbar-push', 'navbar-mobile', 'header-mobile']) ||
    (in_array($position, ['header', 'header-split']) && str_starts_with($layout, 'horizontal')) ||
    ($position == 'logo' && preg_match('/^(horizontal|stacked-center-split-[ab])/', $layout)) ||
    $position == 'logo-mobile'
) {

    // Menu
    if ($module->type == 'menu' && in_array($config("$modules.menu_type"), ['', 'nav'])) {
        echo $module->content;
        return;
    }

    // Search
    if (in_array($module->type, ['search', 'finder']) &&
        ((!str_ends_with($position, '-mobile') && $config("$header.search_style") == 'modal') ||
            (str_ends_with($position, '-mobile') && $config("$mobile.header.search_style") == 'modal'))
    ) {
        echo $module->content;
        return;
    // Else
    } else {
        $class[] = 'uk-navbar-item';
    }

// No style positions
} elseif (preg_match('/^(toolbar-(left|right)|logo|header(-split)?|dialog(-push|-mobile(-push)?)?)$/', $position)) {

    $class[] = 'uk-panel';

// Style positions (Top, Bottom, Sidebar, Builder 1-6)
} else {

    $class[] = $config("$modules.style") ? "uk-card uk-card-body uk-{$config("$modules.style")}" : 'uk-panel';

}

// Class
if ($cls = $config("$modules.class")) {
    $class = array_merge($class, (array) $cls);
}

// Visibility
if ($visibility = $config("$modules.visibility")) {
    $class[] = "uk-visible@{$visibility}";
}

// Grid + sidebar positions (and any custom position)
if (!preg_match('/^(toolbar-(left|right)|logo(-mobile)?|navbar(-split|-push|-mobile)?|header(-split|-mobile)?|debug)$/', $position)) {

    // Title?
    if ($config("$modules.showtitle") && !empty($module->title)) {

        $title['class'] = [];
        $title_element = $config("$modules.title_tag", 'h3');

        // Style?
        $title['class'][] = $config("$modules.title_style") ? "uk-{$config("$modules.title_style")}" : '';
        $title['class'][] = $config("$modules.style") && !$config("$modules.title_style") ? 'uk-card-title' : '';

        // Decoration?
        $title['class'][] = $config("$modules.title_decoration") ? "uk-heading-{$config("$modules.title_decoration")}" : '';

        // Header Class?
        $title['class'][] = $config("$modules.title_class", '');

    }

    // Text alignment
    if ($config("$modules.text_align") && $config("$modules.text_align") != 'justify' && $config("$modules.text_align_breakpoint")) {
        $class[] = "uk-text-{$config("$modules.text_align")}@{$config("$modules.text_align_breakpoint")}";
        if ($config("$modules.text_align_fallback")) {
            $class[] = "uk-text-{$config("$modules.text_align_fallback")}";
        }
    } elseif ($config("$modules.text_align")) {
        $class[] = "uk-text-{$config("$modules.text_align")}";
    }

}

// List options
$list_class = [];
if ($config("$modules.is_list")) {
    $list_class[] = 'tm-child-list';

    // List Style?
    if ($config("$modules.list_style")) {
        $list_class[] = "tm-child-list-{$config("$modules.list_style")}";
    }

    // Link Style?
    if ($config("$modules.link_style")) {
        $list_class[] = "uk-link-{$config("$modules.link_style")}";
    }
}

// Grid positions
if (preg_match('/^(top|bottom|builder-\d+)$/', $position)) {

    // Max Width?
    if ($config("$modules.maxwidth")) {
        $class[] = "uk-width-{$config("$modules.maxwidth")}";

        // Center?
        if ($config("$modules.maxwidth_align")) {
            $class[] = 'uk-margin-auto';
        }

    }

}

?>

<div<?= $this->attrs(compact('class'), ['class' => $list_class], $module->attrs) ?>>

    <?php if ($title) : ?>

        <<?= $title_element ?><?= $this->attrs($title) ?>>

        <?php if ($config("$modules.title_decoration") == 'line') : ?>
        <span><?= $module->title ?></span>
        <?php else: ?>
        <?= $module->title ?>
        <?php endif ?>

        </<?= $title_element ?>>

    <?php endif ?>

    <?= $module->content ?>

</div>
