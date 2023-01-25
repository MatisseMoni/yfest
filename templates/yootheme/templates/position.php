<?php

$items = array_filter($items, function ($item) { return !empty(trim($item->content ?? '')); });

$render = function ($items, $wrap = null) use ($view, $name) {
    $output = [];
    foreach ($items as $index => $item) {
        $widget = $view('~theme/templates/module', ['index' => $index, 'module' => $item, 'position' => $name]);
        $output[] = $wrap ? $wrap([], $widget) : $widget;
    }
    return implode('', $output);
};

// Blank
if (empty($style)) {
    echo $render($items);
    return;
}

// Cell
if ($style == 'cell') {
    echo $render($items, $this->el('div'));
    return;
}

// Logo and Header Position in Stacked Center layouts
if ($style === 'grid-center') {

    if (count($items) > 1) {
        $attrs = [
            'class' => ['uk-grid-medium uk-child-width-auto uk-flex-center uk-flex-middle'],
            'uk-grid' => true,
        ];
    } else {
        $attrs = ['class' => ['uk-flex uk-flex-center']];
    }

    echo "<div{$this->attrs($attrs)}>{$render($items, $this->el('div'))}</div>";
    return;
}

// Logo and Header Position in Stacked Center C and Stacked Left layouts
if ($style === 'grid-middle') {

    if (count($items) > 1) {
        echo "<div{$this->attrs([
            'class' => ['uk-grid-medium uk-child-width-auto uk-flex-middle'],
            'uk-grid' => true,
        ])}>{$render($items, $this->el('div'))}</div>";
        return;
    }

    echo $render($items);
    return;
}

// Section
if ($style == 'section') {

    $section = [];

    foreach ($items as $item) {

        if (!empty($item->type) && in_array($item->type, ['yootheme_builder', 'builderwidget'])) {

            if ($section) {
                echo $view('~theme/templates/section', ['name' => $name, 'items' => $section]);
                $section = [];
            }

            echo $item->content;
        } else {
            $section[] = $item;
        }
    }

    if ($section) {
        echo $view('~theme/templates/section', ['name' => $name, 'items' => $section]);
    }

    return;
}

// Grid
$grid = $position ?? $config("~theme.{$name}", []);
$visibilities = ['xs', 's', 'm', 'l', 'xl'];
$visible = 4;

// Widgets/Modules
$widgets = [];

foreach ($items as $index => $item) {

    $visibility = $config("~theme.modules.{$item->id}.visibility");
    $visible = min(array_search($visibility, $visibilities), $visible);
    $widget = $view('~theme/templates/module', ['index' => $index, 'module' => $item, 'position' => $name]);

    if (empty($widget)) {
        continue;
    }

    $widgets[] = $this->el(
        'div',
        [
            'class' => [
                'uk-width-{0}@{breakpoint}' => $config("~theme.modules.{$item->id}.width"),
                'uk-visible@{0}' => $visibility,
            ],
        ],
        $widget
    );
}

$el = $this->el('div', [
    'class' => [
        'uk-grid',
        'uk-grid-small' => $style == 'grid-stack-small',
        'uk-child-width-1-1' => str_starts_with($style, 'grid-stack'),
        'uk-child-width-expand@{breakpoint}' => !str_starts_with($style, 'grid-stack'),
        isset($grid['column_gap'], $grid['row_gap']) && $grid['column_gap'] == $grid['row_gap'] ? 'uk-grid-{column_gap}' : '[uk-grid-column-{column_gap}] [uk-grid-row-{row_gap}]',
        'uk-grid-divider {@divider} {@!column_gap:collapse} {@!row_gap:collapse}',
        'uk-flex-middle {@vertical_align}',
        'uk-visible@{0}' => $visible ? $visibilities[$visible] : false,
    ],
    'uk-grid' => count($items) > 1,
    'uk-height-match' => ['target: .uk-card {@match}'],
]);

?>

<?= $el($grid) ?>
<?php foreach ($widgets as $widget) : ?>
    <?= $widget($grid) ?>
<?php endforeach ?>
<?= $el->end() ?>
