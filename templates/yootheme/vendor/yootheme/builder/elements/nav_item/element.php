<?php

namespace YOOtheme;

return [
    'transforms' => [
        'render' => function ($node, $params) {
            // Display
            foreach (['meta', 'image'] as $key) {
                if (!$params['parent']->props["show_{$key}"]) {
                    $node->props[$key] = '';
                    if ($key === 'image') {
                        $node->props['icon'] = '';
                    }
                }
            }

            // Don't render element if content fields are empty
            return $node->props['content'] != '';
        },
    ],

    'updates' => [
        '3.0.10.1' => function ($node) {
            if (Arr::get($node->props, 'type') === 'header') {
                $node->props['type'] = 'heading';
            }
        },
    ],
];
