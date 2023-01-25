<?php

namespace YOOtheme\Theme\Joomla;

return [
    'routes' => [['get', '/items', [MenusController::class, 'getItems']]],

    'events' => [
        'customizer.init' => [
            MenusListener::class => 'initCustomizer',
        ],
    ],

    'actions' => [
        'onAfterCleanModuleList' => [
            MenusListener::class => [['loadModules', 0], ['lateLoadModules', -20]],
        ],
    ],
];
