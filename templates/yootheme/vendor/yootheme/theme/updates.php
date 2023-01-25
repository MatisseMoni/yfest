<?php

namespace YOOtheme;

return [
    '3.0.1.1' => function ($config) {
        if (Arr::get($config, 'image_metadata')) {
            Arr::set($config, 'webp', false);
        }

        return $config;
    },
    '3.0.0-beta.3.2' => function ($config) {
        Arr::del($config, 'webp');

        return $config;
    },
    '3.0.0-beta.3.1' => function ($config) {
        if (
            Arr::get($config, '~theme.site.image_effect') == 'parallax' &&
            !is_numeric(Arr::get($config, '~theme.site.image_parallax_easing'))
        ) {
            Arr::set($config, '~theme.site.image_parallax_easing', '1');
        }

        return $config;
    },
    '3.0.0-beta.1.8' => function ($config) {
        if (Arr::get($config, 'mobile.dialog.dropbar.animation') == 'slide') {
            Arr::set($config, 'mobile.dialog.dropbar.animation', 'reveal-top');
        }

        return $config;
    },
    '3.0.0-beta.1.7' => function ($config) {
        Arr::updateKeys($config, [
            'navbar.boundary_align' => 'navbar.dropdown_target',
            'mobile.dialog.dropdown.animation' => 'mobile.dialog.dropbar.animation',
        ]);

        if (Arr::get($config, 'mobile.dialog.layout') == 'dropdown-top') {
            Arr::set($config, 'mobile.dialog.layout', 'dropbar-top');
        }

        if (Arr::get($config, 'mobile.dialog.layout') == 'dropdown-center') {
            Arr::set($config, 'mobile.dialog.layout', 'dropbar-center');
        }

        // Menu Items
        if (Arr::has($config, 'menu.items')) {
            $items = Arr::get($config, 'menu.items', []);
            foreach ($items as &$item) {
                Arr::updateKeys($item, [
                    'dropdown.justify' => 'dropdown.stretch',
                ]);
                if (Arr::get($item, 'dropdown.stretch') == 'dropbar') {
                    Arr::set($item, 'dropdown.stretch', 'navbar-container');
                }
            }
            Arr::set($config, 'menu.items', $items);
        }

        return $config;
    },
    '3.0.0-beta.1.6' => function ($config) {
        // Menu Positions
        if (Arr::has($config, 'menu.positions')) {
            $positions = Arr::get($config, 'menu.positions', []);
            foreach ($positions as &$position) {
                if (empty(Arr::get($position, 'style'))) {
                    Arr::set($position, 'style', 'default');
                }
            }
            Arr::set($config, 'menu.positions', $positions);
        }

        return $config;
    },
    '3.0.0-beta.1.5' => function ($config) {
        Arr::updateKeys($config, [
            'dialog.menu_style' => 'menu.positions.dialog.style',
            'dialog.menu_divider' => 'menu.positions.dialog.divider',
            'mobile.dialog.menu_style' => 'menu.positions.dialog-mobile.style',
            'mobile.dialog.menu_divider' => 'menu.positions.dialog-mobile.divider',
        ]);

        return $config;
    },
    '3.0.0-beta.1.4' => function ($config) {
        // Menu Items
        if (Arr::has($config, 'menu.items')) {
            $items = Arr::get($config, 'menu.items', []);
            foreach ($items as &$item) {
                Arr::del($item, 'image_width');
                Arr::del($item, 'image_height');
                Arr::del($item, 'image_svg_inline');
                Arr::del($item, 'icon_width');
                Arr::del($item, 'image_margin');
            }
            Arr::set($config, 'menu.items', $items);
        }

        return $config;
    },
    '3.0.0-beta.1.3' => function ($config) {
        Arr::update($config, 'menu.positions', function ($positions) {
            foreach ($positions ?: [] as $position => $menu) {
                $positions[$position] = isset($menu) ? ['menu' => $menu] : null;
            }
            return $positions;
        });

        return $config;
    },
    '3.0.0-beta.1.1' => function ($config) {
        // Less
        Arr::updateKeys($config, [
            'less.@navbar-dropdown-dropbar-margin-top' =>
                'less.@navbar-dropdown-dropbar-padding-top',
            'less.@navbar-dropdown-dropbar-margin-bottom' =>
                'less.@navbar-dropdown-dropbar-padding-bottom',
        ]);

        return $config;
    },
    '2.8.0-beta.0.14' => function ($config) {
        // Less
        Arr::updateKeys($config, [
            'less.@nav-primary-item-font-size' => 'less.@nav-primary-font-size',
            'less.@nav-primary-item-line-height' => 'less.@nav-primary-line-height',
        ]);

        return $config;
    },
    '2.8.0-beta.0.11' => function ($config) {
        Arr::del($config, 'mobile.dialog.dropdown.animation');
        Arr::del($config, 'mobile.dialog.dropdown');

        return $config;
    },
    '2.8.0-beta.0.10' => function ($config) {
        [$style] = explode(':', Arr::get($config, 'style'));
        if ($style == 'makai') {
            Arr::set($config, 'mobile.header.layout', 'horizontal-right');
        }

        return $config;
    },
    '2.8.0-beta.0.9' => function ($config) {
        if (Arr::get($config, 'mobile.dialog.layout') == 'dropdown') {
            Arr::set($config, 'mobile.dialog.layout', 'dropdown-top');
        }
        Arr::set($config, 'mobile.dialog.dropdown.animation', 'slide');
        Arr::del($config, 'mobile.dialog.dropdown');

        return $config;
    },
    '2.8.0-beta.0.8' => function ($config) {
        Arr::updateKeys($config, [
            'dialog.menu_center' => 'dialog.text_center',
            'mobile.dialog.menu_center' => 'mobile.dialog.text_center',
        ]);

        return $config;
    },
    '2.8.0-beta.0.7' => function ($config) {
        Arr::updateKeys($config, [
            'dialog.menu_center' => 'dialog.text_center',
            'mobile.dialog.menu_center' => 'mobile.dialog.text_center',
        ]);
        // Menu Items
        if (Arr::has($config, 'menu.items')) {
            $items = Arr::get($config, 'menu.items', []);
            foreach ($items as &$item) {
                if (Arr::get($item, 'dropdown.width') == 400) {
                    Arr::del($item, 'dropdown.width');
                }
                Arr::updateKeys($item, [
                    'image-margin' => 'image_margin',
                    'image-only' => 'image_only',
                ]);
            }
            Arr::set($config, 'menu.items', $items);
        }

        return $config;
    },
    '2.8.0-beta.0.6' => function ($config) {
        // Less
        Arr::updateKeys($config, [
            'less.@offcanvas-bar-width-m' => 'less.@offcanvas-bar-width-s',
            'less.@offcanvas-bar-padding-vertical-m' => 'less.@offcanvas-bar-padding-vertical-s',
            'less.@offcanvas-bar-padding-horizontal-m' =>
                'less.@offcanvas-bar-padding-horizontal-s',
        ]);

        return $config;
    },
    '2.8.0-beta.0.5' => function ($config) {
        // Convert builder menu items from type 'layout' to 'fragment'
        if (Arr::has($config, 'menu.items')) {
            $items = Arr::get($config, 'menu.items', []);
            foreach ($items as &$item) {
                if (Arr::get($item, 'content')) {
                    Arr::set($item, 'content.type', 'fragment');
                }
            }
            Arr::set($config, 'menu.items', $items);
        }

        return $config;
    },
    '2.8.0-beta.0.4' => function ($config) {
        // Mobile Header
        if (Arr::get($config, 'mobile.logo') == 'left') {
            if (Arr::get($config, 'mobile.toggle') == 'left') {
                Arr::set($config, 'mobile.header.layout', 'horizontal-left');
            } else {
                Arr::set($config, 'mobile.header.layout', 'horizontal-right');
            }
        } else {
            Arr::set($config, 'mobile.header.layout', 'horizontal-center-logo');
        }
        Arr::del($config, 'mobile.logo');
        if (Arr::get($config, 'mobile.toggle') == 'left') {
            Arr::set($config, 'mobile.dialog.toggle', 'navbar-mobile:start');
        } else {
            Arr::set($config, 'mobile.dialog.toggle', 'header-mobile:end');
        }
        Arr::del($config, 'mobile.toggle');
        if (Arr::get($config, 'mobile.offcanvas.flip')) {
            Arr::set(
                $config,
                'mobile.dialog.offcanvas.flip',
                Arr::get($config, 'mobile.offcanvas.flip')
            );
        } else {
            Arr::set($config, 'mobile.dialog.offcanvas.flip', false);
        }
        Arr::del($config, 'mobile.offcanvas.flip');
        Arr::updateKeys($config, [
            // Mobile Header
            'mobile.logo_padding_remove' => 'mobile.header.logo_padding_remove',
            // Mobile Navbar
            'mobile.sticky' => 'mobile.navbar.sticky',
            // Mobile Dialog
            'mobile.animation' => function ($value) use (&$config) {
                $menu_center_vertical = Arr::get($config, 'mobile.menu_center_vertical');
                Arr::del($config, 'mobile.menu_center_vertical');
                switch ($value) {
                    case 'offcanvas':
                        return [
                            'mobile.dialog.layout' => $menu_center_vertical
                                ? 'offcanvas-center'
                                : 'offcanvas-top',
                        ];
                    case 'modal':
                        return [
                            'mobile.dialog.layout' => $menu_center_vertical
                                ? 'modal-center'
                                : 'modal-top',
                        ];
                    case 'dropdown':
                        return ['mobile.dialog.layout' => 'dropdown'];
                }
            },
            'mobile.toggle_text' => 'mobile.dialog.toggle_text',
            'mobile.close_button' => 'mobile.dialog.close',
            'mobile.menu_style' => 'mobile.dialog.menu_style',
            'mobile.menu_divider' => 'mobile.dialog.menu_divider',
            'mobile.menu_center' => 'mobile.dialog.menu_center',
            'mobile.offcanvas.mode' => 'mobile.dialog.offcanvas.mode',
            'mobile.dropdown' => 'mobile.dialog.dropdown',
            'social_links' => 'header.social_links',
        ]);

        // Mobile Search and Social settings
        foreach (['search', 'social'] as $key) {
            if (Arr::get($config, "header.{$key}")) {
                Arr::set($config, "mobile.header.{$key}", 'dialog-mobile:end');
            }
        }

        foreach (
            ['search_style', 'social_links', 'social_target', 'social_style', 'social_gap']
            as $key
        ) {
            if (Arr::has($config, "header.{$key}")) {
                Arr::set($config, "mobile.header.{$key}", Arr::get($config, "header.{$key}"));
            }
        }

        return $config;
    },
    '2.8.0-beta.0.3' => function ($config) {
        foreach (['bgx', 'bgy'] as $prop) {
            $key = "site.image_parallax_{$prop}";
            $start = implode(
                ',',
                array_map(function ($value) {
                    return trim($value);
                }, explode(',', Arr::get($config, "{$key}_start", '')))
            );
            $end = implode(
                ',',
                array_map(function ($value) {
                    return trim($value);
                }, explode(',', Arr::get($config, "{$key}_end", '')))
            );
            if ($start !== '' || $end !== '') {
                Arr::set(
                    $config,
                    $key,
                    implode(',', [$start !== '' ? $start : 0, $end !== '' ? $end : 0])
                );
            }
            Arr::del($config, "{$key}_start");
            Arr::del($config, "{$key}_end");
        }

        return $config;
    },
    '2.8.0-beta.0.1' => function ($config) {
        // Stacked Center Split
        if (Arr::get($config, 'header.layout') == 'stacked-center-split') {
            Arr::set($config, 'header.layout', 'stacked-center-split-a');
        }
        // Stacked Left
        if (Arr::get($config, 'header.layout') == 'stacked-left-b') {
            Arr::set($config, 'header.push_index', 1);
        }
        if (in_array(Arr::get($config, 'header.layout'), ['stacked-left-a', 'stacked-left-b'])) {
            Arr::set($config, 'header.layout', 'stacked-left');
        }
        // Dialog Layout
        if (
            in_array(Arr::get($config, 'header.layout'), [
                'offcanvas-top-b',
                'offcanvas-center-b',
                'modal-top-b',
                'modal-center-b',
            ])
        ) {
            Arr::set($config, 'dialog.push_index', 1);
        }
        if (preg_match('/offcanvas-top/', Arr::get($config, 'header.layout'))) {
            Arr::set($config, 'dialog.layout', 'offcanvas-top');
        }
        if (preg_match('/offcanvas-center/', Arr::get($config, 'header.layout'))) {
            Arr::set($config, 'dialog.layout', 'offcanvas-center');
        }
        if (preg_match('/modal-top/', Arr::get($config, 'header.layout'))) {
            Arr::set($config, 'dialog.layout', 'modal-top');
        }
        if (preg_match('/modal-center/', Arr::get($config, 'header.layout'))) {
            Arr::set($config, 'dialog.layout', 'modal-center');
        }
        if (preg_match('/(offcanvas|modal)/', Arr::get($config, 'header.layout'))) {
            if (Arr::get($config, 'header.logo_center')) {
                Arr::set($config, 'header.layout', 'horizontal-center-logo');
                Arr::del($config, 'header.logo_center');
            } else {
                Arr::set($config, 'header.layout', 'horizontal-left');
            }
        }
        // Dialog Options
        Arr::updateKeys($config, [
            'navbar.toggle_text' => 'dialog.toggle_text',
            'navbar.toggle_menu_style' => 'dialog.menu_style',
            'navbar.toggle_menu_divider' => 'dialog.menu_divider',
            'navbar.toggle_menu_center' => 'dialog.menu_center',
            'navbar.offcanvas.mode' => 'dialog.offcanvas.mode',
            'navbar.offcanvas.overlay' => 'dialog.offcanvas.overlay',
        ]);
        // Navbar
        if (Arr::get($config, 'navbar.dropbar')) {
            Arr::set($config, 'navbar.dropbar', true);
        }
        // Search
        if (Arr::get($config, 'header.search')) {
            Arr::set($config, 'header.search', Arr::get($config, 'header.search') . ':end');
        }
        // Social
        if (Arr::get($config, 'header.social') == 'toolbar-left') {
            Arr::set($config, 'header.social', 'toolbar-right:start');
        } elseif (Arr::get($config, 'header.social')) {
            Arr::set($config, 'header.social', Arr::get($config, 'header.social') . ':end');
        }
        // Menu Items
        if (Arr::has($config, 'menu.items')) {
            $items = Arr::get($config, 'menu.items', []);
            foreach ($items as &$item) {
                if (Arr::get($item, 'justify')) {
                    Arr::set($item, 'dropdown.justify', 'navbar');
                    Arr::del($item, 'justify');
                }
                if (Arr::get($item, 'columns')) {
                    Arr::set($item, 'dropdown.columns', Arr::get($item, 'columns'));
                    Arr::del($item, 'columns');
                }
            }
            Arr::set($config, 'menu.items', $items);
        }

        return $config;
    },

    '2.7.15.1' => function ($config) {
        // Less
        if (Arr::get($config, 'less.@navbar-mode-border-vertical') === 'true') {
            Arr::set($config, 'less.@navbar-mode-border-vertical', 'partial');
        }

        return $config;
    },

    '2.5.0-beta.1.2' => function ($config) {
        if (Arr::has($config, 'menu.items')) {
            $items = Arr::get($config, 'menu.items', []);
            foreach ($items as &$item) {
                Arr::updateKeys($item, [
                    'icon' => 'image',
                    'icon-only' => 'image-only',
                ]);
            }
            Arr::set($config, 'menu.items', $items);
        }

        return $config;
    },

    '2.4.14' => function ($config) {
        // Less
        if (Arr::get($config, 'less.@navbar-mode') === 'border') {
            Arr::set($config, 'less.@navbar-mode', 'border-always');
        }

        if (Arr::get($config, 'less.@navbar-nav-item-line-slide-mode') === 'false') {
            Arr::set($config, 'less.@navbar-nav-item-line-slide-mode', 'left');
        }

        return $config;
    },

    '2.1.0-beta.0.1' => function ($config) {
        // Less
        Arr::updateKeys($config, [
            'less.@width-xxlarge-width' => 'less.@width-2xlarge-width',
            'less.@global-xxlarge-font-size' => 'less.@global-2xlarge-font-size',
        ]);

        return $config;
    },

    '2.0.11.1' => function ($config) {
        $style = Arr::get($config, 'style');

        $mapping = [
            'framerate:dark-blue' => 'framerate:black-blue',
            'framerate:dark-lightblue' => 'framerate:dark-blue',
            'joline:black-pink' => 'joline:dark-pink',
            'max:black-black' => 'max:dark-black',
        ];

        if (array_key_exists($style, $mapping)) {
            Arr::set($config, 'style', $mapping[$style]);
        }

        return $config;
    },

    '2.0.8.1' => function ($config) {
        $style = Arr::get($config, 'style');

        $mapping = [
            'copper-hill:white-turquoise' => 'copper-hill:light-turquoise',
            'florence:white-lilac' => 'florence:white-beige',
            'pinewood-lake:white-green' => 'pinewood-lake:light-green',
            'pinewood-lake:white-petrol' => 'pinewood-lake:light-petrol',
        ];

        if (array_key_exists($style, $mapping)) {
            Arr::set($config, 'style', $mapping[$style]);
        }

        return $config;
    },

    '2.0.0-beta.5.1' => function ($config) {
        foreach (['blog.width', 'post.width', 'header.width'] as $prop) {
            if (Arr::get($config, $prop) == '') {
                Arr::set($config, $prop, 'default');
            }

            if (Arr::get($config, $prop) == 'none') {
                Arr::set($config, $prop, '');
            }
        }

        [$style] = explode(':', Arr::get($config, 'style'));

        foreach (
            [
                'site.toolbar_width',
                'header.width',
                'top.width',
                'bottom.width',
                'blog.width',
                'post.width',
            ]
            as $prop
        ) {
            if (!in_array($style, ['jack-baker', 'morgan-consulting', 'vibe'])) {
                if (Arr::get($config, $prop) == 'large') {
                    Arr::set($config, $prop, 'xlarge');
                }
            }

            if (
                in_array($style, [
                    'craft',
                    'district',
                    'florence',
                    'makai',
                    'matthew-taylor',
                    'pinewood-lake',
                    'summit',
                    'tomsen-brody',
                    'trek',
                    'vision',
                    'yard',
                ])
            ) {
                if (Arr::get($config, $prop) == 'default') {
                    Arr::set($config, $prop, 'large');
                }
            }
        }

        // Less
        if (!in_array($style, ['jack-baker', 'morgan-consulting', 'vibe'])) {
            Arr::updateKeys($config, [
                'less.@container-large-max-width' => 'less.@container-xlarge-max-width',
            ]);
        }

        if (
            in_array($style, [
                'craft',
                'district',
                'florence',
                'makai',
                'matthew-taylor',
                'pinewood-lake',
                'summit',
                'tomsen-brody',
                'trek',
                'vision',
                'yard',
            ])
        ) {
            Arr::updateKeys($config, [
                'less.@container-max-width' => 'less.@container-large-max-width',
            ]);
        }

        return $config;
    },

    '1.22.0-beta.0.1' => function ($config) {
        // Rename Top and Bottom options
        foreach (['top', 'bottom'] as $position) {
            Arr::set(
                $config,
                "{$position}.column_gap",
                Arr::get($config, "{$position}.grid_gutter", '')
            );
            Arr::set(
                $config,
                "{$position}.row_gap",
                Arr::get($config, "{$position}.grid_gutter", '')
            );
            Arr::del($config, "{$position}.grid_gutter");

            Arr::set(
                $config,
                "{$position}.divider",
                Arr::get($config, "{$position}.grid_divider", '')
            );
            Arr::del($config, "{$position}.grid_divider");
        }

        // Rename Blog options
        if (Arr::get($config, 'blog.column_gutter')) {
            Arr::set($config, 'blog.grid_column_gap', 'large');
        }
        Arr::set($config, 'blog.grid_row_gap', 'large');
        Arr::del($config, 'blog.column_gutter');

        Arr::set($config, 'blog.grid_breakpoint', Arr::get($config, 'blog.column_breakpoint', 'm'));
        Arr::del($config, 'blog.column_breakpoint');

        // Rename Sidebar options
        foreach (['width', 'breakpoint', 'first', 'gutter', 'divider'] as $prop) {
            Arr::updateKeys($config, ["sidebar.{$prop}" => "main_sidebar.{$prop}"]);
        }

        return $config;
    },

    '1.20.4.1' => function ($config) {
        Arr::updateKeys($config, [
            // Less
            'less.@theme-toolbar-padding-vertical' => function ($value) {
                return [
                    'less.@theme-toolbar-padding-top' => $value,
                    'less.@theme-toolbar-padding-bottom' => $value,
                ];
            },
            // Header settings
            'site.toolbar_fullwidth' => function ($value) {
                if ($value) {
                    return ['site.toolbar_width' => 'expand'];
                }
            },
        ]);

        return $config;
    },

    '1.20.0-beta.7' => function ($config) {
        // Remove empty menu items
        if (Arr::has($config, 'menu.items')) {
            Arr::set(
                $config,
                'menu.items',
                array_filter((array) Arr::get($config, 'menu.items', []))
            );
        }

        return $config;
    },

    '1.20.0-beta.6' => function ($config) {
        Arr::updateKeys($config, [
            // Header settings
            'header.fullwidth' => function ($value) {
                if ($value) {
                    return ['header.width' => 'expand'];
                }
            },
        ]);

        if (Arr::get($config, 'header.layout') == 'toggle-offcanvas') {
            Arr::set($config, 'header.layout', 'offcanvas-top-a');
        }

        if (Arr::get($config, 'header.layout') == 'toggle-modal') {
            Arr::set($config, 'header.layout', 'modal-center-a');
            Arr::set($config, 'navbar.toggle_menu_style', 'primary');
            Arr::set($config, 'navbar.toggle_menu_center', true);
        }

        if (
            Arr::get($config, 'mobile.animation') == 'modal' &&
            !Arr::has($config, 'mobile.menu_center')
        ) {
            Arr::set($config, 'mobile.menu_style', 'primary');
            Arr::set($config, 'mobile.menu_center', true);
            Arr::set($config, 'mobile.menu_center_vertical', true);
        }

        if (
            Arr::get($config, 'site.boxed.padding') &&
            (!Arr::has($config, 'site.boxed.margin_top') ||
                !Arr::has($config, 'site.boxed.margin_bottom'))
        ) {
            Arr::set($config, 'site.boxed.margin_top', true);
            Arr::set($config, 'site.boxed.margin_bottom', true);
        }

        if (!Arr::has($config, 'cookie.mode') && Arr::get($config, 'cookie.active')) {
            Arr::set($config, 'cookie.mode', 'notification');
        }
        if (!Arr::has($config, 'cookie.button_consent_style')) {
            Arr::set(
                $config,
                'cookie.button_consent_style',
                Arr::get($config, 'cookie.button_style')
            );
        }

        foreach (['top', 'bottom'] as $position) {
            if (Arr::get($config, "{$position}.vertical_align") === true) {
                Arr::set($config, "{$position}.vertical_align", 'middle');
            }

            if (Arr::get($config, "{$position}.style") === 'video') {
                Arr::set($config, "{$position}.style", 'default');
            }

            if (Arr::get($config, "{$position}.width") == '1') {
                Arr::set($config, "{$position}.width", 'default');
            }

            if (Arr::get($config, "{$position}.width") == '2') {
                Arr::set($config, "{$position}.width", 'small');
            }

            if (Arr::get($config, "{$position}.width") == '3') {
                Arr::set($config, "{$position}.width", 'expand');
            }
        }

        foreach (Arr::get($config, 'less', []) as $key => $value) {
            if (
                in_array($key, [
                    '@heading-primary-line-height',
                    '@heading-hero-line-height-m',
                    '@heading-hero-line-height',
                ])
            ) {
                Arr::del($config, "less.{$key}");
            } elseif (Str::contains($key, ['heading-primary-', 'heading-hero-'])) {
                Arr::set(
                    $config,
                    'less.' .
                        strtr($key, [
                            'heading-primary-line-height-l' => 'heading-medium-line-height',
                            'heading-primary-' => 'heading-medium-',
                            'heading-hero-line-height-l' => 'heading-xlarge-line-height',
                            'heading-hero-' => 'heading-xlarge-',
                        ]),
                    $value
                );
                Arr::del($config, "less.{$key}");
            }
        }

        [$style] = explode(':', Arr::get($config, 'style'));

        $less = Arr::get($config, 'less', []);

        foreach (
            [
                [
                    ['fuse', 'horizon', 'joline', 'juno', 'lilian', 'vibe', 'yard'],
                    ['medium', 'small'],
                ],
                [['trek', 'fjord'], ['medium', 'large']],
                [['juno', 'vibe', 'yard'], ['xlarge', 'medium']],
                [
                    ['district', 'florence', 'flow', 'nioh-studio', 'summit', 'vision'],
                    ['xlarge', 'large'],
                ],
                [['lilian'], ['xlarge', '2xlarge']],
            ]
            as $change
        ) {
            [$styles, $transform] = $change;

            if (in_array($style, $styles)) {
                foreach ($less as $key => $value) {
                    if (str_contains($key, "heading-{$transform[0]}")) {
                        Arr::set(
                            $config,
                            'less.' .
                                str_replace(
                                    "heading-{$transform[0]}",
                                    "heading-{$transform[1]}",
                                    $key
                                ),
                            $value
                        );
                    }
                }
            }
        }

        return $config;
    },
];
