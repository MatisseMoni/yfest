<?php

namespace YOOtheme\Theme\Joomla;

use YOOtheme\Path;
use YOOtheme\Theme\Styler;

class StylerListener
{
    public static function stylerImports(Styler $styler, $imports)
    {
        if (version_compare(JVERSION, '4.0', '<')) {
            $bootstrap = Path::get(
                '~theme/vendor/yootheme/theme-joomla/assets/less/bootstrap-joomla3/bootstrap.less'
            );

            foreach ($styler->resolveImports($bootstrap) as $file => $content) {
                $imports[str_replace('/bootstrap-joomla3/', '/bootstrap/', $file)] = $content;
            }
        }

        return $imports;
    }
}
