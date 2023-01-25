<?php

// Resets
if ($props['overlay_link']) { $props['title_link'] = ''; }

// New logic shortcuts
$props['overlay_cover'] = $props['overlay_style'] && $props['overlay_mode'] == 'cover';

$el = $this->el('div', [

    // Needs to be parent of `uk-link-toggle`
    'class' => [
        'uk-{text_color}' => !$props['overlay_style'] || $props['overlay_cover'],
    ],

]);

// Container
$container = $this->el($isLink = $props['link'] && $props['overlay_link'] ? 'a' : 'div', [

    'class' => [
        'el-container',

        'uk-box-shadow-{image_box_shadow}',
        'uk-box-shadow-hover-{image_hover_box_shadow}',

        'uk-border-{image_border} {@!image_transition_border} {@!image_box_decoration}',
        'uk-box-shadow-bottom {@image_box_decoration: shadow}',
        'tm-mask-default {@image_box_decoration: mask}',
        'tm-box-decoration-{image_box_decoration: default|primary|secondary}',
        'tm-box-decoration-inverse {@image_box_decoration_inverse} {@image_box_decoration: default|primary|secondary}',
        'uk-inline {@!image_box_decoration: |shadow}',
        'uk-inline-clip {@!image_box_decoration}',

        'uk-transition-toggle' => $isTransition = $props['overlay_hover'] || $props['image_transition'] || $props['image_transition_border'] || $props['hover_image'],

    ],

    'style' => [
        'min-height: {image_min_height}px; {@!image_box_decoration} {@!image_transition_border}',
        'background-color: ' . $props['media_background'] . ';' => $props['media_background'],
    ],

    'tabindex' => $isTransition && !$isLink ? 0 : null,

]);

// Inverse text color on hover
if ($props['text_color_hover'] && ((!$props['overlay_style'] && $props['hover_image']) || ($props['overlay_cover'] && $props['overlay_hover'] && $props['overlay_transition_background']))) {
    $inverse = $props['text_color'] === 'light' ? 'dark' : 'light';
    $container->attr('uk-toggle', "cls: uk-{$inverse} uk-{$props['text_color']}; mode: hover; target: !*");
}

// Box Decoration / Transition Border
$image_container = $props['image_box_decoration'] || $props['image_transition_border'] ? $this->el('div', [

    'class' => [
        'uk-inline-clip {@image_box_decoration}',
        'tm-transition-border {@image_transition_border}',
    ],

    'style' => [
        'min-height: {image_min_height}px;',
    ],

]) : null;

$overlay = $this->el('div', [

    'class' => [
        'uk-{overlay_style}',
        'uk-transition-{overlay_transition} {@overlay_hover}',

        'el-overlay uk-position-cover {@overlay_cover}',
        'uk-position-{overlay_margin} {@overlay_cover}',
    ],

]);

$position = $this->el('div', [

    'class' => [
        'uk-position-{overlay_position} [uk-position-{overlay_margin} {@overlay_style}]',
        'uk-transition-{overlay_transition} {@overlay_hover}' => !($props['overlay_transition_background'] && $props['overlay_cover']),
    ],

]);

$content = $this->el('div', [

    'class' => [
        $props['overlay_style'] ? 'uk-overlay' : 'uk-panel',
        'uk-padding {@!overlay_padding} {@!overlay_style}',
        'uk-padding-{!overlay_padding: |none}',
        'uk-padding-remove {@overlay_padding: none} {@overlay_style}',
        'uk-width-{overlay_maxwidth} {@!overlay_position: top|bottom}',
        'uk-margin-remove-first-child',
    ],

]);

if (!$props['overlay_cover']) {
    $position->attr($overlay->attrs);
}

// Link
$link = include "{$__dir}/template-link.php";

?>

<?= $el($props, $attrs) ?>
    <?= $container($props) ?>

        <?php if ($image_container) : ?>
        <?= $image_container($props) ?>
        <?php endif ?>

            <?= $this->render("{$__dir}/template-media", compact('props')) ?>

            <?php if ($props['media_overlay']) : ?>
            <div class="uk-position-cover" style="background-color:<?= $props['media_overlay'] ?>"></div>
            <?php endif ?>

            <?php if ($props['overlay_cover']) : ?>
            <?= $overlay($props, '') ?>
            <?php endif ?>

            <?php if ($props['title'] || $props['meta'] || $props['content'] || ($props['link'] && $props['link_text'])) : ?>
            <?= $position($props, $content($props, $this->render("{$__dir}/template-content", compact('props', 'link')))) ?>
            <?php endif ?>

        <?php if ($image_container) : ?>
        <?= $image_container->end() ?>
        <?php endif ?>

    <?= $container->end() ?>
</div>
