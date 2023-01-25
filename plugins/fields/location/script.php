<?php

use Joomla\CMS\Factory;

defined('_JEXEC') or die();

class plgFieldsLocationInstallerScript
{
    public function install($parent)
    {
    }

    public function uninstall($parent)
    {
    }

    public function update($parent)
    {
    }

    public function preflight($type, $parent)
    {
    }

    public function postflight($type, $parent)
    {
        if (!in_array($type, ['install', 'update'])) {
            return;
        }

        Factory::getDbo()
            ->setQuery(
                'UPDATE #__extensions SET ' .
                    ($type == 'install' ? 'enabled = 1, ' : '') .
                    "ordering = 0 WHERE type = 'plugin' AND folder = 'fields' AND element = 'location'"
            )
            ->execute();
    }
}
