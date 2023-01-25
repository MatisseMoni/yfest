<?php

namespace YOOtheme;

return [
    'updates' => [
        '2.4.0-beta.0.3' => function ($node) {
            $rename = [
                'slider' => 'overlay-slider',
                'slider_item' => 'overlay-slider_item',
                'map_marker' => 'map_item',
            ];

            $apply = function ($node) use (&$apply, $rename) {
                if (isset($node->type) && !empty($rename[$node->type])) {
                    $node->type = $rename[$node->type];
                }

                if (!empty($node->children)) {
                    array_map($apply, $node->children);
                }
            };
            $apply($node);
        },

        '2.3.0-beta.0.1' => function ($node) {
            $rename = [
                'joomla_module' => 'module',
                'wordpress_widget' => 'module',
                'joomla_position' => 'module_position',
                'wordpress_area' => 'module_position',
            ];

            $apply = function ($node) use (&$apply, $rename) {
                if (isset($node->type) && !empty($rename[$node->type])) {
                    $node->type = $rename[$node->type];
                }

                if (!empty($node->children)) {
                    array_map($apply, $node->children);
                }
            };
            $apply($node);
        },
    ],
];
