<?php

namespace YOOtheme;

use YOOtheme\Theme\HighlightListener;

return [
    'filters' => [
        'builder_content' => [
            HighlightListener::class => 'checkContent',
        ],
    ],
];
