<?php

namespace YOOtheme;

class UrlResolver
{
    public static function resolve(Config $config, $path, $parameters, $secure, callable $next)
    {
        $root = $config('app.rootDir');
        $path = Path::resolveAlias($path);

        if (Path::isBasePath($root, $path)) {
            $path = Path::relative($root, $path);
        }

        return $next($path, $parameters, $secure);
    }
}
