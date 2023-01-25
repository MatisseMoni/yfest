<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Factory;
use YOOtheme\Config;
use YOOtheme\Event;
use YOOtheme\File;

class ChildThemeListener
{
    public static function initTheme(Config $config)
    {
        if (empty(($child = $config('~theme.child_theme')))) {
            return;
        }

        if (!file_exists($childDir = "{$config('theme.rootDir')}_{$child}")) {
            return;
        }

        // add childDir to config
        $config->set('theme.childDir', $childDir);

        // add ~theme alias resolver
        Event::on('path ~theme', function ($path, $file) use ($childDir) {
            return $file && File::find($childDir . $file) ? $childDir . $file : $path;
        });
    }

    public static function initCustomizer(Config $config)
    {
        $config->set(
            'theme.child_themes',
            array_merge(['None' => ''], static::getChildThemes($config('theme.rootDir')))
        );
    }

    public static function loadModules(Config $config, $event)
    {
        if ($config('app.isAdmin') || empty($config('theme.childDir'))) {
            return;
        }

        [$modules] = $event->getArguments();

        foreach ($modules as $module) {
            $params = !empty($module->params) ? json_decode($module->params) : new \stdClass();
            $layout =
                isset($params->layout) && is_string($params->layout)
                    ? str_replace('_:', '', $params->layout)
                    : 'default';

            if (file_exists("{$config('theme.childDir')}/html/{$module->module}/{$layout}.php")) {
                $params->layout = basename($config('theme.childDir')) . ":{$layout}";
                $module->params = json_encode($params);
            }
        }
    }

    public static function beforeDisplay(Config $config, $event)
    {
        if ($config('app.isAdmin') || empty(($childDir = $config('theme.childDir')))) {
            return;
        }

        $view = $event->getArgument('subject');
        $paths = $view->get('_path');

        if ($path = $paths['template'][0] ?? false) {
            $theme = $config('theme.template');

            if (str_contains($path, DIRECTORY_SEPARATOR . $theme . DIRECTORY_SEPARATOR)) {
                array_unshift(
                    $paths['template'],
                    preg_replace("/({$theme}(?!.*{$theme}.*))/", basename($childDir), $path)
                );
            }
        }

        $view->set('_path', $paths);
    }

    public static function afterDispatch(Config $config)
    {
        if (
            !$config('app.isAdmin') &&
            $config('theme.childDir') &&
            ($themeFile = static::getThemeFile()) &&
            file_exists($file = "{$config('theme.childDir')}/{$themeFile}")
        ) {
            $config('joomla.config')->set('theme', basename(dirname($file)));
        }
    }

    public static function getChildThemes($root)
    {
        $dir = dirname($root);
        $name = basename($root);
        $themes = [];

        foreach (glob("{$dir}/{$name}_*") as $child) {
            $child = str_replace("{$name}_", '', basename($child));
            $themes[ucfirst($child)] = $child;
        }

        return $themes;
    }

    /**
     * @see SiteApplication::render
     */
    protected static function getThemeFile()
    {
        $app = Factory::getApplication();
        $document = Factory::getDocument();

        if ($document->getType() === 'feed') {
            return;
        }

        $file = $app->input->get('tmpl', 'index');

        if ($file === 'offline' && !$app->get('offline')) {
            return 'index.php';
        }

        if ($app->get('offline') && !Factory::getUser()->authorise('core.login.offline')) {
            return 'offline.php';
        }

        return "{$file}.php";
    }
}
