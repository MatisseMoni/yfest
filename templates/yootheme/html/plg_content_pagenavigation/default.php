<?php

namespace YOOtheme;

defined('_JEXEC') or die;

if (Path::get(__FILE__) !== $file = Path::get('~theme/html/plg_content_pagenavigation/default.php')) {
    return include $file;
}

/** @var View $view */
$view = app(View::class);

$props = ['isPage' => $row->category_alias === 'uncategorised'];

$el = $view->el('ul', [
    'class' => [
        'uk-pagination',
        'uk-margin-medium {@!isPage}',
        'uk-flex-between uk-margin-xlarge {@isPage}',
    ],
]);

$prev = $view->el('li');
$next = $view->el('li', [
    'class' => [
        'uk-margin-auto-left {@!isPage}',
    ],
]);

?>

<?= $el($props)?>

<?php if ($row->prev) : ?>
    <?= $prev($props) ?>
        <a href="<?= $row->prev ?>"><span uk-pagination-previous></span> <?= $row->prev_label ?></a>
    <?= $prev->end() ?>
<?php endif ?>

<?php if ($row->next) : ?>
    <?= $next($props) ?>
    <a href="<?= $row->next ?>"><?= $row->next_label ?> <span uk-pagination-next></span></a>
    <?= $next->end() ?>
<?php endif ?>

<?= $el->end()?>
