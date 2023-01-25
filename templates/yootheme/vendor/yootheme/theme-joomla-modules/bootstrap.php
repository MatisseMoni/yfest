<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Document\Document;
use YOOtheme\View;

return [
    'routes' => [
        ['get', '/module', [ModulesController::class, 'getModule']],
        ['post', '/module', [ModulesController::class, 'saveModule']],
        ['get', '/modules', [ModulesController::class, 'getModules']],
        ['get', '/positions', [ModulesController::class, 'getPositions']],
    ],

    'events' => [
        'theme.init' => [
            ModulesListener::class => 'initTheme',
        ],

        'customizer.init' => [
            ModulesListener::class => 'initCustomizer',
        ],
    ],

    'actions' => [
        'onAfterCleanModuleList' => [
            ModulesListener::class => ['loadModules', -10],
        ],

        'onContentPrepareForm' => [
            ModulesListener::class => 'editModule',
        ],
    ],

    'extend' => [
        View::class => function (View $view, $app) {
            $view->addFunction('countModules', $app->wrap(Document::class . '@countModules'));
        },
    ],
];
