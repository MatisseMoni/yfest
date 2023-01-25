<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Document\DocumentRenderer;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\User\User;
use function YOOtheme\app;
use YOOtheme\Config;
use YOOtheme\View;

class ModulesRenderer extends DocumentRenderer
{
    public function render($position, $params = [], $content = null)
    {
        [$config, $view, $user] = app(Config::class, View::class, User::class);

        $modules = ModuleHelper::getModules($position);
        $renderer = $this->_doc->loadRenderer('module');

        $frontEdit =
            $config('app.isSite') && $config('joomla.config.frontediting', 1) && !$user->guest;
        $menusEdit =
            $config('joomla.config.frontediting', 1) == 2 &&
            $user->authorise('core.edit', 'com_menus');

        foreach ($modules as $module) {
            $moduleHtml = $renderer->render($module, $params, $content);

            if (!isset($module->attrs)) {
                $module->attrs = [];
            }

            if (
                $frontEdit &&
                trim($moduleHtml) != '' &&
                $user->authorise('module.edit.frontend', "com_modules.module.{$module->id}")
            ) {
                $displayData = [
                    'moduleHtml' => &$moduleHtml,
                    'module' => $module,
                    'position' => $position,
                    'menusediting' => $menusEdit,
                ];
                LayoutHelper::render('joomla.edit.frontediting_modules', $displayData);
            }

            $module->content = $moduleHtml;
        }

        return $view('~theme/templates/position', ['items' => $modules] + $params);
    }
}
