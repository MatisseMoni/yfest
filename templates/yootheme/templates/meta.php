<?php

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Site\Helper\RouteHelper;

$author = $published = $category = '';

// Author
if ($params['show_author']) {

    $author = $article->created_by_alias ?: $article->author;

    if ($params['link_author'] && !empty($article->contact_link)) {
        $author = HTMLHelper::_('link', $article->contact_link, $author);
    }
}

// Publish date
if ($params['show_publish_date']) {
    $published = HTMLHelper::_('date', $article->publish_up, Text::_('DATE_FORMAT_LC3'));
    $published = '<time datetime="' . HTMLHelper::_('date', $article->publish_up, 'c') . "\">{$published}</time>";
}

// Category
if ($params['show_category']) {

    $category = $article->category_title;

    if ($params['link_category'] && $article->catid) {
        $category = HTMLHelper::_('link', Route::_(RouteHelper::getCategoryRoute($article->catid)), $category);
    }
}

if ($published || $author || $category) {

    $attrs_meta['class'][] = "{$this->margin($params['meta_margin'])} uk-margin-remove-bottom";

    switch ($params['meta_style']) {

        case 'list':

            $parts = array_filter([
                $published ?: '',
                $author ? "<span>{$author}</span>" : '',
                $category ?: '',
            ]);

            $attrs_meta['class'][] = 'uk-subnav uk-subnav-divider';
            $attrs_meta['class'][] = $params['header_align'] ? 'uk-flex-center' : '';

            ?>
            <ul<?= $this->attrs($attrs_meta) ?>>
                <?php foreach ($parts as $part) : ?>
                    <li><?= $part ?></li>
                <?php endforeach ?>
            </ul>
            <?php
            break;

        default: // sentence

            $attrs_meta['class'][] = 'uk-article-meta';
            $attrs_meta['class'][] = $params['header_align'] ? 'uk-text-center' : '';

            ?>
            <p<?= $this->attrs($attrs_meta) ?>>
                <?php

                if ($author && $published) {
                    Text::printf('TPL_YOOTHEME_META_AUTHOR_DATE', $author, $published);
                } elseif ($author) {
                    Text::printf('TPL_YOOTHEME_META_AUTHOR', $author);
                } elseif ($published) {
                    Text::printf('TPL_YOOTHEME_META_DATE', $published);
                }

                ?>
                <?= $category ? Text::sprintf('TPL_YOOTHEME_META_CATEGORY', $category) : '' ?>
            </p>
        <?php
    }

}
