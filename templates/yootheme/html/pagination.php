<?php

use Joomla\CMS\Layout\LayoutHelper;
use YOOtheme\Path;

defined('_JEXEC') or die;

// prefer child theme's pagination
if (Path::get(__FILE__) !== $file = Path::get('~theme/html/pagination.php')) {
    return include $file;
}

function pagination_item_active($item) {
    return LayoutHelper::render('joomla.pagination.link', ['data' => $item, 'active' => true]);
}

function pagination_item_inactive($item) {
    return LayoutHelper::render('joomla.pagination.link', ['data' => $item, 'active' => false]);
}
