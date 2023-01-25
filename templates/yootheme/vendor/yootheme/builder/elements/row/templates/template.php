<?php

$el = $this->el('div', $attrs);

$el->attr([

    'class' => [
        'tm-grid-expand',
        $props['column_gap'] == $props['row_gap'] ? 'uk-grid-{column_gap}' : '[uk-grid-column-{column_gap}] [uk-grid-row-{row_gap}]',
        'uk-grid-divider {@divider} {@!column_gap:collapse} {@!row_gap:collapse}',
        'uk-child-width-1-1 {@!layout}',
    ],

    'uk-grid' => true,

    // Match height if single panel element inside cell
    'uk-height-match' => ['target: .uk-card {@match}'],
]);

// Margin
$margin = $this->el('div', [
    'class' => [

        'uk-grid-margin[-{row_gap}] {@!margin} {@row_gap: |small|medium|large}',

        'uk-margin {@margin: default}',
        'uk-margin-{!margin: |default}',
        'uk-margin-remove-top {@margin_remove_top}{@!margin: remove-vertical}',
        'uk-margin-remove-bottom {@margin_remove_bottom}{@!margin: remove-vertical}',

        'uk-container {@width}',
        'uk-container-{width}{@width: xsmall|small|large|xlarge|expand}',
        'uk-padding-remove-horizontal' => ($props['padding_remove_horizontal'] && $props['width'] && $props['width'] != 'expand') || $props['parent'] == 'layout',
        'uk-container-expand-{width_expand} {@width} {@!width:expand}',
    ],
]);

echo $props['width']
    ? $margin($props, $el($props, $builder->render($children)))
    : $el($props, $margin->attrs, $builder->render($children));
