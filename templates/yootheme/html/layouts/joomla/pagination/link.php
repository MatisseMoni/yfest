<?php

namespace YOOtheme;

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

$item = $displayData['data'];

if ($item->active) {
    echo "<li class=\"uk-active\"><span>{$item->text}</span></li>";
} else {
    $cls = '';
    $title = '';

    if ($item->text == Text::_('JNEXT')) {
        $title = $item->text;
        $item->text = '<span uk-pagination-next></span>';
        $cls = 'next';
    } elseif ($item->text == Text::_('JPREV')) {
        $title = $item->text;
        $item->text = '<span uk-pagination-previous></span>';
        $cls = 'previous';
    } elseif ($item->text == Text::_('JLIB_HTML_START')) {
        $cls = 'first';
    } elseif ($item->text == Text::_('JLIB_HTML_END')) {
        $cls = 'last';
    }

    echo "<li><a class=\"{$cls}\" href=\"{$item->link}\" title=\"{$title}\">{$item->text}</a></li>";
}
