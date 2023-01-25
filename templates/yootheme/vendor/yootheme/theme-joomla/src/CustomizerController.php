<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Document\HtmlDocument;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\User\User;
use YOOtheme\Config;
use YOOtheme\Event;
use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use YOOtheme\Joomla\Database;
use YOOtheme\Metadata;
use YOOtheme\Path;
use YOOtheme\Url;

class CustomizerController
{
    public static function index(
        Config $config,
        User $user,
        Metadata $metadata,
        CMSApplication $cms,
        HtmlDocument $document,
        Request $request
    ) {
        HTMLHelper::_('behavior.keepalive');

        // init customizer
        Event::emit('customizer.init');

        // init config
        $config->add('customizer', [
            'config' => $config('~theme'),
            'return' => $request->getQueryParam('return') ?: Url::to('administrator/index.php'),
        ]);

        // apikey editable?
        if (!$user->authorise('core.admin', 'com_plugins')) {
            $config->del('customizer.sections.settings.fields.settings.items.api-key');
        }

        // add style/script
        $metadata->set('style:customizer', ['href' => Path::get('../assets/css/admin.css')]);
        $metadata->set('script:customizer', ['src' => Path::get('../app/customizer.min.js')]);

        // set system template
        $cms->set('theme', 'system');
        $cms->input->set('tmpl', 'component');

        // set document title/icon
        $document->setTitle("Website Builder - {$config('joomla.config.sitename')}");
        $document->addFavicon(Url::to(Path::get('../assets/images/favicon.png')));
        $document->setBuffer('<div id="customizer"></div>', [
            'type' => 'component',
            'name' => null,
            'title' => null,
        ]);
    }

    public static function save(
        Request $request,
        Response $response,
        Config $config,
        Database $db,
        User $user
    ) {
        $request->abortIf(
            !$user->authorise('core.edit', 'com_templates'),
            403,
            'Insufficient User Rights.'
        );

        // alter custom_data type to MEDIUMTEXT only in MySQL database
        if (str_contains($db->driver, 'mysql')) {
            foreach (
                ['extensions' => 'custom_data', 'template_styles' => 'params']
                as $table => $field
            ) {
                $query = "SHOW FIELDS FROM @{$table} WHERE Field = '{$field}'";
                $alter = "ALTER TABLE @{$table} CHANGE `{$field}` `{$field}` MEDIUMTEXT NOT NULL";

                if ($db->fetchObject($query)->Type == 'text') {
                    $db->executeQuery($alter);
                }
            }
        }

        // get config values
        $values = Event::emit('config.save|filter', $request->getParam('config', []));

        // fetch current style params
        $params = $db->loadResult('SELECT params FROM #__template_styles WHERE id=:id', [
            'id' => $config('theme.id'),
        ]);

        // prepare style params
        $params = ['config' => json_encode($values)] + (json_decode($params, true) ?: []);

        // update style params
        $db->update(
            '@template_styles',
            ['params' => json_encode($params)],
            ['id' => $config('theme.id')]
        );

        return 'success';
    }
}
