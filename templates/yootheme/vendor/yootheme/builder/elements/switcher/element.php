<?php

namespace YOOtheme;

return [
    'updates' => [
        '2.8.0-beta.0.13' => function ($node) {
            foreach (['title_style', 'meta_style', 'content_style'] as $prop) {
                if (in_array(Arr::get($node->props, $prop), ['meta', 'lead'])) {
                    $node->props[$prop] = 'text-' . Arr::get($node->props, $prop);
                }
            }
        },

        '2.7.0-beta.0.6' => function ($node) {
            if (empty($node->props['nav'])) {
                $node->props['nav'] = 'tab';
            }

            unset(
                $node->props['slidenav'],
                $node->props['slidenav_hover'],
                $node->props['slidenav_large'],
                $node->props['slidenav_margin'],
                $node->props['slidenav_breakpoint'],
                $node->props['slidenav_color'],
                $node->props['slidenav_outside_breakpoint'],
                $node->props['slidenav_outside_color'],
                $node->props['slidenav_outside_color'],
                $node->props['slidenav_outside_color'],
                $node->props['slidenav_outside_color']
            );
        },

        '2.7.0-beta.0.3' => function ($node) {
            Arr::updateKeys($node->props, ['switcher_thumbnail_height' => 'thumbnav_height']);
        },

        '2.7.0-beta.0.2' => function ($node) {
            Arr::updateKeys($node->props, [
                'switcher_style' => 'nav',
                'switcher_thumbnail_nowrap' => 'thumbnav_nowrap',
                'switcher_thumbnail_width' => 'thumbnav_width',
                'switcher_thumbnail_height' => 'thumbnav_height',
                'switcher_thumbnail_svg_inline' => 'thumbnav_svg_inline',
                'switcher_thumbnail_svg_color' => 'thumbnav_svg_color',
                'switcher_position' => 'nav_position',
                'nav_primary' => 'nav_style_primary',
                'switcher_align' => 'nav_align',
                'switcher_margin' => 'nav_margin',
                'switcher_grid_width' => 'nav_grid_width',
                'switcher_grid_column_gap' => 'nav_grid_column_gap',
                'switcher_grid_row_gap' => 'nav_grid_row_gap',
                'switcher_grid_breakpoint' => 'nav_grid_breakpoint',
                'switcher_vertical_align' => 'nav_vertical_align',
            ]);
        },

        '2.1.0-beta.0.1' => function ($node) {
            if (Arr::get($node->props, 'title_grid_width') === 'xxlarge') {
                $node->props['title_grid_width'] = '2xlarge';
            }

            if (Arr::get($node->props, 'image_grid_width') === 'xxlarge') {
                $node->props['image_grid_width'] = '2xlarge';
            }
        },

        '1.22.0-beta.0.1' => function ($node) {
            Arr::updateKeys($node->props, [
                'switcher_breakpoint' => 'switcher_grid_breakpoint',
                'title_breakpoint' => 'title_grid_breakpoint',
                'image_breakpoint' => 'image_grid_breakpoint',
                'switcher_gutter' => function ($value) {
                    return [
                        'switcher_grid_column_gap' => $value,
                        'switcher_grid_row_gap' => $value,
                    ];
                },
                'title_gutter' => function ($value) {
                    return ['title_grid_column_gap' => $value, 'title_grid_row_gap' => $value];
                },
                'image_gutter' => function ($value) {
                    return ['image_grid_column_gap' => $value, 'image_grid_row_gap' => $value];
                },
            ]);
        },

        '1.20.0-beta.1.1' => function ($node) {
            Arr::updateKeys($node->props, ['maxwidth_align' => 'block_align']);
        },

        '1.20.0-beta.0.1' => function ($node) {
            /** @var Config $config */
            $config = app(Config::class);

            [$style] = explode(':', $config('~theme.style'));

            if (Arr::get($node->props, 'title_style') === 'heading-primary') {
                $node->props['title_style'] = 'heading-medium';
            }

            if (
                in_array($style, [
                    'craft',
                    'district',
                    'jack-backer',
                    'tomsen-brody',
                    'vision',
                    'florence',
                    'max',
                    'nioh-studio',
                    'sonic',
                    'summit',
                    'trek',
                ])
            ) {
                if (
                    Arr::get($node->props, 'title_style') === 'h1' ||
                    (empty($node->props['title_style']) &&
                        Arr::get($node->props, 'title_element') === 'h1')
                ) {
                    $node->props['title_style'] = 'heading-small';
                }
            }

            if (in_array($style, ['florence', 'max', 'nioh-studio', 'sonic', 'summit', 'trek'])) {
                if (Arr::get($node->props, 'title_style') === 'h2') {
                    $node->props['title_style'] =
                        Arr::get($node->props, 'title_element') === 'h1' ? '' : 'h1';
                } elseif (
                    empty($node->props['title_style']) &&
                    Arr::get($node->props, 'title_element') === 'h2'
                ) {
                    $node->props['title_style'] = 'h1';
                }
            }

            if (in_array($style, ['fuse', 'horizon', 'joline', 'juno', 'lilian', 'vibe', 'yard'])) {
                if (Arr::get($node->props, 'title_style') === 'heading-medium') {
                    $node->props['title_style'] = 'heading-small';
                }
            }

            if (in_array($style, ['copper-hill'])) {
                if (Arr::get($node->props, 'title_style') === 'heading-medium') {
                    $node->props['title_style'] =
                        Arr::get($node->props, 'title_element') === 'h1' ? '' : 'h1';
                } elseif (Arr::get($node->props, 'title_style') === 'h1') {
                    $node->props['title_style'] =
                        Arr::get($node->props, 'title_element') === 'h2' ? '' : 'h2';
                } elseif (
                    empty($node->props['title_style']) &&
                    Arr::get($node->props, 'title_element') === 'h1'
                ) {
                    $node->props['title_style'] = 'h2';
                }
            }

            if (in_array($style, ['trek', 'fjord'])) {
                if (Arr::get($node->props, 'title_style') === 'heading-medium') {
                    $node->props['title_style'] = 'heading-large';
                }
            }
        },

        '1.19.0-beta.0.1' => function ($node) {
            if (Arr::get($node->props, 'meta_align') === 'top') {
                $node->props['meta_align'] = 'above-title';
            }

            if (Arr::get($node->props, 'meta_align') === 'bottom') {
                $node->props['meta_align'] = 'below-title';
            }
        },

        '1.18.10.3' => function ($node) {
            if (Arr::get($node->props, 'meta_align') === 'top') {
                if (!empty($node->props['meta_margin'])) {
                    $node->props['title_margin'] = $node->props['meta_margin'];
                }
                $node->props['meta_margin'] = '';
            }
        },

        '1.18.10.1' => function ($node) {
            Arr::updateKeys($node->props, [
                'image_inline_svg' => 'image_svg_inline',
                'image_animate_svg' => 'image_svg_animate',
                'switcher_thumbnail_inline_svg' => 'switcher_thumbnail_svg_inline',
            ]);
        },

        '1.18.0' => function ($node) {
            if (Arr::get($node->props, 'switcher_style') === 'thumbnail') {
                $node->props['switcher_style'] = 'thumbnav';
            }

            if (
                !isset($node->props['image_box_decoration']) &&
                Arr::get($node->props, 'image_box_shadow_bottom') === true
            ) {
                $node->props['image_box_decoration'] = 'shadow';
            }

            if (
                !isset($node->props['meta_color']) &&
                Arr::get($node->props, 'meta_style') === 'muted'
            ) {
                $node->props['meta_color'] = 'muted';
                $node->props['meta_style'] = '';
            }
        },
    ],
];
