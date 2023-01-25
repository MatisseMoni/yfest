<?php

namespace YOOtheme\Theme;

return [
    'routes' => [
        ['get', '/systemcheck', [SystemCheckController::class, 'index']],
        ['get', '/cache', [CacheController::class, 'index']],
        ['post', '/cache/clear', [CacheController::class, 'clear']],
        ['post', '/import', [SettingsController::class, 'import']],
    ],

    'events' => [
        'theme.head' => [
            SettingsListener::class => 'initHead',
        ],

        'customizer.init' => [
            SettingsListener::class => 'initCustomizer',
        ],
    ],
];
