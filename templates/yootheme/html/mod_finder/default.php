<?php

namespace YOOtheme;

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

$view = app(View::class);

$fields = [[
    'tag' => 'input',
    'name' => 'q',
    'class' => $params->get('show_autosuggest', 1) ? ['js-finder-search-query'] : [],
    'value' => $app->input->getCmd('option') === 'com_finder' ? urldecode($app->input->getString('q', '')) : false,
    'placeholder' => Text::_('TPL_YOOTHEME_SEARCH'),
    'required' => true,
]];

$uri = Uri::getInstance(Route::_($route));
$uri->delVar('q');

// Create hidden input elements for each part of the URI.
foreach ($uri->getQuery(true) as $name => $value) {
    $fields[] = ['tag' => 'input', 'type' => 'hidden', 'name' => $name, 'value' => $value];
}

echo $view('~theme/templates/search', [

    'position' => $module->position,

    'tag' => $module->attrs,

    'attrs' => [
        'id' => "search-{$module->id}",
        'action' => Route::_($route),
        'method' => 'get',
        'role' => 'search',
        'class' => ['js-finder-searchform']
    ],

    'fields' => $fields,

    'iconClass' => [
        'uk-position-z-index' => $params->get('show_autosuggest', 1), // Needed because of `awesomplete` HTML class has a `z-index`
    ],

]);

// This segment of code sets up the autocompleter.
if ($params->get('show_autosuggest', 1)) {
    $document = $app->getDocument();

    if (version_compare(JVERSION, '4.0', '<')) {
        HTMLHelper::_('behavior.core');
        $document->addStylesheet(
            Url::to(Path::get('~theme/html/com_finder/assets/awesomplete/css/awesomplete.css')),
            ['version' => 'auto']
        );
        $document->addScript(
            Url::to(Path::get('~theme/html/com_finder/assets/awesomplete/js/awesomplete.min.js')),
            ['version' => 'auto']
        );
        $document->addScript(
            Url::to(Path::get('~theme/html/com_finder/assets/com_finder/js/finder.min.js')),
            ['version' => 'auto'],
            ['defer' => true]
        );
    } else {
        $assetManager = $document->getWebAssetManager();
        $assetManager->usePreset('awesomplete');
        $assetManager->getRegistry()->addExtensionRegistryFile('com_finder');
        $assetManager->useScript('com_finder.finder');
    }
    $document->addScriptOptions('finder-search', ['url' => Route::_('index.php?option=com_finder&task=suggestions.suggest&format=json&tmpl=component')]);
}
