<?php

use Joomla\CMS\Document\HtmlDocument;
use Joomla\CMS\Factory;

return [
    'transforms' => [
        'render' => function ($node) {
            /** @var HtmlDocument $document */
            $document = Factory::getDocument();
            $renderer = $document->loadRenderer('modules');
            $position = $node->props['content'] ?? '';

            // render module position
            if ($position && $document->countModules($position)) {
                $node->content = $renderer->render($position, [
                    'name' => $position,
                    'style' => 'grid' . ($node->props['layout'] === 'stack' ? '-stack' : ''),
                    'position' => $node->props, // pass grid settings to templates/position.php
                ]);
            }

            // return false, if no module position content was found
            if (empty($node->content)) {
                return false;
            }
        },
    ],
];
