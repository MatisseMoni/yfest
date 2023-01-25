<?php

$props['connect'] = "js-{$this->uid()}";
$props['item_nav'] = "js-{$this->uid()}";

$el = $this->el('div');

// Nav Alignment
$grid = $this->el('div', [

    'class' => [
        'uk-child-width-expand',
        $props['nav_grid_column_gap'] == $props['nav_grid_row_gap'] ? 'uk-grid-{nav_grid_column_gap}' : '[uk-grid-column-{nav_grid_column_gap}] [uk-grid-row-{nav_grid_row_gap}]',
        'uk-flex-middle {@nav_vertical_align}',
    ],

    'uk-grid' => true,
]);

$cell = $this->el('div', [

    'class' => [
        'uk-width-{nav_grid_width}@{nav_grid_breakpoint}',
        'uk-flex-last@{nav_grid_breakpoint} {@nav_position: right}',
    ],

]);

// Content
$content = $this->el('ul', [
    'id' => ['{connect}'],
    'class' => 'uk-switcher',
    'uk-height-match' => ['row: false {@switcher_height}'],
]);

?>

<?= $el($props, $attrs) ?>

    <?php if ($props['nav'] && in_array($props['nav_position'], ['left', 'right'])) : ?>

        <?= $grid($props) ?>
            <?= $cell($props, $this->render("{$__dir}/template-nav", compact('props'))) ?>
            <div>

                <?= $content($props) ?>
                    <?php foreach ($children as $child) :

                        $content_item = $this->el('li', [

                            'class' => [
                                'el-item',
                                'uk-margin-remove-first-child' => !$child->props['image'] || !in_array($props['image_align'], ['left', 'right']),
                            ],

                        ]);

                    ?>
                    <?= $content_item($props) ?>
                        <?= $builder->render($child, ['element' => $props]) ?>
                    <?= $content_item->end() ?>

                    <?php endforeach ?>
                <?= $content->end() ?>

            </div>
        </div>

    <?php else : ?>

        <?php if ($props['nav_position'] == 'top') : ?>
        <?= $this->render("{$__dir}/template-nav", compact('props')) ?>
        <?php endif ?>

        <?= $content($props) ?>

            <?php foreach ($children as $child) :

                $content_item = $this->el('li', [

                    'class' => [
                        'el-item',
                        'uk-margin-remove-first-child' => !$child->props['image'] || !in_array($props['image_align'], ['left', 'right']),
                    ],

                ]);

            ?>
            <?= $content_item($props) ?>
                <?= $builder->render($child, ['element' => $props]) ?>
            <?= $content_item->end() ?>
            <?php endforeach ?>

        <?= $content->end() ?>

        <?php if ($props['nav_position'] == 'bottom') : ?>
        <?= $this->render("{$__dir}/template-nav", compact('props')) ?>
        <?php endif ?>

    <?php endif ?>

</div>
