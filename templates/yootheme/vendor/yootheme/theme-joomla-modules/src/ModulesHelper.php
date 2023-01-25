<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Language;
use Joomla\CMS\Language\Text;
use Joomla\CMS\User\User;
use YOOtheme\Config;
use YOOtheme\Database;
use function YOOtheme\app;

class ModulesHelper
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Database
     */
    protected $database;

    /**
     * @var Language
     */
    protected $language;

    /**
     * Constructor.
     *
     * @param Config   $config
     * @param Database $database
     * @param Language $language
     */
    public function __construct(Config $config, Database $database, Language $language)
    {
        $this->config = $config;
        $this->database = $database;
        $this->language = $language;
    }

    public function getTypes()
    {
        $types = $this->database->fetchAll(
            "SELECT name, element FROM @extensions WHERE client_id = 0 AND type = 'module'"
        );

        $data = [];

        foreach ($types as $type) {
            $this->language->load("{$type['element']}.sys", JPATH_SITE, null, false, true);
            $data[$type['element']] = Text::_($type['name']);
        }

        natsort($data);

        return $data;
    }

    public function getModules()
    {
        $user = app(User::class);

        $modules = $this->database->fetchAll(
            'SELECT id, title, module, position, ordering FROM @modules WHERE client_id = 0 AND published != -2 ORDER BY position, ordering'
        );

        foreach ($modules as &$module) {
            $module += [
                'canEdit' => $user->authorise('core.edit', "com_modules.module.{$module['id']}"),
                'canDelete' => $user->authorise(
                    'core.edit.state',
                    "com_modules.module.{$module['id']}"
                ),
            ];
            // In Joomla 4 `id` is int
            $module['id'] = (string) $module['id'];
        }

        return $modules;
    }

    public function getPositions()
    {
        return array_values(
            array_unique(
                array_merge(
                    array_keys($this->config->get('theme.positions', [])),
                    Factory::getDbo()
                        ->setQuery(
                            'SELECT DISTINCT(position) FROM #__modules WHERE client_id = 0 ORDER BY position'
                        )
                        ->loadColumn()
                )
            )
        );
    }
}
