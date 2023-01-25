<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Document\Document;
use Joomla\CMS\Document\HtmlDocument;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\User\User;
use YOOtheme\Config;
use YOOtheme\Metadata;
use YOOtheme\Path;
use YOOtheme\Translator;
use YOOtheme\View;

class ModulesListener
{
    public static function initTheme(Config $config)
    {
        if ($config('app.isSite')) {
            $renderer = version_compare(JVERSION, '3.8', '>=')
                ? 'Joomla\CMS\Document\Renderer\Html\ModulesRenderer'
                : 'JDocumentRendererHtmlModules';

            class_alias('YOOtheme\Theme\Joomla\ModulesRenderer', $renderer);
        }
    }

    public static function initCustomizer(Config $config, User $user, ModulesHelper $helper)
    {
        $config->add('customizer', [
            'module' => [
                'types' => $helper->getTypes(),
                'modules' => $helper->getModules(),
                'positions' => $helper->getPositions(),
                'canCreate' => $user->authorise('core.create', 'com_modules'),
            ],
        ]);

        if ($user->authorise('core.manage', 'com_modules')) {
            $component = PluginHelper::isEnabled('system', 'advancedmodules')
                ? 'com_advancedmodules'
                : 'com_modules';

            $config->set(
                'customizer.sections.joomla-modules.url',
                "administrator/index.php?option={$component}"
            );
            $config->addFile('customizer', Path::get('../config/customizer.json'));
        }
    }

    public static function loadModules(Config $config, Document $document, View $view, $event)
    {
        [$modules] = $event->getArguments();

        if (
            $config('app.isAdmin') ||
            !$config('theme.active') ||
            !$document instanceof HtmlDocument
        ) {
            return;
        }

        $view['sections']->add('breadcrumbs', function () use ($config) {
            return ModuleHelper::renderModule(
                static::createModule([
                    'module' => 'mod_breadcrumbs',
                    'params' => [
                        'showLast' => $config('~theme.site.breadcrumbs_show_current'),
                        'showHome' => $config('~theme.site.breadcrumbs_show_home'),
                        'homeText' => $config('~theme.site.breadcrumbs_home_text'),
                    ],
                ])
            );
        });

        // Logo Module
        foreach (['logo', 'logo-mobile', 'dialog', 'dialog-mobile'] as $position) {
            if ($content = trim($view('~theme/templates/header-logo', ['position' => $position]))) {
                $module = static::createModule([
                    'module' => 'mod_custom',
                    'position' => $position,
                    'content' => $content,
                    'type' => 'logo',
                    'params' => ['layout' => 'blank'],
                ]);
                array_unshift($modules, $module);
            }
        }

        // Search Module
        foreach (['~theme.header.search', '~theme.mobile.header.search'] as $key) {
            if ($position = $config($key)) {
                $position = explode(':', $position, 2);

                $params = [];
                if ($config('~theme.search_module') === 'mod_finder') {
                    $params['show_autosuggest'] = ComponentHelper::getParams('com_finder')->get(
                        'show_autosuggest',
                        1
                    );
                }

                $module = static::createModule([
                    'module' => $config('~theme.search_module'),
                    'position' => $position[0],
                    'params' => $params,
                ]);

                $position[1] == 'start'
                    ? array_unshift($modules, $module)
                    : array_push($modules, $module);
            }
        }

        // Social Module
        foreach (['~theme.header.social', '~theme.mobile.header.social'] as $key) {
            if (
                $config($key) &&
                ($content = trim(
                    $view('~theme/templates/socials', [
                        'position' => ($position = explode(':', $config($key), 2))[0],
                    ])
                ))
            ) {
                $module = static::createModule([
                    'module' => 'mod_custom',
                    'position' => $position[0],
                    'content' => $content,
                    'params' => ['layout' => 'blank'],
                ]);

                $position[1] == 'start'
                    ? array_unshift($modules, $module)
                    : array_push($modules, $module);
            }
        }

        // Dialog Toggle Module
        foreach (['~theme.dialog.toggle', '~theme.mobile.dialog.toggle'] as $key) {
            if (
                $config($key) &&
                ($content = trim(
                    $view('~theme/templates/header-dialog', [
                        'position' => ($position = explode(':', $config($key), 2))[0],
                    ])
                ))
            ) {
                $module = static::createModule([
                    'module' => 'mod_custom',
                    'position' => $position[0],
                    'content' => $content,
                    'type' => 'dialog-toggle',
                    'params' => ['layout' => 'blank'],
                ]);

                $position[1] == 'start'
                    ? array_unshift($modules, $module)
                    : array_push($modules, $module);
            }
        }

        // Split Header Position
        if ($config('~theme.header.layout') === 'stacked-center-c') {
            $headerModules = self::filterModules($modules, 'header');

            // Split Auto
            $index = $config('~theme.header.split_index') ?: ceil(count($headerModules) / 2);

            foreach (array_slice($headerModules, $index) as $module) {
                $module->position .= '-split';
            }
        }

        // Push Navbar Position
        if (
            $config('~theme.header.layout') === 'stacked-left' &&
            ($index = $config('~theme.header.push_index'))
        ) {
            $navbarModules = self::filterModules($modules, 'navbar');

            foreach (array_slice($navbarModules, $index) as $module) {
                $module->position .= '-push';
            }
        }

        // Push Dialog Positions
        foreach (
            [
                'dialog' => '~theme.dialog.push_index',
                'dialog-mobile' => '~theme.mobile.dialog.push_index',
            ]
            as $key => $value
        ) {
            if ($index = $config->get($value)) {
                $dialogModules = self::filterModules($modules, $key);

                foreach (array_slice($dialogModules, $index) as $module) {
                    $module->position .= '-push';
                }
            }
        }

        $temp = $config('req.customizer.module');

        // Module field defaults (Template Tab in Module edit view)
        $defaults = array_map(function ($field) {
            return $field['default'] ?? '';
        }, $config->loadFile(Path::get('../config/modules.json'))['fields']);

        foreach ($modules as $module) {
            if (!isset($module->type)) {
                $module->type = str_replace('mod_', '', $module->module);
            }
            $module->attrs = ['id' => "module-{$module->id}", 'class' => []];

            if ($temp && $temp['id'] == $module->id) {
                $module->content = $temp['content'];
            }

            $config->update("~theme.modules.{$module->id}", function ($values) use (
                $module,
                $defaults
            ) {
                $params = json_decode($module->params);

                if (isset($params->yoo_config)) {
                    $config = $params->yoo_config;
                } elseif (isset($params->config)) {
                    $config = $params->config;
                } else {
                    $config = '{}';
                }

                return [
                    'showtitle' => $module->showtitle,
                    'class' => [$params->moduleclass_sfx ?? ''],
                    'title_tag' => $params->header_tag ?? 'h3',
                    'title_class' => $params->header_class ?? '',
                    'is_list' => in_array($module->type, [
                        'articles_archive',
                        'articles_categories',
                        'articles_category',
                        'articles_latest',
                        'articles_popular',
                        'tags_popular',
                        'tags_similar',
                    ]),
                ] +
                    json_decode($config, true) +
                    $defaults +
                    ($values ?: []);
            });
        }

        $event->setArgument(0, $modules);
    }

