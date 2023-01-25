<?php

namespace YOOtheme;

return [
    'transforms' => [
        'render' => function ($node) {
            /**
             * @var Config        $config
             * @var ImageProvider $image
             */
            [$config, $image] = app(Config::class, ImageProvider::class, Metadata::class);

            $getIconOptions = function ($node) use ($image) {
                if (empty($node->props['marker_icon'])) {
                    return [];
                }

                $icon = $node->props['marker_icon'];
                $width = (int) $node->props['marker_icon_width'];
                $height = (int) $node->props['marker_icon_height'];

                if ($imageObj = $image->create($icon, false)) {
                    if ($width || $height) {
                        $imageObj = $imageObj->thumbnail($width, $height);
                    }

                    $width = $imageObj->getWidth();
                    $height = $imageObj->getHeight();
                    $icon = $image->getUrl("{$icon}#thumbnail={$width},{$height}");
                }

                $hasWidthAndHeight = $width && $height;

                return [
                    'icon' => $icon ? Url::to($icon) : false,
                    'iconSize' => $hasWidthAndHeight ? [$width, $height] : null,
                    'iconAnchor' => $hasWidthAndHeight ? [$width / 2, $height] : null,
                ];
            };

            $center = [];
            $node->options = [];
            foreach ($node->children as $child) {
                if (empty($child->props['location'])) {
                    continue;
                }

                @list($lat, $lng) = explode(',', $child->props['location']);

                if (!is_numeric($lat) || !is_numeric($lng)) {
                    continue;
                }

                if (empty($center)) {
                    $center = ['lat' => (float) $lat, 'lng' => (float) $lng];
                }

                if (!empty($child->props['hide'])) {
                    continue;
                }

                $options =
                    [
                        'lat' => (float) $lat,
                        'lng' => (float) $lng,
                        'title' => $child->props['title'],
                    ] + $getIconOptions($child);

                if (!empty($child->props['show_popup'])) {
                    $options['show_popup'] = true;
                }

                $child->props['show'] = true;
                $node->options['markers'][] = $options;
            }

            // map options
            $node->options += Arr::pick($node->props, [
                'type',
                'zoom',
                'min_zoom',
                'max_zoom',
                'zooming',
                'dragging',
                'clustering',
                'controls',
                'poi',
                'styler_invert_lightness',
                'styler_hue',
                'styler_saturation',
                'styler_lightness',
                'styler_gamma',
                'popup_max_width',
            ]);
            $node->options['center'] = $center ?: ['lat' => 53.5503, 'lng' => 10.0006];
            $node->options['lazyload'] = true;
            $node->options += $getIconOptions($node);

            if ($node->props['clustering']) {
                for ($i = 1; $i < 4; $i++) {
                    $icon = $node->props["cluster_icon_{$i}"];
                    $width = $node->props["cluster_icon_{$i}_width"];
                    $height = $node->props["cluster_icon_{$i}_height"];
                    $textColor = $node->props["cluster_icon_{$i}_text_color"];

                    if ($icon) {
                        if ($imageObj = $image->create($icon, false)) {
                            if ($width || $height) {
                                $imageObj = $imageObj->thumbnail($width, $height);
                            }

                            $width = $imageObj->getWidth();
                            $height = $imageObj->getHeight();
                            $icon = $image->getUrl("{$icon}#thumbnail={$width},{$height}");
                        }

                        $node->options['cluster_icons'][] = [
                            'url' => Url::to($icon),
                            'size' => $width && $height ? [$width, $height] : null,
                            'textColor' => $textColor,
                        ];
                    }
                }
            }

            $node->options = array_filter($node->options, function ($value) {
                return isset($value);
            });

            $node->props['metadata'] = [];

            // add scripts, styles
            $cdnBase = 'https://cdn.jsdelivr.net/npm';
            if ($key = $config('~theme.google_maps')) {
                $node->options['library'] = 'google';

                $node->props['metadata']['script:google-maps'] = [
                    'src' => "https://maps.googleapis.com/maps/api/js?key={$key}",
                    'defer' => true,
                ];

                if ($node->props['clustering']) {
                    $baseUrl = "{$cdnBase}/@googlemaps/markerclusterer@2.0.7";
                    $node->props['metadata']['script:google-maps-clusterer'] = [
                        'src' => "{$baseUrl}/dist/index.umd.min.js",
                        'defer' => true,
                    ];
                }
            } else {
                $node->options['library'] = 'leaflet';

                $baseUrl = "{$cdnBase}/leaflet@1.9.2/dist";
                $node->options['baseUrl'] = $baseUrl;
                $node->props['metadata']['script:leaflet'] = [
                    'src' => "{$baseUrl}/leaflet.js",
                    'defer' => true,
                ];
                $node->props['metadata']['style:leaflet'] = [
                    'href' => Path::get('./assets/leaflet.css'),
                    'defer' => true,
                ];

                if ($node->props['clustering']) {
                    $baseUrl = "{$cdnBase}/leaflet.markercluster@1.5.3/dist";
                    $node->options['clusterBaseUrl'] = $baseUrl;
                    $node->props['metadata'] += [
                        'script:leaflet-clusterer' => [
                            'src' => "{$baseUrl}/leaflet.markercluster.js",
                            'defer' => true,
                        ],
                        'style:leaflet-clusterer' => [
                            'href' => "{$baseUrl}/MarkerCluster.css",
                            'defer' => true,
                        ],
                        'style:leaflet-clusterer-default' => [
                            'href' => "{$baseUrl}/MarkerCluster.Default.css",
                            'defer' => true,
                        ],
                    ];
                }
            }

            $node->props['metadata']['script:builder-map'] = [
                'src' => Path::get('./app/map.min.js'),
                'defer' => true,
            ];
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

        '1.20.0-beta.1.1' => function ($node) {
            Arr::updateKeys($node->props, ['maxwidth_align' => 'block_align']);
        },

        '1.18.0' => function ($node) {
            if (
                !isset($node->props['width_breakpoint']) &&
                Arr::get($node->props, 'width_max') === false
            ) {
                $node->props['width_breakpoint'] = true;
            }
        },
    ],
];
