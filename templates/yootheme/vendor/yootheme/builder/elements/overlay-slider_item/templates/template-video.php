<?php

if ($iframe = $this->iframeVideo($props['video'])) {

    $video = $this->el('iframe', [

        'class' => [
            'uk-disabled',
        ],

        'src' => $iframe,
        'allow' => 'autoplay',
        'uk-responsive' => !$element['has_cover'],

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

    'width' => $element['image_width'],
    'height' => $element['image_height'],

    'uk-video' => [
        'automute: true' => !$element['has_cover'],
    ],

]);

return $video;
