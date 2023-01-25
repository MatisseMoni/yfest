<?php

namespace YOOtheme;

return [
    'transforms' => [
        'render' => function ($node, $params) {
            // Display
            foreach (['title', 'meta', 'content', 'link'] as $key) {
                if (!$params['parent']->props["show_{$key}"]) {
                    $node->props[$key] = '';
                }
            }

            // Don't render element if content fields are empty
            return $node->props['title'] != '' ||
                $node->props['meta'] != '' ||
                $node->props['content'] != '';
        },
    ],
];
