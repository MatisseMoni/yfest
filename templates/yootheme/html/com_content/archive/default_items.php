<?php

namespace YOOtheme;

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;

HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers');

$view = app(View::class);

// Parameter shortcuts
$params = $this->params;

foreach ($this->items as $item) {

    // Article
    $article = [
        'article' => $item,
        'params' => $params->toArray() + $item->params->toArray(),
    ];

    // Content
    if ($params->get('show_intro')) {
        $article['content'] = HTMLHelper::_('string.truncate', $item->introtext, $params->get('introtext_limit'));
    }

    echo $view('~theme/templates/article{-archive,}', $article);
}

echo $this->pagination->getPagesLinks();
