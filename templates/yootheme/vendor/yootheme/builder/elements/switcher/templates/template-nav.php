<?php

$nav = $this->el('ul', [

    'class' => [
        'el-nav',
        'uk-margin[-{nav_margin}] {@nav_position: top|bottom}',
        'uk-{nav: thumbnav} [uk-flex-nowrap {@thumbnav_nowrap}]',
    ],

    'hidden' => !$props['nav'],

    $props['nav'] == 'tab' ? 'uk-tab' : 'uk-switcher' => [
        'connect: #{connect};',
        'itemNav: #{item_nav};',
        'animation: uk-animation-{switcher_animation};',
        'media: @{nav_grid_breakpoint} {@nav_position: left|right} {@nav: tab};',
    ],

    'uk-margin' => ['1{@nav: thumbnav} {@!thumbnav_nowrap}'],
]);

$nav_horizontal = [
    'uk-subnav {@nav: subnav-.*}',
    'uk-{nav: subnav.*}',
    'uk-tab-{nav_position: bottom} {@nav: tab}',
    'uk-flex-{nav_align: right|center}',
    'uk-child-width-expand {@nav_align: justify}',
];

$nav_vertical = [
    'uk-nav uk-nav-[primary {@nav_style_primary}][default {@!nav_style_primary}] [uk-text-left {@text_align}] {@nav: subnav.*}',
    'uk-tab-{nav_position} {@nav: tab}',
    'uk-thumbnav-vertical {@nav: thumbnav}',
];

$nav_switcher = in_array($props['nav_position'], ['top', 'bottom'])
    ? ['class' => $nav_horizontal]
    : ['class' => $nav_vertical,
        'uk-toggle' => $props['nav'] != 'tab' ? [
            "cls: {$this->expr(array_merge($nav_vertical, $nav_horizontal), $props)};",
            'mode: media;',
            'media: @{nav_grid_breakpoint};',
        ] : false,
    ];

?>

<?= $nav($props, $nav_switcher) ?>
    <?php foreach ($children as $child) :

        // Image
        $image = $this->el('image', [
            'class' => [
                'uk-text-{thumbnav_svg_color}' => $props['thumbnav_svg_inline'] && $props['thumbnav_svg_color'] && $this->isImage($child->props['thumbnail'] ?: $child->props['image']) == 'svg',
            ],
            'src' => $child->props['thumbnail'] ?: $child->props['image'],
            'alt' => $child->props['label'] ?: $child->props['title'],
            'loading' => $props['image_loading'] ? false : null,
            'width' => $props['thumbnav_width'],
            'height' => $props['thumbnav_height'],
            'uk-svg' => (bool) $props['thumbnav_svg_inline'],
            'thumbnail' => true,
        ]);

        $thumbnail = $image->attrs['src'] && $props['nav'] == 'thumbnav' ? $image($props) : '';
    ?>
    <li>
        <a href="#"><?= $thumbnail ?: $child->props['label'] ?: $child->props['title'] ?></a>
    </li>
    <?php endforeach ?>
</ul>
