<?php

namespace YOOtheme\Builder\Joomla\Source\Type;

use Joomla\CMS\Document\HtmlDocument;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;

class ArticleEventType extends EventType
{
    public static function resolve($article, $args, $context, $info)
    {
        $key = $info->fieldName;

        if (isset($article->event->$key)) {
            return $article->event->$key;
        }

        $marker = "<!-- article_{$article->id}_{$key} -->";

        Factory::getApplication()->registerEvent('onBeforeRender', function () use (
            $article,
            $key,
            $marker
        ) {
            if (!isset($article->event->$key)) {
                static::applyContentPlugins($article);
            }

            /** @var HtmlDocument $document */
            $document = Factory::getDocument();
            $document->setBuffer(
                str_replace($marker, $article->event->$key, $document->getBuffer('component')),
                [
                    'type' => 'component',
                    'name' => null,
                    'title' => null,
                ]
            );
        });

        return $marker;
    }

    protected static function applyContentPlugins($article)
    {
        $app = Factory::getApplication();

        // Process the content plugins.
        PluginHelper::importPlugin('content');

        $article->event = new \stdClass();

        // Joomla content plugins expect $article and $article->params to be passed as reference
        $results = $app->triggerEvent('onContentAfterTitle', [
            'com_content.article',
            &$article,
            &$article->params,
        ]);
        $article->event->afterDisplayTitle = trim(implode("\n", $results));

        $results = $app->triggerEvent('onContentBeforeDisplay', [
            'com_content.article',
            &$article,
            &$article->params,
        ]);
        $article->event->beforeDisplayContent = trim(implode("\n", $results));

        $results = $app->triggerEvent('onContentAfterDisplay', [
            'com_content.article',
            &$article,
            &$article->params,
        ]);
        $article->event->afterDisplayContent = trim(implode("\n", $results));
    }
}
