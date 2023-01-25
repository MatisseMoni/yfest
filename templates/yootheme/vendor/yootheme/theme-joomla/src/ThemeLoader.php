<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Factory;
use Joomla\Registry\Registry;
use YOOtheme\Application;
use YOOtheme\Arr;
use YOOtheme\Config;
use YOOtheme\Container;
use YOOtheme\Database;
use YOOtheme\Event;
use YOOtheme\Path;
use YOOtheme\Theme\Updater;
use function YOOtheme\app;

class ThemeLoader
{
    protected static $configs = [];

    /**
     * Load theme configurations.
     *
     * @param Container $container
     * @param array     $configs
     */
    public static function load(Container $container, array $configs)
    {
        static::$configs = array_merge(static::$configs, $configs);
    }

    /**
     * Initialize current theme.
     *
     * @param Application $app
     * @param Config      $config
     */
    public static function initTheme(Application $app, Config $config)
    {
        $template = static::getTemplate();

        // is template active?
        if (!empty($template->params['yootheme'])) {
            static::loadConfiguration($template, $app, $config);
            Event::emit('theme.init');
        }
    }

    public static function loadConfiguration($template, $app, $config)
    {
        // get theme config
        $themeConfig = $template->params->get('config', '');
        $themeConfig = json_decode($themeConfig, true) ?: [];

        // load child theme config
        if (!empty($themeConfig['child_theme'])) {
            $app->load(
                Path::get(
                    "~/templates/{$template->template}_{$themeConfig['child_theme']}/config.php"
                )
            );
        }

        // add configurations
        $config->add('theme', [
            'id' => $template->id,
            'active' => true,
            'default' => !empty($template->home),
            'template' => $template->template,
        ]);

        foreach (static::$configs as $conf) {
            if ($conf instanceof \Closure) {
                $conf = $conf($config, $app);
            }

            $config->add('theme', (array) $conf);
        }

        // handle empty config
        if (empty($themeConfig)) {
            $themeConfig['version'] = $config('theme.version');
        }

        // merge defaults with configuration
        $config->set(
            '~theme',
            Arr::merge($config('theme.defaults', []), static::updateConfig($themeConfig, $template))
        );
    }

    /**
     * Gets the current template.
     *
     * @return object|null
     */
    public static function getTemplate()
    {
        $app = Factory::getApplication();
        $template = $app->getTemplate(true);

        // get site template
        if ($app->isClient('administrator')) {
            $view = $app->input->getCmd('view') === 'style';
            $option = $app->input->getCmd('option') === 'com_templates';
            $style = $app->input->getInt($view && $option ? 'id' : 'templateStyle');
            $query =
                'SELECT * FROM #__template_styles WHERE ' .
                ($style ? "id = {$style}" : "client_id = 0 AND home = '1'");

            if (
                $template = Factory::getDbo()
                    ->setQuery($query)
                    ->loadObject()
            ) {
                $template->params = new Registry($template->params);
            }
        }

        return $template;
    }

    protected static function updateConfig($themeConfig, $template)
    {
        /** @var Updater $updater */
        $updater = app(Updater::class);
        $version = $themeConfig['version'] ?? null;
        $themeConfig = $updater->update($themeConfig, [
            'app' => app(),
            'config' => $themeConfig,
        ]);

        if (empty($version) || $version !== $themeConfig['version']) {
            /** @var Database $db */
            $db = app(Database::class);
            $db->update(
                '@template_styles',
                [
                    'params' => json_encode(
                        [
                            'config' => json_encode(
                                $themeConfig,
                                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                            ),
                        ] + $template->params->toArray()
                    ),
                ],
                ['id' => $template->id]
            );
        }

        return $themeConfig;
    }
}
