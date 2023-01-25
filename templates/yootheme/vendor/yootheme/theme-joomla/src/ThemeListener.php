<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Document\Document;
use Joomla\CMS\Document\HtmlDocument;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Language;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Input\Input;
use YOOtheme\Config;
use YOOtheme\Event;
use YOOtheme\Metadata;
use YOOtheme\Theme\HighlightListener;
use YOOtheme\Url;
use function YOOtheme\app;

class ThemeListener
{
    public static function initTheme(Config $config)
    {
        // register views cache array
        if (version_compare(JVERSION, '4.0', '<') && $config('app.isSite')) {
            ViewsObject::register();
        }

        // Joomla 4 does not distribute com_search
        if (!ComponentHelper::isEnabled('com_search')) {
            $config->set('~theme.search_module', 'mod_finder');
        }
    }

    public static function initHead(Metadata $metadata)
    {
        if (version_compare(JVERSION, '4.0', '<')) {
            return;
        }

        $metadata->set('style:fontawesome', [
            'href' => Url::to('~/media/system/css/joomla-fontawesome.min.css'),
            'rel' => 'preload',
            'as' => 'style',
            'onload' => 'this.onload=null;this.rel=\'stylesheet\'',
        ]);
    }

    public static function beforeDisplay(Config $config, $event)
    {
        if ($config('app.isAdmin') || !$config('theme.active')) {
            return;
        }

        $view = $event->getArgument('subject');

        // loader callback for template event
        $loader = function ($path) use ($view) {
            // Clone view to avoid mutations on the original view
            $copy = clone $view;
            $copy->set('_output', null);
            $copy->set('context', basename($copy->get('_basePath')) . ".{$copy->getName()}");
            $tpl = substr(basename($path, '.php'), strlen($copy->getLayout()) + 1) ?: null;

            $app = Factory::getApplication();
            $app->triggerEvent('onLoadTemplate', [$copy, $tpl]);

            return $copy->get('_output');
        };

        // register the stream wrapper
        if (!in_array('views', stream_get_wrappers())) {
            stream_wrapper_register('views', StreamWrapper::class);
        }

        // add loader using a stream reference
        // check if path is available (StackIdeas com_payplan)
        if ($path = $view->get('_path')) {
            array_unshift($path['template'], 'views://' . StreamWrapper::setObject($loader));
            $view->set('_path', $path);
        }
    }

    public static function loadTemplate(Config $config, $event)
    {
        [$view] = $event->getArguments();

        $context = $view->get('context');
        $layout = $view->getLayout();

        if (in_array($context, ['com_content.category', 'com_content.featured', 'com_tags.tag'])) {
            $config->set('~theme.page_layout', 'blog');
        }

        if ($context === 'com_content.article' && $layout === 'default') {
            $item = $view->get('item');

            if ($item->category_alias !== 'uncategorised') {
                $config->set('~theme.page_layout', 'post');
            }
        }
    }

    /**
     * @param SiteApplication $cms
     */
    public static function afterDispatch(
        Config $config,
        Document $document,
        Language $language,
        CMSApplication $cms
    ) {
        if (!static::isThemeActive()) {
            return;
        }

        $language->load('tpl_yootheme', $config('theme.rootDir'));
        $document->setBase(htmlspecialchars(Uri::current()));

        $config->add('~theme', [
            'direction' => $document->getDirection(),
            'page_class' => $cms->getParams()->get('pageclass_sfx'),
        ]);

        if (PluginHelper::isEnabled('content', 'emailcloak')) {
            static::fixEmailCloak($document);
        }

        if (($custom = $config('~theme.custom_js', '')) && $document instanceof HtmlDocument) {
            static::addCustomScript($document, $custom);
        }

        if ($config('~theme.jquery') || str_contains($custom, 'jQuery')) {
            HTMLHelper::_('jquery.framework');
        }

        Event::emit('theme.head');
    }

    public static function loadModules(Config $config, CMSApplication $cms)
    {
        if (!static::isThemeActive()) {
            return;
        }

        $itemId = ($item = $cms->getMenu()->getDefault()) ? $item->id : 0;
        $siteUrl = Route::_("index.php?Itemid={$itemId}", false, 0, true);
        $config->set('~theme.site_url', $siteUrl);
    }

    public static function beforeRender(
        Config $config,
        Metadata $metadata,
        CMSApplication $application
    ) {
        /** @var HtmlDocument $document */
        $document = $application->getDocument();
        HighlightListener::checkContent($config, $metadata, $document->getBuffer('component'));
    }

    protected static function fixEmailCloak(Document $document)
    {
        $document->addScriptDeclaration("document.addEventListener('DOMContentLoaded', function() {
            Array.prototype.slice.call(document.querySelectorAll('a span[id^=\"cloak\"]')).forEach(function(span) {
                span.innerText = span.textContent;
            });
        });");
    }

    protected static function addCustomScript(HtmlDocument $document, $script)
    {
        if (stripos(trim($script), '<script') !== 0) {
            $script = "<script>{$script}</script>";
        }

        $document->addCustomTag($script);
    }

    protected static function isThemeActive()
    {
        /**
         * @var Config $config
         * @var Input   $input
         */
        [$config, $input] = app(Config::class, Input::class);

        return $config('theme.active') &&
            !$config('app.isAdmin') &&
            $input->getCmd('option') !== 'com_ajax' &&
            $input->getCmd('tmpl') !== 'component';
    }
}
