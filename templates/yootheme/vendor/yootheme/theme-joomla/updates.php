<?php

namespace YOOtheme;

use Joomla\CMS\Factory;

return [
    '3.0.0-beta.1.5' => function ($config) {
        $db = Factory::getDbo();
        $modules = $db->setQuery('SELECT id, params FROM `#__modules`')->loadObjectList();

        foreach ($modules as $module) {
            $params = json_decode($module->params);
            if (empty($params->yoo_config)) {
                continue;
            }

            $conf = json_decode($params->yoo_config, true);
            Arr::updateKeys($conf, ['menu_style' => 'menu_type']);
            $params->yoo_config = json_encode($conf);
            $module->params = json_encode($params);

            $db->updateObject('#__modules', $module, 'id');
        }

        return $config;
    },
    '2.8.0-beta.0.4' => function ($config) {
        Arr::updateKeys($config, ['menu.positions.mobile' => 'menu.positions.dialog-mobile']);

        $db = Factory::getDbo();
        $db->setQuery(
            "UPDATE `#__modules` SET position = {$db->quote(
                'dialog-mobile'
            )} WHERE client_id=0 AND position = {$db->quote('mobile')}"
        )->execute();

        return $config;
    },
    '2.8.0-beta.0.1' => function ($config, array $params) {
        if (preg_match('/(offcanvas|modal)/', Arr::get($params['config'], 'header.layout'))) {
            Arr::updateKeys($config, ['menu.positions.navbar' => 'menu.positions.dialog']);

            // Ensure empty instead of default value
            Arr::set($config, 'menu.positions.navbar', '');

            $db = Factory::getDbo();
            $db->setQuery(
                "UPDATE `#__modules` SET position = {$db->quote(
                    'dialog'
                )} WHERE client_id=0 AND position = {$db->quote('navbar')}"
            )->execute();
        }

        // Check child theme's "theme.js" for jQuery
        if (
            !empty($config['child_theme']) &&
            !isset($config['jquery']) &&
            ($contents = @file_get_contents(
                $params['app'](Config::class)->get('theme.rootDir') .
                    "_{$config['child_theme']}/js/theme.js"
            )) &&
            str_contains($contents, 'jQuery')
        ) {
            $config['jquery'] = true;
        }

        return $config;
    },
    '1.20.0-beta.6' => function ($config) {
        // Deprecated Blog settings
        if (!Arr::has($config, 'post.image_margin')) {
            Arr::set($config, 'post.title_margin', 'large');
            Arr::set($config, 'blog.title_margin', 'large');

            if (Arr::get($config, 'post.content_width') === true) {
                Arr::set($config, 'post.content_width', 'small');
            }

            if (Arr::get($config, 'post.content_width') === false) {
                Arr::set($config, 'post.content_width', '');
            }

            if (Arr::get($config, 'post.header_align') === true) {
                Arr::set($config, 'blog.header_align', 1);
            }
        }

        return $config;
    },
];
