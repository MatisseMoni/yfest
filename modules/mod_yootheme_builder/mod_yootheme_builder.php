<?php

use Joomla\CMS\Helper\ModuleHelper;
use function YOOtheme\app;
use YOOtheme\View;

defined('_JEXEC') or die();

if (!$module->content) {
    $module->content = '{}';
}

$module->content = app(View::class)->builder($module->content, [
    'prefix' => "module-{$module->id}",
]);

if (in_array($module->position, ['top', 'bottom'])) {
    $module->content = "<div id=\"module-{$module->id}\" class=\"builder\">{$module->content}</div>";
}

require ModuleHelper::getLayoutPath('mod_yootheme_builder', $params->get('layout', 'default'));
