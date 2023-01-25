<?php

namespace YOOtheme;

return [
    '3.0.5.1' => function ($node) {
        if (
            (Arr::get($node->props, 'animation') == 'parallax' ||
                Arr::get($node->props, 'item_animation') == 'parallax') &&
            !is_numeric(Arr::get($node->props, 'parallax_easing'))
        ) {
            Arr::set($node->props, 'parallax_easing', '1');
        }
    },
    '3.0.0-beta.3.1' => function ($node) {
        if (Arr::get($node->props, 'parallax_target') === false) {
            Arr::del($node->props, 'parallax_target');
        }
    },
    '3.0.0-beta.2.1' => function ($node) {
        /** @var Config $config */
        $config = app(Config::class);
        [$style] = explode(':', $config('~theme.style'));

        if (
            $style === 'fjord' &&
            !array_key_exists('@base-h6-font-size', $config('~theme.less', [])) &&
            Arr::get($node->props, 'title_style') === 'h6' &&
            Arr::get($node->props, 'title_element') === 'h4'
        ) {
            $node->props['title_style'] = '';
        }
    },
    '2.8.0-beta.0.3' => function ($node) {
        foreach (['x', 'y', 'scale', 'rotate', 'opacity'] as $prop) {
            $key = "parallax_{$prop}";

            // Cleanup old values from before introducing '_start' and '_end' props
            Arr::del($node->props, $key);

            $start = implode(
                ',',
                array_map(function ($value) {
                    return trim($value);
                }, explode(',', Arr::get($node->props, "{$key}_start", '')))
            );
            $end = implode(
                ',',
                array_map(function ($value) {
                    return trim($value);
                }, explode(',', Arr::get($node->props, "{$key}_end", '')))
            );
            if ($start !== '' || $end !== '') {
                $default = in_array($prop, ['scale', 'opacity']) ? 1 : 0;
                Arr::set(
                    $node->props,
                    $key,
                    implode(',', [$start !== '' ? $start : $default, $end !== '' ? $end : $default])
                );
            }
            Arr::del($node->props, "{$key}_start");
            Arr::del($node->props, "{$key}_end");
        }
    },
    '2.8.0-beta.0.1' => function ($node) {
        if (Arr::get($node->props, 'parallax_target')) {
            $node->props['parallax_target'] = '!.uk-section';
        }

        Arr::updateKeys($node->props, [
            'parallax_viewport' => function ($value) {
                if (!empty($value) && ($viewport = 100 * (1 - (float) $value))) {
                    return ['parallax_end' => $viewport . 'vh + ' . $viewport . '%'];
                }
            },
        ]);
    },

    '2.7.11.1' => function ($node) {
        unset($node->props['pointer_events']);
    },

    '2.1.1.1' => function ($node) {
        /** @var Config $config */
        $config = app(Config::class);

        [$style] = explode(':', $config('~theme.style'));

        if ($style == 'horizon') {
            if (
                (Arr::get($node->props, 'title_style') === 'h6' ||
                    (Arr::get($node->props, 'title_element') === 'h6' &&
                        empty(Arr::get($node->props, 'title_style')))) &&
                empty(Arr::get($node->props, 'title_color'))
            ) {
                $node->props['title_color'] = 'primary';
            }
        }

        if ($style == 'fjord') {
            if (
                (Arr::get($node->props, 'title_style') === 'h4' ||
                    (Arr::get($node->props, 'title_element') === 'h4' &&
                        empty(Arr::get($node->props, 'title_style')))) &&
                empty(Arr::get($node->props, 'title_color'))
            ) {
                $node->props['title_color'] = 'primary';
            }
        }
    },

    '2.1.0-beta.0.1' => function ($node, array $params) {
        $type = $params['type'];

        if (Arr::get($node->props, 'maxwidth') === 'xxlarge') {
            $node->props['maxwidth'] = '2xlarge';
        }

        // move declaration of uk-hidden class to visibility settings
        if ($type->element && empty($node->props['visibility']) && !empty($node->props['class'])) {
            $node->props['class'] = trim(
                preg_replace_callback(
                    '/(^|\s+)uk-hidden@(s|m|l|xl)/',
                    function ($match) use ($node) {
                        $node->props['visibility'] = 'hidden-' . $match[2];
                        return '';
                    },
                    $node->props['class']
                )
            );
        }
    },

    '1.22.0-beta.0.1' => function ($node) {
        if (isset($node->type) && in_array($node->type, ['joomla_position', 'wordpress_area'])) {
            Arr::updateKeys($node->props, [
                'grid_divider' => 'divider',
                'grid_gutter' => function ($value) {
                    return ['column_gap' => $value, 'row_gap' => $value];
                },
            ]);
        }
    },

    '1.20.0-beta.1.1' => function ($node) {
        if (
            isset($node->type) &&
            in_array($node->type, [
                'joomla_position',
                'wordpress_area',
                'joomla_module',
                'wordpress_widget',
            ])
        ) {
            Arr::updateKeys($node->props, ['maxwidth_align' => 'block_align']);
        }
    },

    '1.20.0-beta.0.1' => function ($node) {
        if (isset($node->type) && in_array($node->type, ['joomla_module', 'wordpress_widget'])) {
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
        }
    },
];
