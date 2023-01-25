<?php

namespace YOOtheme\Theme;

use YOOtheme\Http\Request;
use YOOtheme\Http\Response;

class SettingsController
{
    public static function import(Request $request, Response $response, Updater $updater)
    {
        $config = $request->getParam('config');
        $config = $updater->update($config, []);

        return $response->withJson($config);
    }
}
