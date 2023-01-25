<?php

namespace YOOtheme\Builder\Joomla\Source;

use Joomla\CMS\Access\Access;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\UserGroupsHelper;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Router\Route;
use Joomla\Component\Contact\Site\Helper\RouteHelper;
use Joomla\Component\Users\Administrator\Model\UsersModel;
use function YOOtheme\app;
use YOOtheme\Database;

class UserHelper
{
    /**
     * Gets the user's contact.
     *
     * @param int $id
     *
     * @return object|null
     */
    public static function getContact($id)
    {
        static $contacts = [];

        if (!isset($contacts[$id])) {
            /** @var Database $db */
            $db = app(Database::class);

            $query = 'SELECT id AS contactid, alias, catid
                FROM #__contact_details
                WHERE published = 1 AND user_id = :id';

            $params = ['id' => $id];

            if (Multilanguage::isEnabled() === true) {
                $query .= ' AND (language in (:lang) OR language IS NULL)';
                $params += ['lang' => [Factory::getLanguage()->getTag(), '*']];
            }

            $query .= ' ORDER BY id DESC LIMIT 1';

            $contacts[$id] = $db->fetchObject($query, $params) ?: false;
        }

        return $contacts[$id] ?: null;
    }

    /**
     * Gets the user's contact link.
     *
     * @param int $id
     *
     * @return string|null
     */
    public static function getContactLink($id)
    {
        if (!($contact = self::getContact($id))) {
            return null;
        }

        return Route::_(RouteHelper::getContactRoute($contact->contactid, (int) $contact->catid));
    }

    /**
     * Query users.
     *
     * @param array $args
     *
     * @return array
     */
    public static function query(array $args = [])
    {
        BaseDatabaseModel::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_users/models/');

        /** @var UsersModel $model */
        $model = BaseDatabaseModel::getInstance('users', 'UsersModel', ['ignore_request' => true]);
        $model->setState('params', ComponentHelper::getParams('com_users'));
        $model->setState('filter.active', true);
        $model->setState('filter.state', 0);

        $props = [
            'offset' => 'list.start',
            'limit' => 'list.limit',
            'order' => 'list.ordering',
            'order_direction' => 'list.direction',
            'groups' => 'filter.groups',
        ];

        if (empty($args['groups'])) {
            unset($args['groups']);
        }

        foreach (array_intersect_key($props, $args) as $key => $prop) {
            $model->setState($prop, $args[$key]);
        }

        return $model->getItems();
    }

    public static function getAuthorList()
    {
        /** @var Database $db */
        $db = app(Database::class);

        $usergroups = array_filter(
            array_map(function ($usergroup) {
                return $usergroup->id;
            }, UserGroupsHelper::getInstance()->getAll()),
            function ($id) {
                return Access::checkGroup($id, 'core.create', 'com_content') ||
                    Access::checkGroup($id, 'core.admin');
            }
        );

        $query = 'SELECT DISTINCT(user_id) as value, u.name as text
                    FROM #__usergroups as ug1
                    JOIN #__usergroups AS ug2 ON ug2.lft >= ug1.lft AND ug1.rgt >= ug2.rgt
                    JOIN #__user_usergroup_map AS m ON ug2.id=m.group_id
                    JOIN #__users AS u ON u.id=user_id
                    WHERE ug1.id IN (:groups)';

        return $db->fetchAllObjects($query, ['groups' => $usergroups]);
    }
}
