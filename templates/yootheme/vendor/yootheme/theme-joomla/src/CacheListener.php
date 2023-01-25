<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Document\Document;
use Joomla\CMS\Document\HtmlDocument;
use function YOOtheme\app;
use YOOtheme\Application;
use YOOtheme\Config;
use YOOtheme\Joomla\Platform;

class CacheListener
{
    public static $keys = ['app.isBuilder', '~theme.page_layout'];

    /**
     * Add to Joomla caching.
     *
     * @param Application $app
     * @param Config      $config
     * @param Document    $document
     */
    public static function loadTemplate(Application $app, Config $config, Document $document)
    {
        if (!$config('joomla.config')->get('caching', 0) || !$document instanceof HtmlDocument) {
            return;
        }

        foreach (static::$keys as $key) {
            $value = $config($key);

            if (isset($value)) {
                $document->_custom[$key] = $value;
            }
        }

        // Make assets cacheable (e.g. maps.min.js)
        $app->call([Platform::class, 'registerAssets']);
    }

    /**
     * Add assets for Joomla progressive caching.
     *
     * @param mixed    $name
     * @param mixed    $parameters
     * @param callable $next
     *
     * @return mixed
     */
    public static function loadPosition($name, $parameters, callable $next)
    {
        $result = $next($name, $parameters);

        $config = app(Config::class);

        if ($config('joomla.config')->get('caching', 0) == 2) {
            // Make assets cacheable (e.g. maps.min.js)
            app()->call([Platform::class, 'registerAssets']);
        }

        return $result;
    }

    /**
     * Get from Joomla caching.
     *
     * @param Config   $config
     * @param Document $document
     */
    public static function afterDispatch(Config $config, Document $document)
    {
        if (!$config('joomla.config')->get('caching', 0) || !$document instanceof HtmlDocument) {
            return;
        }

        // Get keys from Joomla caching
        foreach (static::$keys as $key) {
            if (isset($document->_custom[$key])) {
                $config->set($key, $document->_custom[$key]);
                unset($document->_custom[$key]);
            }
        }
    }
}
