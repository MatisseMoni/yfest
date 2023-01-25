<?php

use Joomla\CMS\Factory;

class pkg_yoothemeInstallerScript
{
    public function preflight($type, $parent)
    {
        $php = '5.6.0';

        // abort if PHP version < 5.6
        if (version_compare(PHP_VERSION, $php, '<')) {
            Factory::getApplication()->enqueueMessage(
                "<p>You need PHP {$php} or later to install the template.</p>",
                'warning'
            );
            return false;
        }
    }

    public function postflight($type, $parent)
    {
        // updateservers url update workaround
        if ($type == 'update' && $parent->manifest->updateservers) {
            $db = Factory::getDbo();
            $servers = $parent->manifest->updateservers->children();

            $db->setQuery(
                'UPDATE `#__update_sites` a' .
                    ' LEFT JOIN `#__update_sites_extensions` b ON b.update_site_id = a.update_site_id' .
                    ' SET location = ' .
                    $db->quote(trim((string) $servers[0])) .
                    ', enabled = 1' .
                    " WHERE b.extension_id = (SELECT `extension_id` FROM `#__extensions` WHERE `type` = 'package' AND `element` = '{$parent->getElement()}')"
            )->execute();
        }
    }
}
