<?php

$image = $this->el('image', [

    'src' => $props['image'] ?: $props['hover_image'],
    'alt' => $props['image_alt'],
    'loading' => $props['image_loading'] ? false : null,
    'width' => $props['image_width'],
    'height' => $props['image_height'],
    'thumbnail' => true,

]);

return $image;
