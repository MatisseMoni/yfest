<?php

namespace YOOtheme;

/** @var ImageProvider $imageProvider */
$imageProvider = app(ImageProvider::class);

$el = $this->el('div');

// Video
if ($iframe = $this->iframeVideo($props['video'], [], false)) {

    $video = $this->el('iframe', [

        'src' => $iframe,
        'allow' => 'autoplay',
        'allowfullscreen' => true,
        'uk-responsive' => true,

    ]);

    if ($props['video_width'] && !$props['video_height']) {
        $props['video_height'] = round($props['video_width'] * 9 / 16);
    } elseif ($props['video_height'] && !$props['video_width']) {
        $props['video_width'] = round($props['video_height'] * 16 / 9);
    }

} else {

    $video = $this->el('video', [

        'src' => $props['video'],
        'controls' => $props['video_controls'],
        'loop' => $props['video_loop'],
        'muted' => $props['video_muted'],
        'playsinline' => $props['video_playsinline'],
        'preload' => ['none {@video_lazyload}'],
        'poster' => $props['video_poster'] && ($props['video_width'] || $props['video_height'])
            ? $imageProvider->getUrl("{$props['video_poster']}#thumbnail={$props['video_width']},{$props['video_height']}")
            : $props['video_poster'],
        $props['video_autoplay'] === 'inview' ? 'uk-video' : 'autoplay' => $props['video_autoplay'],

    ]);

}

$video->attr([

    'class' => [
        'uk-box-shadow-{video_box_shadow}',
    ],

    'width' => $props['video_width'],
    'height' => $props['video_height'],

]);

// Box decoration
$decoration = $this->el('div', [

    'class' => [
        'uk-box-shadow-bottom {@video_box_decoration: shadow}',
        'tm-mask-default {@video_box_decoration: mask}',
        'tm-box-decoration-{video_box_decoration: default|primary|secondary}',
        'tm-box-decoration-inverse {@video_box_decoration_inverse} {@video_box_decoration: default|primary|secondary}',
        'uk-inline {@!video_box_decoration: |shadow}',
    ],

]);

?>

<?= $el($props, $attrs) ?>

    <?php if ($props['video_box_decoration']) : ?>
    <?= $decoration($props) ?>
    <?php endif ?>

        <?= $video($props, '') ?>

        <?php if ($props['video_box_decoration']) : ?>
    <?= $decoration->end() ?>
    <?php endif ?>

<?= $el->end() ?>
