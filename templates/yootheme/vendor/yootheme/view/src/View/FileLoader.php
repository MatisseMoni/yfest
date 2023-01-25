<?php

namespace YOOtheme\View;

use YOOtheme\File;

class FileLoader
{
    public function __invoke($name, $parameters, $next)
    {
        if (!str_ends_with(strtolower($name), '.php')) {
            $name .= '.php';
        }

        return $next(File::find($name) ?: $name, $parameters);
    }
}
