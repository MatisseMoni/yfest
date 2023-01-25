<?php

namespace YOOtheme;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\Module\Breadcrumbs\Site\Helper\BreadcrumbsHelper;
use Joomla\Registry\Registry;

$rand = rand();
$marker = "<!-- breadcrumbs_{$rand} -->";
$render = function () use ($__dir, $attrs, $props) {

    // Get the breadcrumbs
    $params = new Registry([
        'showHome' => $props['show_home'],
        'homeText' => Text::_($props['home_text'] ?: 'Home', 'yootheme'),
    ]);

    $items = BreadcrumbsHelper::getList($params, Factory::getApplication());

    if (!$props['show_current']) {
        array_pop($items);
    } elseif ($items) {
        $items[count($items) - 1]->link = '';
    }

    $props['items'] = $items;

    return $this->render("{$__dir}/template-breadcrumbs", compact('attrs', 'props'));
};

if ($prefix === 'page') {
    app('dispatcher')->addListener('onLoadTemplate', function ($event) use ($render, $marker) {
        [$view] = $event->getArguments();

        if ($output = $view->get('_output')) {
            $view->set('_output', str_replace($marker, $render(), $output));
        }
    });

    echo $marker;
} else {
    echo $render();
}
