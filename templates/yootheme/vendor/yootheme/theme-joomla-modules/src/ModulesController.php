<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\User\User;
use YOOtheme\Builder;
use YOOtheme\Database;
use YOOtheme\Http\Request;
use YOOtheme\Http\Response;

class ModulesController
{
    public static function getModule(
        Request $request,
        Response $response,
        Database $db,
        Builder $builder
    ) {
        $id = $request->getQueryParam('id');
        $query = 'SELECT id, content FROM @modules WHERE id = :id';

        $module = $db->fetchObject($query, ['id' => $id]);
        $module->content = $builder->load($module->content);

        return $response->withJson($module);
    }

    public static function saveModule(
        Request $request,
        Response $response,
        Database $db,
        User $user
    ) {
        $id = $request->getParam('id');
        $content = $request->getParam('content');

        $request->abortIf(
            !$user->authorise('core.edit', "com_modules.module.{$id}"),
            403,
            'Insufficient User Rights.'
        );

        $db->update('@modules', ['content' => json_encode($content)], ['id' => $id]);

        return $response->withJson(['message' => 'success']);
    }

    public static function getModules(Request $request, Response $response, ModulesHelper $helper)
    {
        return $response->withJson($helper->getModules());
    }

    public static function getPositions(Request $request, Response $response, ModulesHelper $helper)
    {
        return $response->withJson($helper->getPositions());
    }
}
