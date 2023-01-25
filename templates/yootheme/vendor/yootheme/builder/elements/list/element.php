<?php

namespace YOOtheme;

return [
    'updates' => [
        '2.8.0-beta.0.13' => function ($node) {
            if (in_array(Arr::get($node->props, 'content_style'), ['bold', 'muted'])) {
                $node->props['content_style'] = 'text-' . Arr::get($node->props, 'content_style');
            }
        },

        '2.1.0-beta.0.1' => function ($node) {
            if (Arr::get($node->props, 'list_style') === 'bullet') {
                $node->props['list_marker'] = 'bullet';
                $node->props['list_style'] = '';
            }

            if (Arr::get($node->props, 'list_size') === true) {
                $node->props['list_size'] = 'large';
            } else {
                $node->props['list_size'] = '';
            }

            if (!empty($node->props['icon_ratio'])) {
                $node->props['icon_width'] = round(20 * $node->props['icon_ratio']);
                unset($node->props['icon_ratio']);
            }
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
                if (Arr::get($node->props, 'content_style') === 'h1') {
                    $node->props['content_style'] = 'heading-small';
                }
            }

            if (in_array($style, ['florence', 'max', 'nioh-studio', 'sonic', 'summit', 'trek'])) {
                if (Arr::get($node->props, 'content_style') === 'h2') {
                    $node->props['content_style'] = 'h1';
                }
            }

            if (in_array($style, ['copper-hill'])) {
                if (Arr::get($node->props, 'content_style') === 'h1') {
                    $node->props['content_style'] = 'h2';
                }
            }
        },

        '1.18.10.1' => function ($node) {
            Arr::updateKeys($node->props, [
                'image_inline_svg' => 'image_svg_inline',
                'image_animate_svg' => 'image_svg_animate',
            ]);
        },

        '1.18.0' => function ($node) {
            if (!isset($node->props['content_style'])) {
                $node->props['content_style'] = Arr::get($node->props, 'text_style');
            }
        },
    ],
];
