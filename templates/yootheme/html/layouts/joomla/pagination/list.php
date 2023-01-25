<?php

namespace YOOtheme;

defined('_JEXEC') or die;

$list = $displayData['list'];

$config = app(Config::class);
if (!$config('~theme.blog.pagination_startend')) {
    $list['start']['active'] = false;
    $list['end']['active'] = false;
}

// find out the id of the page, that is the current page
$currentId = 0;

foreach ($list['pages'] as $id => $page) {
    if (!$page['active']) {
        $currentId = $id;
    }
}

// set the range for the inner pages that should be displayed
// this displays + - $range page-buttons around the current page
// due to joomla-restrictions there won't be displayed more than -5 and +4 buttons.
$range = 3;

// start building pagination-list
echo '<ul class="uk-pagination uk-margin-large uk-flex-center">';

// add first-button
if ($list['start']['active'] == 1) {
    echo $list['start']['data'];
}

// add previous-button
if ($list['previous']['active'] == 1) {
    echo $list['previous']['data'];
}

// add buttons for surrounding pages
foreach ($list['pages'] as $id => $page) {
    // only show the buttons that are within the range
    if ($id <= $currentId + $range && $id >= $currentId - $range) {
        echo $page['data'];
    }
}

// add next-button
if ($list['next']['active'] == 1) {
    echo $list['next']['data'];
}

// add last-button
if ($list['end']['active'] == 1) {
    echo $list['end']['data'];
}

// close pagination-list
echo '</ul>';
