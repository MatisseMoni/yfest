<?php

namespace YOOtheme;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\Event\Event;
use Joomla\Registry\Registry;

/** @var \plgSystemYOOtheme $this */
$option = $this->app->input->getCmd('option');
$options = ['com_ajax', 'com_content', 'com_templates', 'com_modules', 'com_advancedmodules'];

if ($this->app->isClient('site') || in_array($option, $options, true))  {

    // bootstrap application
    $app = require __DIR__ . '/bootstrap.php';
    $app->load(__DIR__ . '/{vendor/yootheme/{platform-joomla,theme{,-analytics,-cookie,-highlight,-settings,-joomla*},styler,builder{,-source*,-templates,-newsletter,-joomla*}}/bootstrap.php,config.php}');

} else {

    // add shortcut icon
    $this->app->registerEvent('onGetIcons', function ($event) {

        $user = Factory::getUser();
        $query = "SELECT * FROM #__template_styles WHERE client_id=0 AND home='1'";
        $context = $event instanceof Event ? $event->getArgument('context') : $event;

        if ($context !== 'mod_quickicon' || !$user->authorise('core.edit', 'com_templates')) {
            return;
        }

        if ($templ = $this->db->setQuery($query)->loadObject()) {
            $templ->params = new Registry($templ->params);

            if ($templ->params->get('yootheme')) {

                $icon = [
                    'image' => 'star fas fa-star',
                    'text' => Text::_('YOOtheme'),
                    'link' => "index.php?option=com_ajax&templateStyle={$templ->id}&p=customizer&format=html",
                ];

                if ($event instanceof Event) {
                    $event->setArgument('result', array_merge($event->getArgument('result', []), [[$icon]]));
                }

                return [$icon];
            }
        }

    });

}
