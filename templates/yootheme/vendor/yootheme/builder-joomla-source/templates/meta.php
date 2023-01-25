<?php

use Joomla\CMS\Categories\Categories;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Site\Helper\RouteHelper;
use YOOtheme\Builder\Joomla\Source\UserHelper;
use YOOtheme\Path;

$author = $published = $category = $tag = '';

// Author
if ($args['show_author']) {

    $author = $article->created_by_alias ?: $article->author;

    if (!isset($article->contact_link)) {
        $article->contact_link = UserHelper::getContactLink($article->created_by);
    }

    if (!empty($article->contact_link)) {
        $author = HTMLHelper::_('link', $article->contact_link, $author);
    }
}

// Publish date
if ($args['show_publish_date'] && $article->publish_up !== Factory::getDbo()->getNullDate()) {
    $published = HTMLHelper::_('date', $article->publish_up, $args['date_format'] ?: Text::_('DATE_FORMAT_LC3'));
    $published = '<time datetime="' . HTMLHelper::_('date', $article->publish_up, 'c') . "\">{$published}</time>";
}

// Category
if ($args['show_taxonomy'] === 'category') {

    $category = $article->category_title;

    if ($article->catid) {

        if (!$category) {
            $category = Categories::getInstance('content')->get($article->catid);
            if ($category) {
                $category = $category->title;
            }
        }

        $category = HTMLHelper::_('link', Route::_(RouteHelper::getCategoryRoute($article->catid)), $category);
    }
}

// Tag
if ($tags && $args['show_taxonomy'] === 'tag') {
    $tag = $view->render(Path::get('./tags'), [
        'tags' => $tags,
        'args' => [
            'separator' => ', ',
            'show_link' => true,
            'link_style' => $args['link_style'],
        ],
    ]);
}

if (!$published && !$author && !$category && !$tag) {
    return;
}

if ($args['link_style']) {
    echo "<span class=\"uk-{$args['link_style']}\">";
}

switch ($args['format']) {

    case 'list':

        echo implode(" {$args['separator']} ", array_filter([$published, $author, $category, $tag]));
        break;

    default: // sentence

        if ($author && $published) {
            Text::printf('TPL_YOOTHEME_META_AUTHOR_DATE', $author, $published);
        } elseif ($author) {
            Text::printf('TPL_YOOTHEME_META_AUTHOR', $author);
        } elseif ($published) {
            Text::printf('TPL_YOOTHEME_META_DATE', $published);
        }

        if ($category) {
            echo ' ';
            Text::printf('TPL_YOOTHEME_META_CATEGORY', $category);
        } elseif ($tag) {
            echo ' ';
            Text::printf('TPL_YOOTHEME_META_TAG', $tag);
        }
}

if ($args['link_style']) {
    echo '</span>';
}
