<?php

namespace YOOtheme\Builder\Joomla\Source;

class ContentListener
{
    public static function prepareContent($event)
    {
        [$context, $item] = $event->getArguments();

        if ($context === 'com_search.search.article') {
            $item->created_raw = $item->created;
        }
    }
}
