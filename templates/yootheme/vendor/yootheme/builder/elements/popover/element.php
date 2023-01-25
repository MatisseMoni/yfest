<?php

namespace YOOtheme;

return [
    'transforms' => [
        'render' => function ($node) {
            if (empty($node->props['background_image'])) {
                $node->props['background_image'] = Url::to(
                    '~yootheme/theme/assets/images/element-image-placeholder.png'
                );
            }
        },
    ],

    'updates' => [
        '2.8.0-beta.0.13' => function ($node) {
            foreach (['title_style', 'meta_style', 'content_style'] as $prop) {
                if (in_array(Arr::get($node->props, $prop), ['meta', 'lead'])) {
                    $node->props[$prop] = 'text-' . Arr::get($node->props, $prop);
                }
            }
        },

        '2.0.0-beta.5.1' => function ($node) {
            Arr::updateKeys($node->props, [
                'link_type' => function ($value) {
                    if ($value === 'content') {
                        return [
                            'title_link' => true,
                            'image_link' => true,
                            'link_text' => '',
                        ];
                    } elseif ($value === 'element') {
                        return [
                            'card_link' => true,
                            'link_text' => '',
                        ];
                    }
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

            if (in_array($style, ['copper-hill'])) {
                if (Arr::get($node->props, 'title_style') === 'h1') {
                    $node->props['title_style'] =
                        Arr::get($node->props, 'title_element') === 'h2' ? '' : 'h2';
                } elseif (
                    empty($node->props['title_style']) &&
                    Arr::get($node->props, 'title_element') === 'h1'
                ) {
                    $node->props['title_style'] = 'h2';
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

            if (Arr::get($node->props, 'link_style') === 'card') {
                $node->props['link_type'] = 'element';
                $node->props['link_style'] = 'default';
            }

            Arr::updateKeys($node->props, ['image_card' => 'image_card_padding']);
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
            ]);
        },

        '1.18.0' => function ($node) {
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
