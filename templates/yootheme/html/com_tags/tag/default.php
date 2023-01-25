<?php

namespace YOOtheme;

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Language;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\Component\Tags\Site\Helper\RouteHelper;

HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers');

[$view, $language] = app(View::class, Language::class);

// Load language
$language->load('com_content');

// Parameter shortcuts
$params = $this->params;
$date = $params->get('tag_list_show_date');
$length = $params->get('tag_list_item_maximum_characters');

// Article template
$article = $view('~theme/templates/article{-tag,}', function ($item) use ($params, $date, $length) {
    return [

        // Article
        'layout' => 'blog',
        'article' => $item,
        'content' => HTMLHelper::_('string.truncate', $item->body, $length),
        'image' => $params->get('tag_list_show_item_image') ? 'intro' : null,
        'link' => Route::_(RouteHelper::getItemRoute($item->id, $item->alias, $item->catid, $item->language, $item->type_alias, $item->router)),

        // Params
        'params' => [
            'show_title' => true,
            'link_titles' => true,
            'show_create_date' => $date == 'created',
            'show_modify_date' => $date == 'modified',
            'show_publish_date' => $date == 'published',
        ],

    ];
});

// Note that there are certain parts of this layout used only when there is exactly one tag.
$isSingleTag = (count($this->item) == 1);

?>

<?php if ($params->get('show_page_heading')
            || $params->get('show_tag_title', 1)
            || ($isSingleTag && ($params->get('tag_list_show_tag_image', 1) || $params->get('tag_list_show_tag_description', 1)))
            || ($params->get('tag_list_show_tag_description', 1) || $params->get('show_description_image', 1))
        ) : ?>

<div class="uk-panel uk-margin-large-bottom">

    <?php if ($params->get('show_page_heading')) : ?>
    <h1><?= $this->escape($params->get('page_heading')) ?></h1>
    <?php endif ?>

    <?php if ($params->get('show_tag_title')) : ?>
    <h2><?= HTMLHelper::_('content.prepare', $this->document->title, '', 'com_tag.tag') ?></h2>
    <?php endif ?>

    <?php // We only show a tag description if there is a single tag. ?>
    <?php  if ($isSingleTag && ($params->get('tag_list_show_tag_image') || $params->get('tag_list_show_tag_description'))) : ?>
    <div class="uk-clearfix uk-margin">

        <?php $images = json_decode($this->item[0]->images) ?>

        <?php if ($params->get('tag_list_show_tag_image', 1) == 1 && !empty($images->image_fulltext)) : ?>
            <?= HTMLHelper::_('image', htmlspecialchars($images->image_fulltext), $images->image_fulltext_alt, [], false, 0) ?>
        <?php endif ?>

        <?php if ($params->get('tag_list_show_tag_description') == 1 && $this->item[0]->description) : ?>
        <div class="uk-margin"><?= HTMLHelper::_('content.prepare', $this->item[0]->description, '', 'com_tags.tag') ?></div>
        <?php endif ?>

    </div>
    <?php endif ?>

    <?php // If there are multiple tags and a description or image has been supplied use that. ?>
    <?php if ($params->get('tag_list_show_tag_description') || $params->get('show_description_image')) : ?>

        <?php if ($params->get('show_description_image', 1) == 1 && $params->get('tag_list_image')) : ?>
            <?= HTMLHelper::_('image', $params->get('tag_list_image'), null, [], false, 0) ?>
        <?php endif ?>

        <?php if ($params->get('tag_list_description', '') > '') : ?>
        <div class="uk-margin"><?= HTMLHelper::_('content.prepare', $params->get('tag_list_description'), '', 'com_tags.tag') ?></div>
        <?php endif ?>

    <?php endif ?>

</div>
<?php endif ?>

<?php if (!$this->items) : ?>
    <p><?= Text::_('COM_TAGS_NO_ITEMS') ?></p>
<?php else :

foreach ($this->items as $i) {

    $item = new \stdClass();
    $item->category_title = '';

    foreach (get_object_vars($i) as $name => $value) {
        $item->{str_replace(['content_item_', 'core_', '_time'], '', $name)} = $value;
    }

    echo $article($item);
}

endif;

if ($params->get('show_pagination')) {
    echo $this->pagination->getPagesLinks();
}
