<?php

namespace YOOtheme;

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\User\User;

HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers');

[$view, $user] = app(View::class, User::class);

// Parameter shortcuts
$item = $this->item;
$params = $item->params;
$uncategorised = $item->category_alias == 'uncategorised';

// Heading
if ($params->get('show_page_heading')) {
    echo "<h1>{$this->escape($this->params->get('page_heading'))}</h1>";
}

// Article
$article = [
    'layout' => $uncategorised ? '' : 'blog',
    'article' => $item,
    'params' => $params,
    'content' => '',
    'single' => true,
];

// Title
$params->set('link_titles', false);

// Content
if ($params->get('access-view')) {

    if ($params->get('urls_position') === '0') {
        $article['content'] .= $this->loadTemplate('links');
    }

    $article['content'] .= $item->text;

    if ($params->get('urls_position') === '1') {
        $article['content'] .= $this->loadTemplate('links');
    }

    $article['image'] = 'fulltext';

// Optional teaser intro text for guests
} elseif ($params->get('show_noauth') && $user->get('guest')) {

    $article['content'] .= $item->introtext;

    // Optional link to let them register to see the whole article
    $item->readmore = $params->get('show_readmore') && $item->fulltext;
}

// Icons
if ($this->print) {
    $article['icons'] = ['print' => HTMLHelper::_('icon.print_screen', $item, $params)];
}

echo $view('~theme/templates/article' . ($uncategorised ? '{-page,}' : '{-blog,}'), $article);
