<?php

namespace YOOtheme;

return [
    'transforms' => [
        'render' => function ($node) {
            // Don't render element if content fields are empty
            return $node->props['title'] != '' || $node->props['content'] != '';
        },
    ],

    'updates' => [
        '1.20.0-beta.4' => function ($node) {
            Arr::updateKeys($node->props, ['maxwidth_align' => 'block_align']);
        },
    ],
];
