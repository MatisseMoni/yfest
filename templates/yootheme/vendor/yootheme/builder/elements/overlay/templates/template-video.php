<?php

if ($iframe = $this->iframeVideo($props['video'])) {

    $video = $this->el('iframe', [

        'class' => [
            'uk-disabled',
        ],

        'src' => $iframe,
        'allow' => 'autoplay',
        'uk-responsive' => !$props['image_min_height'],

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

    'uk-video' => [
        'automute: true' => !$props['image_min_height'],
    ],

]);

return $video;
