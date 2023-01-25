<?php

namespace YOOtheme\Builder\Joomla\Source;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use YOOtheme\Builder;
use YOOtheme\Builder\Source\SourceTransform;
use YOOtheme\Builder\UpdateTransform;
use YOOtheme\Path;

return [
    'config' => [
        'source' => [
            'id' => 1,
        ],
    ],

    'routes' => [
        ['get', '/joomla/articles', [SourceController::class, 'articles']],
        ['get', '/joomla/users', [SourceController::class, 'users']],
    ],

    'events' => [
        'source.init' => [
            SourceListener::class => 'initSource',
        ],

        'customizer.init' => [
            SourceListener::class => ['initCustomizer', 10],
        ],

        'builder.template' => [
            TemplateListener::class => 'matchTemplate',
        ],
    ],

    'actions' => [
        'onLoadTemplate' => [
            TemplateListener::class => 'loadTemplate',
        ],

        'onLoad404' => [
            TemplateListener::class => 'load404',
        ],

        'onContentPrepare' => [
            ContentListener::class => 'prepareContent',
        ],
    ],

    'extend' => [
        Builder::class => function (Builder $builder) {
            $builder->addTypePath(Path::get('./elements/*/element.json'));
        },

        UpdateTransform::class => function (UpdateTransform $update) {
            $update->addGlobals(require __DIR__ . '/updates.php');
        },

        SourceTransform::class => function (SourceTransform $transform) {
            $transform->addFilter('date', function ($value, $format) {
                if (!$value) {
                    return $value;
                }

                if ($value === Factory::getDbo()->getNullDate()) {
                    return;
                }

                return HTMLHelper::_('date', $value, $format ?: Text::_('DATE_FORMAT_LC3'));
            });
        },
    ],
];
