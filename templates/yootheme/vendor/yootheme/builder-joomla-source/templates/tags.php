<?php

use Joomla\Component\Tags\Site\Helper\RouteHelper;

if ($args['show_link'] && $args['link_style']) {
    echo '<span class="uk-' . $args['link_style'] . '">';
}

echo implode($args['separator'], array_map(function ($tag) use ($args) {

    if (empty($args['show_link'])) {
        return $tag->title;
    }

    $route = RouteHelper::getTagRoute("{$tag->tag_id}:{$tag->alias}");

    return "<a href=\"{$route}\">{$tag->title}</a>";

}, $tags ?: []));

if ($args['show_link'] && $args['link_style']) {
    echo '</span>';
}
