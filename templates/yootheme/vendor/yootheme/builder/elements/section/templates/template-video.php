<?php

// Video
if (!$props['video']) {
    return;
}

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

    'class' => [
        'uk-blend-{media_blend_mode}',
        'uk-visible@{media_visibility}',
    ],

    'width' => $props['video_width'],
    'height' => $props['video_height'],

    'uk-cover' => true,

]);

echo $video($props, '');
