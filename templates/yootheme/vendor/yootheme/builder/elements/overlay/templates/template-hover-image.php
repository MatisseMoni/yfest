<?php

$hover_image = $this->el('image', [

    'class' => [
        'el-hover-image',
        'uk-transition-{image_transition}',
        'uk-transition-fade {@!image_transition}',
    ],

    'src' => $props['hover_image'],
    'alt' => true,
    'width' => $props['image_width'],
    'height' => $props['image_height'],
    'thumbnail' => true,
    'uk-cover' => true,

]);

return $hover_image;
