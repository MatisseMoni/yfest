<?php

use YOOtheme\Arr;

$el = $this->el('div');

// Nav
$nav = $this->el('ul', [

    'class' => [
        'uk-margin-remove-bottom',
        'uk-nav uk-nav-{!nav_style: navbar-dropdown-nav} [uk-nav-divider {@nav_divider}]',
        'uk-nav uk-{nav_style: navbar-dropdown-nav}',
        'uk-nav-{text_align: center}',
    ],

]);


// Image align
$grid = $this->el('div', [

    'class' => [
        'uk-child-width-expand[@{grid_breakpoint}]',
        $props['grid_column_gap'] == $props['grid_row_gap'] ? 'uk-grid-{grid_column_gap}' : '[uk-grid-column-{grid_column_gap}] [uk-grid-row-{grid_row_gap}]',
        'uk-grid-divider {@grid_divider} {@!grid_column_gap:collapse} {@!grid_row_gap:collapse}',
    ],

    'uk-grid' => true,
]);

?>

<?= $el($props, $attrs) ?>

    <?php if ($props['grid'] > 1) : ?>
    <?= $grid($props) ?>
    <?php endif ?>

    <?php foreach (Arr::columns($children, $props['grid']) as $items) : ?>

        <?php if ($props['grid'] > 1) : ?>
        <div>
        <?php endif ?>

        <?= $nav($props) ?>
        <?php foreach ($items as $child) : ?>
            <?php if ($child->props['type'] == 'heading') : ?>
            <li class="uk-nav-header"><?= $child->props['content'] ?></li>
            <?php elseif ($child->props['type'] == 'divider') : ?>
            <li class="uk-nav-divider"></li>
            <?php else : ?>
            <li class="el-item <?= $child->props['active'] ? 'uk-active' : '' ?>"><?= $builder->render($child, ['element' => $props]) ?></li>
            <?php endif ?>
        <?php endforeach ?>
        <?= $nav->end() ?>

        <?php if ($props['grid'] > 1) : ?>
        </div>
        <?php endif ?>

    <?php endforeach ?>

    <?php if ($props['grid'] > 1) : ?>
    <?= $grid->end() ?>
    <?php endif ?>

<?= $el->end() ?>
