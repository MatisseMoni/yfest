<?php

namespace YOOtheme\Theme;

use YOOtheme\Builder;
use YOOtheme\Config;
use YOOtheme\Metadata;
use YOOtheme\Path;
use YOOtheme\Translator;
use YOOtheme\Url;

class CustomizerListener
{
    public static function initCustomizer(
        Config $config,
        Metadata $metadata,
        Translator $translator,
        Builder $builder
    ) {
        // load builder
        $config->update('~theme.footer.content', function ($footer) use ($builder) {
            return $footer ? $builder->load(json_encode($footer)) : null;
        });

        $config->update('~theme.menu.items', function ($items) use ($builder) {
            foreach ($items ?: [] as $id => $item) {
                if (!empty($item['content'])) {
                    $items[$id]['content'] = $builder->load(json_encode($item['content']));
                }
            }
            return $items;
        });

        // add config
        $config->addFile('customizer', Path::get('../../config/customizer.json'));

        // add locale
        $locale = strtr($config('locale.code'), [
            'de_AT' => 'de_DE',
            'de_CH' => 'de_DE',
            'de_CH_informal' => 'de_DE',
            'de_DE_formal' => 'de_DE',
        ]);

        $translator->addResource(Path::get("../../languages/{$locale}.json"));

        // add uikit
        $debug = $config('app.debug') ? '' : '.min';
        $metadata->set('script:uikit', ['src' => "~assets/uikit/dist/js/uikit{$debug}.js"]);
        $metadata->set('script:uikit-icons', [
            'src' => "~assets/uikit/dist/js/uikit-icons{$debug}.js",
        ]);
    }

    public static function lateInitCustomizer(
        Config $config,
        Metadata $metadata,
        Translator $translator
    ) {
        $config = [
            'url' => Url::base(),
            'route' => Url::route(),
            'csrf' => $config('session.token'),
            'locale' => $config('locale.code'),
            'locales' => $translator->getResources(),
        ];

        $metadata->set('script:config', sprintf('var $config = %s;', json_encode($config)));
    }

    public static function handleRequest(Config $config, $request, callable $next)
    {
        // Prevent image caching in customizer mode
        return $next($request->withAttribute('save', !$config->get('app.isCustomizer')));
    }
}
