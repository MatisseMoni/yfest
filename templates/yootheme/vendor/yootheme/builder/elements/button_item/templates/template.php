<?php

$props['id'] = "js-{$this->uid()}";

// Button
$button = $this->el('a', [

    'class' => $this->expr([
        'el-content',
        'uk-width-1-1 {@fullwidth}',
        'uk-{button_style: link-\w+}' => ['button_style' => $props['button_style']],
        'uk-button uk-button-{!button_style: |link-\w+} [uk-button-{button_size}]' => ['button_style' => $props['button_style']],
        'uk-flex-inline uk-flex-center uk-flex-middle' => $props['content'] && $props['icon'],
    ], $element),

    'title' => ['{link_title}'],

]);

$button->attr($props['link_target'] == 'modal' ? [
    'href' => ['#{id}'],
    'uk-toggle' => true,
] : [
    'href' => ['{link}'],
    'target' => ['_blank {@link_target}'],
    'uk-scroll' => str_contains((string) $props['link'], '#'),
]);

// Icon
$icon = $this->el('span', [

    'class' => [
        'uk-margin-small-right' => $props['content'] && $props['icon_align'] == 'left',
        'uk-margin-small-left' => $props['content'] && $props['icon_align'] == 'right',
    ],
    'uk-icon' => $props['icon'],

]);

?>

<?= $button($props) ?>

    <?php if ($props['icon'] && $props['icon_align'] == 'left') : ?>
    <?= $icon($props, '') ?>
    <?php endif ?>

    <?php if ($props['content']) : ?>
    <?= $props['content'] ?>
    <?php endif ?>

    <?php if ($props['icon'] && $props['icon_align'] == 'right') : ?>
    <?= $icon($props, '') ?>
    <?php endif ?>

</a>

<?= $this->render("{$__dir}/template-lightbox", compact('props')) ?>
