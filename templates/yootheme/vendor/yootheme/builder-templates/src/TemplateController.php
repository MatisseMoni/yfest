<?php

namespace YOOtheme\Builder\Templates;

use YOOtheme\Builder;
use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use YOOtheme\Storage;

class TemplateController
{
    public static function index(
        Request $request,
        Response $response,
        Storage $storage,
        Builder $builder
    ) {
        $library = array_filter(
            array_map(function ($template) use ($builder) {
                if (isset($template['layout'])) {
                    $template['layout'] = $builder->load(json_encode($template['layout']));
                }

                return $template;
            }, $storage('templates', []))
        );

        return $response->withJson($library);
    }
    public static function saveTemplate(Request $request, Response $response, Storage $storage)
    {
        // Can't name 'tpl' request param 'template' because of conflict when PECL extension "json_post" is enabled
        $id = $request->getParam('id');
        $tpl = $request->getParam('tpl');

        if ($id && $tpl) {
            $storage->set("templates.{$id}", $tpl);
        }

        return $response->withJson(['message' => 'success']);
    }

    public static function deleteTemplate(Request $request, Response $response, Storage $storage)
    {
        $id = $request->getQueryParam('id');

        if ($id) {
            $storage->del("templates.{$id}");
        }

        return $response->withJson(['message' => 'success']);
    }

    public static function reorderTemplates(Request $request, Response $response, Storage $storage)
    {
        $sorting = $request->getParam('templates');
        $templates = $storage->get('templates');

        $storage->set(
            'templates',
            array_merge(array_intersect_key(array_flip($sorting), $templates), $templates)
        );

        return $response->withJson(['message' => 'success']);
    }
}
