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

        '2.7.0-beta.0.4' => function ($node) {
            if (empty($node->props['slider_width']) || empty($node->props['slider_height'])) {
                unset($node->props['slider_min_height']);
            }
        },

        '2.2.2.1' => function ($node) {
            Arr::updateKeys($node->props, [
                'link_type' => function ($value) {
                    if ($value === 'content') {
                        return [
                            'title_link' => true,
                            'link_text' => '',
                        ];
                    } elseif ($value === 'element') {
                        return [
                            'overlay_link' => true,
                            'link_text' => '',
                        ];
                    }
                },
            ]);
        },

        '2.1.0-beta.0.1' => function ($node) {
            if (Arr::get($node->props, 'overlay_maxwidth') === 'xxlarge') {
                $node->props['overlay_maxwidth'] = '2xlarge';
            }
        },

        '1.22.0-beta.0.1' => function ($node) {
            Arr::updateKeys($node->props, ['slider_gutter' => 'slider_gap']);
        },

        '1.20.0-beta.1.1' => function ($node) {
            Arr::updateKeys($node->props, ['maxwidth_align' => 'block_align']);
        },

        '1.20.0-beta.0.1' => function ($node) {
            if (Arr::get($node->props, 'title_style') === 'heading-hero') {
                $node->props['title_style'] = 'heading-xlarge';
            }

            if (Arr::get($node->props, 'title_style') === 'heading-primary') {
                $node->props['title_style'] = 'heading-medium';
            }

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

            if (in_array($style, ['juno', 'vibe', 'yard'])) {
                if (Arr::get($node->props, 'title_style') === 'heading-xlarge') {
                    $node->props['title_style'] = 'heading-medium';
                }
            }

            if (
                in_array($style, [
                    'district',
                    'florence',
                    'flow',
                    'nioh-studio',
                    'summit',
                    'vision',
                ])
            ) {
                if (Arr::get($node->props, 'title_style') === 'heading-xlarge') {
                    $node->props['title_style'] = 'heading-large';
                }
            }

            if (in_array($style, ['lilian'])) {
                if (Arr::get($node->props, 'title_style') === 'heading-xlarge') {
                    $node->props['title_style'] = 'heading-2xlarge';
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

            $node->props['link_type'] = 'element';
        },

        '1.18.10.3' => function ($node) {
            if (Arr::get($node->props, 'meta_align') === 'top') {
                if (!empty($node->props['meta_margin'])) {
                    $node->props['title_margin'] = $node->props['meta_margin'];
                }
                $node->props['meta_margin'] = '';
            }
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
