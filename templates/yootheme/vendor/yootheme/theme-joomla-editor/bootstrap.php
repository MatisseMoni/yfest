<?php

namespace YOOtheme\Theme\Joomla;

return [
    'routes' => [['get', '/theme/editor', [EditorListener::class, 'renderEditor']]],

    'events' => [
        'customizer.init' => [
            EditorListener::class => 'initCustomizer',
        ],
    ],
];
