<?php

namespace YOOtheme\Joomla;

use Joomla\CMS\Factory;
use YOOtheme\Database as DatabaseInterface;
use YOOtheme\Storage as AbstractStorage;

class Storage extends AbstractStorage
{
    /**
     * Constructor.
     *
     * @param Database $db
     * @param string   $element
     * @param string   $folder
     *
     * @throws \Exception
     */
    public function __construct(DatabaseInterface $db, $element = 'yootheme', $folder = 'system')
    {
        $query =
            'SELECT custom_data FROM @extensions WHERE element = :element AND folder = :folder LIMIT 1';

        if ($result = $db->fetchAssoc($query, ['element' => $element, 'folder' => $folder])) {
            $this->addJson($result['custom_data']);
        }

        $app = Factory::getApplication();
        $app->registerEvent('onAfterRespond', function () use ($db, $element, $folder) {
            if ($this->isModified()) {
                $data = json_encode($this, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

                if ($data !== false) {
                    $db->update(
                        '@extensions',
                        ['custom_data' => $data],
                        ['element' => $element, 'folder' => $folder]
                    );
                }
            }
        });
    }
}
