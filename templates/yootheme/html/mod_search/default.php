<?php

namespace YOOtheme;

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

$view = app(View::class);

echo $view('~theme/templates/search', [

    'position' => $module->position,

    'tag' => $module->attrs,

    'attrs' => [
        'id' => "search-{$module->id}",
        'action' => Route::_('index.php'),
        'method' => 'post',
        'role' => 'search'
    ],

    'fields' => [
        [
            'tag' => 'input',
            'name' => 'searchword',
            'value' => $app->input->getCmd('option') === 'com_search' ? urldecode($app->input->getString('searchword', '')) : false,
            'placeholder' => $params->get('text', Text::_('TPL_YOOTHEME_SEARCH')),
            'minlength' => '3',
            'aria-label' => Text::_('TPL_YOOTHEME_SEARCH')
        ],
        ['tag' => 'input', 'type' => 'hidden', 'name' => 'task', 'value' => 'search'],
        ['tag' => 'input', 'type' => 'hidden', 'name' => 'option', 'value' => 'com_search'],
        ['tag' => 'input', 'type' => 'hidden', 'name' => 'Itemid', 'value' => $mitemid],
    ],

]);
