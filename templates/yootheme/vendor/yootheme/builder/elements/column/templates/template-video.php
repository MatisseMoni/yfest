<?php

if ($iframe = $this->iframeVideo($props['video'])) {

    $video = $this->el('iframe', [

        'class' => [
            'uk-disabled',
        ],

        'src' => $iframe,

    ]);

} else {

    $video = $this->el('video', [

        'src' => $props['video'],
        'controls' => false,
        'loop' => true,
        'autoplay' => true,
        'muted' => true,
        'playsinline' => true,

    ]);

}

$video->attr([

    'width' => $props['image_width'],
    'height' => $props['image_height'],

    'class' => [
        'uk-blend-{media_blend_mode}',
        'uk-visible@{media_visibility}',
    ],

    'uk-cover' => true,

]);

return $video;
