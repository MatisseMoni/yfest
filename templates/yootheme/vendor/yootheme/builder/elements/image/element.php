<?php

namespace YOOtheme;

return [
    'transforms' => [
        'render' => function ($node) {
            // Don't render element if content fields are empty
            return (bool) $node->props['image'];
        },
    ],

    'updates' => [
        '1.20.0-beta.1.1' => function ($node) {
            Arr::updateKeys($node->props, ['maxwidth_align' => 'block_align']);
        },

        '1.18.10.1' => function ($node) {
            Arr::updateKeys($node->props, [
                'image_inline_svg' => 'image_svg_inline',
                'image_animate_svg' => 'image_svg_animate',
            ]);
        },

        '1.18.0' => function ($node) {
            if (Arr::get($node->props, 'link_target') === true) {
                $node->props['link_target'] = 'blank';
            }

            if (
                !isset($node->props['image_box_decoration']) &&
                Arr::get($node->props, 'image_box_shadow_bottom') === true
            ) {
                $node->props['image_box_decoration'] = 'shadow';
            }
        },
    ],
];
