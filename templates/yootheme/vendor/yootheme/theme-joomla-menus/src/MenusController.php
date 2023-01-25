<?php

namespace YOOtheme\Theme\Joomla;

use YOOtheme\Http\Request;
use YOOtheme\Http\Response;

class MenusController
{
    public static function getItems(Request $request, Response $response, MenusHelper $helper)
    {
        return $response->withJson($helper->getItems());
    }
}
