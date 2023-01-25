<?php

namespace YOOtheme\Builder\Joomla\Fields;

return [
    'events' => [
        'source.init' => [
            SourceListener::class => ['initSource', -10],
        ],

        'customizer.init' => [
            SourceListener::class => 'initCustomizer',
        ],
    ],
];
