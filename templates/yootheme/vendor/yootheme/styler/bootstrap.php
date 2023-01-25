<?php

namespace YOOtheme;

use YOOtheme\Theme\StyleFontLoader;
use YOOtheme\Theme\StylerController;
use YOOtheme\Theme\StylerListener;

return [
    'theme' => function (Config $config) {
        return $config->loadFile(Path::get('./config/theme.json'));
    },

    'routes' => [
        ['get', '/theme/style', [StylerController::class, 'loadStyle']],
        ['post', '/theme/style', [StylerController::class, 'saveStyle']],
        ['get', '/styler/library', [StylerController::class, 'index']],
        ['post', '/styler/library', [StylerController::class, 'addStyle']],
        ['delete', '/styler/library', [StylerController::class, 'removeStyle']],
    ],

    'events' => [
        'customizer.init' => [
            StylerListener::class => 'initCustomizer',
        ],

        'styler.imports' => [
            StylerListener::class => ['stylerImports', 10],
        ],
    ],

    'services' => [
        StyleFontLoader::class => [
            'arguments' => [
                '$cache' => function () {
                    return Path::get('~theme/fonts');
                },
            ],
        ],
    ],
];
