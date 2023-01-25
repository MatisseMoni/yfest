<?php

namespace YOOtheme;

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Site\Helper\RouteHelper;

HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers');

/**
 * @var Config $config
 * @var View   $view
 */
[$config, $view] = app(Config::class, View::class);

// Config
$blog = '~theme.blog';

// Parameter shortcuts
$params = $this->params;
$lead = $this->lead_items ?: [];
$intro = $this->intro_items ?: [];
$columns = max(1, $params->get('num_columns'));

// Article template
$article = $view('~theme/templates/article{-blog,}', function ($item) use ($columns) {
    return [
        'layout' => 'blog',
        'article' => $item,
        'content' => $item->introtext,
        'image' => 'intro',
        'columns' => $columns,
    ];
});

?>

<?php if ($params->get('show_page_heading')
        || $params->get('page_subheading')
        || $params->get('show_category_title', 1)
        || ($params->def('show_description_image', 1) && $this->category->getParams()->get('image'))
        || ($params->get('show_description', 1) && $this->category->description)
        || ($this->params->get('show_cat_tags', 1) && !empty($this->category->tags->itemTags))
    ) : ?>

<div class="uk-panel uk-margin-medium-bottom">

    <?php if ($params->get('show_page_heading')) : ?>
    <h1><?= $this->escape($params->get('page_heading')) ?></h1>
    <?php endif ?>

    <?php if ($params->get('page_subheading')) : ?>
    <h2><?= $this->escape($params->get('page_subheading')) ?></h2>
    <?php endif ?>

    <?php if ($params->get('show_category_title')) : ?>
    <h3><?= $this->category->title ?></h3>
    <?php endif ?>

    <?php if ($params->get('show_description_image') && $this->category->getParams()->get('image')) : ?>
        <?= HTMLHelper::_('image', $this->category->getParams()->get('image'), htmlspecialchars($this->category->getParams()->get('image_alt')), [], false, 0) ?>
    <?php endif ?>

    <?php if ($params->get('show_description') && $this->category->description) : ?>
    <div class="uk-margin"><?= HTMLHelper::_('content.prepare', $this->category->description, '', 'com_content.category') ?></div>
    <?php endif ?>

    <?php if ($params->get('show_cat_tags') && !empty($this->category->tags->itemTags)) : ?>
        <?= LayoutHelper::render('joomla.content.tags', $this->category->tags->itemTags) ?>
    <?php endif ?>

</div>
<?php endif ?>

<?php if (empty($this->lead_items) && empty($this->intro_items) && empty($this->link_items)) : ?>
    <?php if ($params->get('show_no_articles', 1)) : ?>
    <p><?= Text::_('COM_CONTENT_NO_ARTICLES') ?></p>
    <?php endif ?>
<?php endif ?>

<?php

    $attrs_lead = [];

    $column_gap = $config("$blog.grid_column_gap");
    $row_gap = $config("$blog.grid_row_gap");

    $attrs_lead['class'][] = 'uk-grid uk-child-width-1-1';
    $attrs_lead['class'][] = $row_gap ? "uk-grid-{$row_gap}" : '';

?>

<?php if ($lead) : ?>
<div <?= $view->attrs($attrs_lead) ?>>
    <?php foreach ($lead as $item) : ?>
    <div><?= $article($item) ?></div>
    <?php endforeach ?>
</div>
<?php endif ?>

<?php

if ($intro) :

    // Grid
    $attrs = [];
    $options = [];

    $options[] = $config("$blog.grid_masonry") ? 'masonry: true' : '';
    $options[] = $config("$blog.grid_parallax") ? "parallax: {$config("$blog.grid_parallax")}" : '';
    $attrs['uk-grid'] = implode(';', array_filter($options)) ?: true;

    // Columns
    $breakpoints = ['s', 'm', 'l', 'xl'];
    $breakpoint = $config("$blog.grid_breakpoint");
    $pos = array_search($breakpoint, $breakpoints);

    if ($pos === false || $columns === 1) {
        $attrs['class'][] = "uk-child-width-1-{$columns}";
    } else {
        for ($i = $columns; $i > 0; $i--) {
            if (($pos > -1) && ($i > 1)) {
                $attrs['class'][] = "uk-child-width-1-{$i}@{$breakpoints[$pos]}";
                $pos--;
            }
        }
    }

    if ($column_gap == $row_gap) {
        $attrs['class'][] = $column_gap ? "uk-grid-{$column_gap}" : '';
    } else {
        $attrs['class'][] = $column_gap ? "uk-grid-column-{$column_gap}" : '';
        $attrs['class'][] = $row_gap ? "uk-grid-row-{$row_gap}" : '';
    }

?>

    <div <?= $view->attrs($attrs) ?>>
        <?php foreach ($intro as $item) : ?>
        <div><?= $article($item) ?></div>
        <?php endforeach ?>
    </div>

<?php endif ?>

<?php if (!empty($this->link_items)) : ?>
<div class="uk-margin-large<?= $config("$blog.header_align") ? ' uk-text-center' : '' ?>">

    <h3><?= Text::_('COM_CONTENT_MORE_ARTICLES') ?></h3>

    <ul class="uk-list">
        <?php foreach ($this->link_items as $item) : ?>
        <li><a href="<?= Route::_(RouteHelper::getArticleRoute($item->slug, $item->catid)) ?>"><?= $item->title ?></a></li>
        <?php endforeach ?>
    </ul>

</div>
<?php endif ?>

<?php if (($params->def('show_pagination', 1) == 1 || $params->get('show_pagination') == 2) && ($this->pagination->pagesTotal > 1)) : ?>

    <?= $view('~theme/templates/pagination', ['pagination' => $this->pagination]) ?>

<?php endif ?>
