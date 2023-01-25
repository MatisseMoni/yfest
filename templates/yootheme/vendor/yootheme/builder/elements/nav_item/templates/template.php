<?php

// Link
if ($props['link']) {

    $link = $this->el('a', [

        'class' => [
            'el-link',
            'uk-link-{link_style}',
        ],

        'href' => $props['link'],
        'uk-scroll' => str_contains((string) $props['link'], '#'),
        'target' => $props['link_target'] ? '_blank' : '',

    ]);

} else {

    $link = $this->el('a', [

        'class' => [
            'el-content uk-disabled',
        ],

    ]);

}

// Subtitle
$meta = $this->el('div', [

    'class' => [
        'uk-nav-subtitle',
    ],

]);

// Image Align
$grid = $this->el('div', [

    'class' => [
        'uk-grid-small uk-child-width-expand uk-flex-nowrap',
        'uk-flex-middle {@image_vertical_align}',
    ],

    'uk-grid' => true,
]);

$cell_image = $this->el('div', [

    'class' => [
        'uk-width-auto'
    ],

]);

// Image
if ($props['image']) {

    $image = $this->el('image', [

        'class' => [
            'el-image',
            'uk-border-{image_border}',
            'uk-margin-small-right {@image_margin}' => !$props['meta'],
            'uk-text-{image_svg_color} {@image_svg_inline}' => $this->isImage($props['image']) == 'svg',
        ],

        'src' => $props['image'],
        'alt' => $props['image_alt'],
        'loading' => $element['image_loading'] ? false : null,
        'width' => $element['image_width'],
        'height' => $element['image_height'],
        'uk-svg' => $element['image_svg_inline'],
        'thumbnail' => true,
    ]);

    $props['image'] = $image($element);

} elseif ($props['icon']) {

    $icon = $this->el('span', [

        'class' => [
            'el-image',
            'uk-margin-small-right {@image_margin}' => !$props['meta'],
        ],

        'uk-icon' => [
            'icon: {0};' => $props['icon'],
            'width: {icon_width};',
            'height: {icon_width};',
        ],

    ]);

    $props['image'] = $icon($element, '');
}

?>

<?= $link($element) ?>

    <?php if ($props['image'] && $props['meta']) : ?>

        <?= $grid($element) ?>
            <?= $cell_image($element, $props['image']) ?>
            <div>
                <?= $props['content'] ?>
                <?= $meta($element, $props['meta']) ?>
            </div>
        </div>

    <?php else : ?>

        <?= $props['image'] ?>

        <?php if ($props['meta']) : ?>
        <div>
            <?= $props['content'] ?>
            <?= $meta($element, $props['meta']) ?>
        </div>
        <?php else : ?>
            <?= $props['content'] ?>
        <?php endif ?>

    <?php endif ?>

<?= $link->end() ?>
