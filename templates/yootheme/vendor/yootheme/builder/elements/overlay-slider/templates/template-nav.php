<?php

use function YOOtheme\trans;

$nav = $this->el('ul', [

    'class' => [
        'el-nav uk-slider-nav',
        'uk-{nav}',
        'uk-flex-{nav_align}',
    ],

    'uk-margin' => true,
], array_map(function($child, $i) {
    return $this->el(
        'li',
        ['uk-slider-item' => $i],
        $this->el('a', [
            'href' => true,
            'aria-label' => trans('Go to slide %slide%', ['%slide%' => 1 + $i]),
        ], '')
    );
}, $children, array_keys($children)));

$nav_container = $this->el('div', [

    'class' => [
        'uk-margin[-{nav_margin}]-top',
        'uk-visible@{nav_breakpoint}',
        'uk-{nav_color}',
    ],

]);

echo $props['nav_color'] ? $nav_container($props, $nav($props)) : $nav($props, $nav_container->attrs);
