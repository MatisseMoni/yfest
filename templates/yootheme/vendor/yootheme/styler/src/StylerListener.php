<?php

namespace YOOtheme\Theme;

use YOOtheme\Config;
use YOOtheme\Event;
use YOOtheme\File;
use YOOtheme\Metadata;
use YOOtheme\Path;
use YOOtheme\Url;

class StylerListener
{
    public static function initCustomizer(Config $config, Metadata $metadata, Styler $styler)
    {
        // check version in css file, if it needs to be updated
        $style = File::get("~theme/css/theme.{$config('theme.id')}.css");
        $header = $style ? file_get_contents($style, false, null, 0, 34) : '';
        $version = preg_match('/\sv([\w\d\.\-]+)\s/', $header, $match) ? $match[1] : '1.0.0';

        $styles = array_map(function ($theme) {
            unset($theme['file']);
            return $theme;
        }, $styler->getThemes());

        $config->add('customizer.sections.styler', [
            'route' => 'theme/style',
            'worker' => Url::to(Path::get('../app/worker.min.js'), [
                'ver' => $config('theme.version'),
            ]),
            'update' => $version !== $config('theme.version'),
            'styles' => $styles,
        ]);

        $data = json_encode(Event::emit('styler.data|filter', []));

        $metadata->set('script:styler-data', "var \$styler = {$data};");
    }

    public static function stylerImports(Config $config, Styler $styler, $imports, $themeId)
    {
        // add general imports
        foreach ($config('theme.styles.imports', []) as $path) {
            foreach (glob($path) ?: [] as $file) {
                $imports += $styler->resolveImports($file);
            }
        }

        // add theme imports
        if (!($theme = $styler->getTheme($themeId))) {
            return $imports;
        }

        // add svg images
        foreach (
            File::glob("~theme/vendor/assets/uikit-themes/master-{$themeId}/images/*.svg") ?: []
            as $file
        ) {
            $imports += $styler->resolveImports($file);
        }

        $file = $theme['file'];

        $imports += $styler->resolveImports($file);

        // add theme style imports
        if (isset($theme['styles'])) {
            foreach (array_keys($theme['styles']) as $style) {
                $imports += $styler->resolveImports(
                    $file,
                    [
                        '@internal-style' => $style,
                    ] + $config('theme.styles.vars', [])
                );
            }
        }

        // add custom components
        foreach ($config('theme.styles.components', []) as $path) {
            foreach (glob($path) ?: [] as $component) {
                $imports += $styler->resolveImports($component);
                $imports[Url::to($file)] .= sprintf(
                    "\n@import \"%s\";",
                    Path::relative(dirname($file), $component)
                );
            }
        }

        return $imports;
    }
}
