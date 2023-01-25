<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Plugin\PluginHelper;
use YOOtheme\Arr;
use YOOtheme\Config;
use YOOtheme\Database;
use YOOtheme\Path;

class SystemCheck extends \YOOtheme\Theme\SystemCheck
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
     * SystemCheck constructor.
     *
     * @param Database $database
     * @param Config   $config
     */
    public function __construct(Database $database, Config $config)
    {
        $this->config = $config;
        $this->database = $database;
    }

    /**
     * @inheritdoc
     */
    public function getRequirements()
    {
        $res = [];

        // Installer Plugin missing?
        if (!PluginHelper::isEnabled('installer', 'yootheme')) {
            $res[] = 'j_yootheme_installer';
        }

        // Check for SEBLOD Plugin and setting
        $components = ComponentHelper::getComponents();
        $cck = $components['com_cck'] ?? false;
        if ($cck && $cck->enabled == 1) {
            if ($cck->getParams()->get('hide_edit_icon')) {
                $res[] = 'j_seblod_icon';
            }
        }

        try {
            // Check for RSFirewall settings @TODO check if enabled?
            $rsfw = $this->database->fetchAssoc(
                "SELECT value FROM @rsfirewall_configuration WHERE name = 'verify_emails'"
            );

            if ($rsfw['value'] == 1) {
                $res[] = 'j_rsfw_mail';
            }
        } catch (\Exception $e) {
        }

        // Check if paths in FileSystem plugin matches global config in Joomla 4
        if (
            version_compare(JVERSION, '4.0', '>') &&
            ($filesystem = PluginHelper::getPlugin('filesystem', 'local')) &&
            ($params = json_decode($filesystem->params, true)) &&
            !empty($params['directories']) &&
            !Arr::find($params['directories'], [
                'directory' => Path::relative(JPATH_ROOT, $this->config->get('app.uploadDir')),
            ])
        ) {
            $res[] = 'j_upload_dir';
        }

        return array_merge($res, parent::getRequirements());
    }

    /**
     * @inheritdoc
     */
    public function getRecommendations()
    {
        $res = [];

        if (!$this->config->get('app.apikey')) {
            $res[] = 'j_apikey';
        }

        return array_merge($res, parent::getRecommendations());
    }
}
