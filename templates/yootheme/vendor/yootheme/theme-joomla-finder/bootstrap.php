<?php

namespace YOOtheme\Theme\Joomla;

return [
    'routes' => [
        ['get', '/finder', [FinderController::class, 'index']],
        ['post', '/finder/rename', [FinderController::class, 'rename']],
    ],

    'actions' => [
        'onBeforeRespond' => [
            FinderListener::class => 'beforeRespond',
        ],
    ],

    'events' => [
        'customizer.init' => [
            FinderListener::class => 'initCustomizer',
        ],
    ],
];