    public static function editModule(
        Config $config,
        Metadata $metadata,
        Translator $translator,
        $event
    ) {
        [$form, $data] = $event->getArguments();

        if (
            !in_array($form->getName(), [
                'com_modules.module',
                'com_advancedmodules.module',
                'com_config.modules',
            ])
        ) {
            return;
        }

        // don't show theme settings in builder module
        if (isset($data->module) && $data->module == 'mod_yootheme_builder') {
            return;
        }

        if (!isset($data->params['yoo_config']) && isset($data->params['config'])) {
            $data->params['yoo_config'] = $data->params['config'];
        }

        $module = $config->loadFile(Path::get('../config/modules.json'));
        $module['locales'] = $translator->getResources();

        $metadata->set('script:module-data', sprintf('var $module = %s;', json_encode($module)));
        $metadata->set('script:module-edit', [
            'src' => Path::get('../app/module-edit.min.js'),
            'defer' => true,
        ]);

        $form->load(
            '<form><fields name="params"><fieldset name="template" label="Template"><field name="yoo_config" type="hidden" default="{}" /></fieldset></fields></form>'
        );
    }

    public static function createModule($module)
    {
        static $id = 0;

        $module = (object) array_merge(
            [
                'id' => 'tm-' . ++$id,
                'name' => 'tm-' . $id, // Joomla\CMS\Helper\ModuleHelper::getModule() requires 'name'
                'title' => '',
                'showtitle' => 0,
                'position' => '',
                'params' => '{}',
            ],
            (array) $module
        );

        if (is_array($module->params)) {
            $module->params = json_encode($module->params);
        }

        return $module;
    }

    protected static function filterModules($modules, $position)
    {
        return array_filter($modules, function ($module) use ($position) {
            return $module->position === $position;
        });
    }
}
