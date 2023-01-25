<?php

$el = $this->el($props['list_element'], [

    'class' => [
        'uk-list',
        'uk-list-{list_marker} {@list_type: vertical}',
        'uk-list-{list_marker_color} {@list_type: vertical} {@!list_marker: bullet}',
        'uk-list-{list_style} {@list_type: vertical}',
        'uk-list-{list_size} {@list_type: vertical}',
        'uk-list-collapse {@list_type: horizontal}',
        'uk-column-{column}[@{column_breakpoint}]',
        'uk-column-divider {@column} {@column_divider}',
        'uk-margin-remove {@position: absolute}',
    ],

]);

$item = $this->el('li', [

    'class' => [
        'el-item',
        'uk-display-inline-block {@list_type: horizontal}',
    ],

]);

?>

<?= $el($props, $attrs) ?>
<?php foreach ($children as $i => $child) : ?>

    <?= $item($props) ?>
        <?= $builder->render($child, ['element' => $props]) ?>
        <?php if ($props['list_type'] != 'vertical' && $i !== array_key_last($children)) : ?>
        <?= $props['list_horizontal_separator'] ?>
        <?php endif ?>
    <?= $item->end() ?>

<?php endforeach ?>
<?= $el->end() ?>
