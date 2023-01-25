<?php

namespace YOOtheme\Theme;

use YOOtheme\Config;
use YOOtheme\File;
use YOOtheme\Storage;
use YOOtheme\Url;

class SettingsListener
{
    public static function initHead(Config $config)
    {
        $assets = "~yootheme/theme-{$config('app.platform')}/assets";

        $config->set('~theme.body_class', [$config('~theme.page_class')]);
        $config->set(
            '~theme.favicon',
            Url::to($config('~theme.favicon') ?: "{$assets}/images/favicon.png")
        );
        $config->set(
            '~theme.touchicon',
            Url::to($config('~theme.touchicon') ?: "{$assets}/images/apple-touch-icon.png")
        );

        if ($config('~theme.favicon_svg')) {
            $config->set('~theme.favicon_svg', Url::to($config('~theme.favicon_svg')));
        }
    }

    public static function initCustomizer(Config $config, Storage $storage)
    {
        $key = 'news';
        $hash = hash_file('crc32b', File::find('~theme/NEWS.md'));

        if ($storage($key) !== $hash) {
            $storage->set($key, $hash);
            $config->set('customizer.news', true);
        }

        if (!$config('~theme.avif') && !static::supportsImageAvif()) {
            $config->set('customizer.panels.advanced.fields.avif.attrs.disabled', 'true');
        }
    }

    public static function supportsImageAvif()
    {
        if (is_callable('imageavif') && PHP_VERSION_ID >= 80100) {
            $image = imagecreatetruecolor(1, 1);
            $resource = fopen('php://temp', 'rw+');

            // check image size, because libgd will return true even when is compiled without avif support
            return imageavif($image, $resource) && fstat($resource)['size'] > 0;
        }

        return false;
    }
}
