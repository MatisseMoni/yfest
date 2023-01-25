<?php

namespace YOOtheme;

use Joomla\CMS\HTML\Helpers\Content;
use YOOtheme\Builder\Joomla\BuilderController;
use YOOtheme\Builder\Joomla\ContentListener;

return [
    'routes' => [
        ['post', '/page', ContentListener::class . '@savePage'],
        ['post', '/builder/image', [BuilderController::class, 'loadImage']],
    ],

    'actions' => [
        'onAfterRoute' => [
            ContentListener::class => '@afterRoute',
        ],

        'onContentPrepare' => [
            ContentListener::class => '@prepareContent',
        ],

        'onLoadTemplate' => [
            ContentListener::class => ['@loadTemplate', 10],
        ],
    ],

    'extend' => [
        View::class => function (View $view) {
            $view->addLoader(function ($name, $parameters, callable $next) {
                $content = $next($name, $parameters);

                return empty($parameters['prefix']) || $parameters['prefix'] !== 'page'
                    ? Content::prepare($content)
                    : $content;
            }, '*/builder/elements/layout/templates/template.php');
        },

        Builder::class => function (Builder $builder, $app) {
            $builder->addTypePath(Path::get('./elements/*/element.json'));

            if ($childDir = $app->config->get('theme.childDir')) {
                $builder->addTypePath("{$childDir}/builder/*/element.json");
            }
        },
    ],

    'services' => [
        ContentListener::class => '',
    ],
];
