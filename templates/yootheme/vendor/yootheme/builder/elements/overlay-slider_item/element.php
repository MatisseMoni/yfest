<?php

namespace YOOtheme;

return [
    'transforms' => [
        'render' => function ($node, $params) {
            // Link Aria Label
            $node->props['link_aria_label'] =
                $node->props['link_text'] ?:
                $params['parent']->props['link_text'] ?:
                app(View::class)->striptags($node->props['title']);

            // Display
            foreach (['title', 'meta', 'content', 'link', 'hover_image'] as $key) {
                if (!$params['parent']->props["show_{$key}"]) {
                    $node->props[$key] = '';
                }
            }

            // Don't render element if content fields are empty
            return $node->props['image'] || $node->props['video'] || $node->props['hover_image'];
        },
    ],
];
