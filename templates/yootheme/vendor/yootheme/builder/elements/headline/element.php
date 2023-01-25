<?php

namespace YOOtheme;

return [
    'transforms' => [
        'render' => function ($node) {
            // Don't render element if content fields are empty
            return $node->props['content'] != '';
        },
    ],

    'updates' => [
        '2.8.0-beta.0.13' => function ($node) {
            if (in_array(Arr::get($node->props, 'title_style'), ['meta', 'lead'])) {
                $node->props['title_style'] = 'text-' . Arr::get($node->props, 'title_style');
            }
        },

        '1.20.0-beta.1.1' => function ($node) {
            Arr::updateKeys($node->props, ['maxwidth_align' => 'block_align']);
        },

        '1.20.0-beta.0.1' => function ($node) {
            /** @var Config $config */
            $config = app(Config::class);

            [$style] = explode(':', $config('~theme.style'));

            if (Arr::get($node->props, 'title_style') === 'heading-hero') {
                $node->props['title_style'] = 'heading-xlarge';
            }

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

            if (in_array($style, ['fuse', 'horizon', 'joline', 'juno', 'lilian', 'vibe', 'yard'])) {
                if (Arr::get($node->props, 'title_style') === 'heading-medium') {
                    $node->props['title_style'] = 'heading-small';
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
    ],
];
