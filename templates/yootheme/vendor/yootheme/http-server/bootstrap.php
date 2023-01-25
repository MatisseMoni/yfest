<?php

use YOOtheme\BodyMiddleware;
use YOOtheme\CsrfMiddleware;
use YOOtheme\Router;
use YOOtheme\RouterMiddleware;
use YOOtheme\Routes;
use YOOtheme\UrlResolver;

return [
    'events' => [
        'app.request' => [
            BodyMiddleware::class => ['parseJson', 10],
            CsrfMiddleware::class => ['@handle', 10],
            RouterMiddleware::class => [['@handleRoute', 30], ['@handleStatus', 20]],
        ],

        'app.error' => [
            RouterMiddleware::class => ['@handleError', 10],
        ],

        'url.resolve' => [
            UrlResolver::class => 'resolve',
        ],
    ],

    'aliases' => [
        Routes::class => 'routes',
    ],

    'services' => [
        Routes::class => '',
        Router::class => '',
        RouterMiddleware::class => '',
    ],
];
