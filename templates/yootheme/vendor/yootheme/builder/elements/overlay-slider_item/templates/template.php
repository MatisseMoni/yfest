<?php

$el = $this->el($isLink = $props['link'] && $element['overlay_link'] ? 'a' : 'div', [

    'class' => [
        'uk-cover-container',
        'uk-transition-toggle' => $isTransition = $element['overlay_hover'] || $element['image_transition'] || $props['hover_image'],
    ],

    'style' => [
        'min-height: {slider_min_height}px; {@!slider_height}',
        'background-color: ' . $props['media_background'] . ';' => $props['media_background'],
    ],

    'tabindex' => $isTransition && !$isLink ? 0 : null,
]);

$overlay = $this->el('div', [

    'class' => [
        'uk-{overlay_style}',
        'uk-transition-{overlay_transition} {@overlay_hover} {@overlay_cover}',

        'uk-position-cover {@overlay_cover}',
        'uk-position-{overlay_margin} {@overlay_cover}',
    ],

]);

$position = $this->el('div', [

    'class' => [
        'uk-position-{overlay_position} [uk-position-{overlay_margin} {@overlay_style}]',
        'uk-transition-{overlay_transition} {@overlay_hover}' => !$element['overlay_transition_background'] || !$element['overlay_cover'],
    ],

]);

$content = $this->el('div', [

    'class' => [
        $element['overlay_style'] ? 'uk-overlay' : 'uk-panel',
        'uk-padding {@!overlay_padding} {@!overlay_style}',
        'uk-padding-{!overlay_padding: |none}',
        'uk-padding-remove {@overlay_padding: none} {@overlay_style}',
        'uk-width-{overlay_maxwidth} {@!overlay_position: top|bottom}',
        'uk-margin-remove-first-child',
    ],

]);

if (!$element['overlay_cover']) {
    $position->attr($overlay->attrs);
}

// Link
$link = include "{$__dir}/template-link.php";

// Helper
$helper = '';

$helper_condition_color = (!$element['overlay_style'] || $element['overlay_mode'] == 'cover') && ($props['text_color'] ?: $element['text_color']);
$helper_condition_toggle = ($props['text_color_hover'] || $element['text_color_hover']) && ((!$element['overlay_style'] && $props['hover_image']) || ($element['overlay_cover'] && $element['overlay_hover'] && $element['overlay_transition_background']));

if ($helper_condition_color || $helper_condition_toggle) {

    $color = $props['text_color'] ?: $element['text_color'];
    $inverse = $color === 'light' ? 'dark' : 'light';

    $helper = $this->el('div', [

        'class' => [
            // Needed to match helper `dìv` height because image covers if height is set to viewport or if width is set to auto
            'uk-grid-item-match' => ($element['slider_width'] && $element['slider_height']) || !$element['slider_width'],
            // Needs to be parent of `uk-light` or `uk-dark`
            'uk-{0}' => $helper_condition_color ? $color : false,
        ],

    ]);

    // Needs to be on anchor to have just one focusable toggle when using keyboard navigation
    if ($helper_condition_toggle) {
        // Inverse text color on hover
        $el->attr('uk-toggle', "cls: uk-{$inverse} uk-{$color}; mode: hover; target: !*");
    }

}

?>

<?php if ($helper) : ?>
<?= $helper($props) ?>
<?php endif ?>

<?= $el($element, $attrs) ?>

        <?= $this->render("{$__dir}/template-media", compact('props')) ?>

        <?php if ($props['media_overlay']) : ?>
        <div class="uk-position-cover" style="background-color:<?= $props['media_overlay'] ?>"></div>
        <?php endif ?>

        <?php if ($element['overlay_cover']) : ?>
        <?= $overlay($element, '') ?>
        <?php endif ?>

        <?php if ($props['title'] || $props['meta'] || $props['content'] || ($props['link'] && ($props['link_text'] || $element['link_text']))) : ?>
        <?= $position($element, $content($element, $this->render("{$__dir}/template-content", compact('props', 'link')))) ?>
        <?php endif ?>

<?= $el->end() ?>

<?php if ($helper) : ?>
<?= $helper->end() ?>
<?php endif ?>
