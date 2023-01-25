<?php

// Config
$menuposition = '~menu';
$menuitem = "~theme.menu.items.{$item->id}";

$icon = $config("$menuitem.icon");
$image = $config("$menuitem.image");

if ($image && preg_match('/^[a-z_-]+$/i', $image)) {
    $icon = $image;
    $image = '';
}

if ($icon) {

    echo $this->el('span', [
        'class' => [
            'uk-margin-small-right' => $config("$menuposition.image_margin") && !$config("$menuitem.image_only") && !$config("$menuitem.subtitle"),
            $config("$menuitem.image_classes", ''),

        ],
        'uk-icon' => [
            'icon: {0};' => $icon,
            'width: {0};' => $config("$menuposition.icon_width"),
        ],
    ])([], '');

} elseif ($image) {

    echo $this->el('image', [

        'class' => [
            'uk-margin-small-right' => $config("$menuposition.image_margin") && !$config("$menuitem.image_only") && !$config("$menuitem.subtitle"),
            $config("$menuitem.image_classes", ''),
        ],
        'src' => $image,
        'alt' => true,
        'width' => $config("$menuposition.image_width"),
        'height' => $config("$menuposition.image_height"),
        'uk-svg' => (bool) $config("$menuposition.image_svg_inline"),
        'thumbnail' => true,
        'loading' => '',

    ])();

}
