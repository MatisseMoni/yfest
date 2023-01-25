<?php

namespace YOOtheme\Joomla;

use Joomla\CMS\Router\Route;
use YOOtheme\Url;

class Router
{
    public static function generate($pattern = '', array $parameters = [], $secure = null)
    {
        if ($pattern) {
            $parameters = ['p' => $pattern] + $parameters;
        }

        return Url::to(
            Route::_('index.php?' . http_build_query(['option' => 'com_ajax']), false),
            $parameters,
            $secure
        );
    }
}
