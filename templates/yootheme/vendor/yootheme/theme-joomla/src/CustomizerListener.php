<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Router\Router;
use Joomla\CMS\Session\Session;
use Joomla\CMS\User\User;
use Joomla\Component\Content\Site\Helper\RouteHelper;
use Joomla\Input\Input;
use Joomla\Registry\Registry;
use YOOtheme\Arr;
use YOOtheme\Config;
use YOOtheme\Database;
use YOOtheme\Metadata;
use YOOtheme\Path;
use YOOtheme\Url;

class CustomizerListener
{
    public static function initTheme(Config $config, Session $session, Input $input)
    {
        $cookie = hash_hmac('md5', $config('theme.template'), $config('app.secret'));

        // If not customizer route
        if ($input->get('p') !== 'customizer') {
            // Is frontend request and has customizer cookie
            if (!$config('app.isSite') || !$input->cookie->get($cookie)) {
                return;
            }

            // Get params from frontend session
            $params = $session->get($cookie) ?: [];

            // Get customizer config from request
            if ($custom = $input->getBase64('customizer')) {
                $params = array_replace($params, json_decode(base64_decode($custom), true));
                $session->set($cookie, Arr::pick($params, ['config', 'admin', 'user_id']));
            }

            // Override theme config
            if (isset($params['config'])) {
                $config->set('~theme', $params['config']);
            }

            // Pass through e.g. page, modules and template params
            $config->add('req.customizer', $params);
        }

        $config('joomla.config')->set('caching', 0);
        $config->set('app.isCustomizer', true);
        $config->set('theme.cookie', $cookie);
        $config->set('customizer.id', $config('theme.id'));
    }

    public static function initCustomizer(Config $config)
    {
        $config->set(
            'customizer.404',
            (string) Router::getInstance('site')->build(RouteHelper::getArticleRoute(-1, 0, '*'))
        );
        $config->addFile('customizer', Path::get('../config/customizer.json'));

        // Joomla 4 does not distribute com_search
        if (!ComponentHelper::isEnabled('com_search')) {
            $config->del('customizer.panels.advanced.fields.search_module');
        }
    }

    public static function prepareData(Config $config, $event)
    {
        [$context, $data] = $event->getArguments();

        if ($context !== 'com_templates.style') {
            return;
        }

        $config->add('customizer', [
            'context' => $context,
            'apikey' => $config('app.apikey'),
            'url' => Url::route('customizer', [
                'templateStyle' => $data->id,
                'format' => 'html',
            ]),
        ]);
    }

    public static function compileHead(Config $config, Metadata $metadata)
    {
        if (
            $config('joomla.config.themeFile') !== 'offline.php' &&
            ($data = $config('customizer'))
        ) {
            $metadata->set(
                'script:customizer-data',
                sprintf(
                    'var $customizer = JSON.parse(atob("%s"));',
                    base64_encode(json_encode($data))
                )
            );
        }
    }

    public static function saveConfig(Database $db, User $user, $values)
    {
        if (!isset($values['yootheme_apikey'])) {
            return $values;
        }

        // update apikey in plugin
        $plugin = PluginHelper::getPlugin('installer', 'yootheme');

        if ($plugin && $user->authorise('core.admin', 'com_plugins')) {
            $reg = new Registry($plugin->params);
            $reg->set('apikey', $values['yootheme_apikey']);

            $db->executeQuery(
                "UPDATE @extensions SET params = :params WHERE element = 'yootheme' AND folder = 'installer'",
                ['params' => $reg->toString()]
            );
        }

        unset($values['yootheme_apikey']);

        return $values;
    }
}
