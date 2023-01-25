<?php

// Marker
$marker = $this->el('a', [

    'class' => [
        'el-marker uk-position-absolute uk-transform-center uk-preserve-width',
    ],

    'style' => [
        'top: {0};' => (is_numeric(rtrim($props['position_y'] ?: '', '%')) ? (float) $props['position_y'] : 50) . '%',
        'left: {0};' => (is_numeric(rtrim($props['position_x'] ?: '', '%')) ? (float) $props['position_x'] : 50) . '%',
    ],

    'href' => '#', // WordPress Preview reloads if `href` is empty
    'uk-marker' => true,
]);

// Marker Container
$marker_container = $this->el('div', [

    'class' => [
        'uk-{marker_color}',
    ],

]);

// Drop
$drop = $this->el('div', [

    'style' => [
        'width: {0}px;' => rtrim($element['drop_width'] ?: '', 'px') ?: 300,
    ],

    'uk-drop' => [
        'pos: {0};' => $props['drop_position'] ?: $element['drop_position'],
        'mode: click;' => $element['drop_mode'] == 'click',
        'toggle: - * > *; {@marker_color}',
        'auto-update: false;',
    ],

]);

?>

<?php if ($element['marker_color']) : ?>
<?= $marker_container($element) ?>
<?php endif ?>

    <?= $marker($element, '') ?>

<?php if ($element['marker_color']) : ?>
<?= $marker_container->end() ?>
<?php endif ?>

<?= $drop($element, $builder->render($child)); ?>
