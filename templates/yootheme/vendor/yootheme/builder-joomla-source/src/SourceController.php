<?php

namespace YOOtheme\Builder\Joomla\Source;

use Joomla\CMS\Factory;
use Joomla\CMS\User\User;
use YOOtheme\Database;
use YOOtheme\Http\Request;
use YOOtheme\Http\Response;

class SourceController
{
    /**
     * @param Request  $request
     * @param Response $response
     * @param Database $db
     * @param User     $user
     *
     * @throws \Exception
     *
     * @return Response
     */
    public static function articles(Request $request, Response $response, Database $db, User $user)
    {
        $ids = implode(',', array_map('intval', (array) $request->getQueryParam('ids')));
        $groups = implode(',', $user->getAuthorisedViewLevels());
        $titles = [];

        if (!empty($ids)) {
            $query = "SELECT id, title
                FROM #__content
                WHERE id IN ({$ids})
                AND access IN ({$groups})";

            foreach ($db->fetchAll($query) as $article) {
                $titles[$article['id']] = $article['title'];
            }
        }

        return $response->withJson((object) $titles);
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param User     $user
     *
     * @throws \Exception
     *
     * @return Response
     */
    public static function users(Request $request, Response $response, User $user)
    {
        $titles = [];

        if ($user->authorise('core.manage', 'com_users')) {
            foreach ($request->getQueryParam('ids') as $id) {
                $titles[$id] = Factory::getUser($id)->name;
            }
        }

        return $response->withJson((object) $titles);
    }
}
