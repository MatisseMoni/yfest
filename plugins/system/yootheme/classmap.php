<?php

defined('_JEXEC') or die();

if (version_compare(JVERSION, '4.0', '<')) {
    $classes = [
        'Joomla\CMS\HTML\Helpers\Content' => 'JHtmlContent',
        'Joomla\CMS\HTML\Helpers\Menu' => 'JHtmlMenu',
        'Joomla\Component\Contact\Site\Helper\RouteHelper' => [
            'ContactHelperRoute',
            JPATH_SITE . '/components/com_contact/helpers/route.php',
        ],
        'Joomla\Component\Content\Site\Helper\RouteHelper' => [
            'ContentHelperRoute',
            JPATH_SITE . '/components/com_content/helpers/route.php',
        ],
        'Joomla\Component\Content\Site\Model\ArticlesModel' => [
            'ContentModelArticles',
            JPATH_SITE . '/components/com_content/models/articles.php',
        ],
        'Joomla\Component\Fields\Administrator\Helper\FieldsHelper' => [
            'FieldsHelper',
            JPATH_ADMINISTRATOR . '/components/com_fields/helpers/fields.php',
        ],
        'Joomla\Component\Fields\Administrator\Plugin\FieldsPlugin' => [
            'FieldsPlugin',
            JPATH_ADMINISTRATOR . '/components/com_fields/libraries/fieldsplugin.php',
        ],
        'Joomla\Component\Finder\Site\Helper\RouteHelper' => [
            'FinderHelperRoute',
            JPATH_SITE . '/components/com_finder/helpers/route.php',
        ],
        'Joomla\Component\Tags\Administrator\Table\TagTable' => [
            'TagsTableTag',
            JPATH_ADMINISTRATOR . '/components/com_tags/tables/tag.php',
        ],
        'Joomla\Component\Tags\Site\Helper\RouteHelper' => [
            'TagsHelperRoute',
            JPATH_SITE . '/components/com_tags/helpers/route.php',
        ],
        'Joomla\Component\Tags\Site\Model\TagModel' => [
            'TagsModelTag',
            JPATH_SITE . '/components/com_tags/models/tag.php',
        ],
        'Joomla\Component\Tags\Site\Model\TagsModel' => [
            'TagsModelTags',
            JPATH_SITE . '/components/com_tags/models/tags.php',
        ],
        'Joomla\Component\Users\Administrator\Helper\UsersHelper' => [
            'UsersHelper',
            JPATH_ADMINISTRATOR . '/components/com_users/helpers/users.php',
        ],
        'Joomla\Module\Breadcrumbs\Site\Helper\BreadcrumbsHelper' => [
            'ModBreadCrumbsHelper',
            JPATH_SITE . '/modules/mod_breadcrumbs/helper.php',
        ],
    ];

    if (version_compare(JVERSION, '3.9', '<')) {
        $classes += [
            'Joomla\CMS\Filesystem\File' => 'JFile',
            'Joomla\CMS\Filesystem\Folder' => 'JFolder',
            'Joomla\CMS\Filesystem\Path' => 'JPath',
        ];
    }

    if (version_compare(JVERSION, '3.8', '<')) {
        $classes += [
            'Joomla\CMS\Access\Access' => 'JAccess',
            'Joomla\CMS\Component\ComponentHelper' => 'JComponentHelper',
            'Joomla\CMS\Date\Date' => 'JDate',
            'Joomla\CMS\Document\DocumentRenderer' => 'JDocumentRenderer',
            'Joomla\CMS\Editor\Editor' => 'JEditor',
            'Joomla\CMS\Factory' => 'JFactory',
            'Joomla\CMS\Form\FormField' => 'JFormField',
            'Joomla\CMS\Helper\MediaHelper' => 'JHelperMedia',
            'Joomla\CMS\Helper\ModuleHelper' => 'JModuleHelper',
            'Joomla\CMS\Helper\RouteHelper' => 'JHelperRoute',
            'Joomla\CMS\Helper\TagsHelper' => 'JHelperTags',
            'Joomla\CMS\HTML\HTMLHelper' => 'JHtml',
            'Joomla\CMS\Http\HttpFactory' => 'JHttpFactory',
            'Joomla\CMS\Language\Multilanguage' => 'JLanguageMultilang',
            'Joomla\CMS\Language\Text' => 'JText',
            'Joomla\CMS\Layout\LayoutHelper' => 'JLayoutHelper',
            'Joomla\CMS\Menu\AbstractMenu' => 'JMenu',
            'Joomla\CMS\MVC\Controller\BaseController' => 'JControllerLegacy',
            'Joomla\CMS\MVC\Model\BaseDatabaseModel' => 'JModelLegacy',
            'Joomla\CMS\Plugin\CMSPlugin' => 'JPlugin',
            'Joomla\CMS\Plugin\PluginHelper' => 'JPluginHelper',
            'Joomla\CMS\Router\Route' => 'JRoute',
            'Joomla\CMS\Router\Router' => 'JRouter',
            'Joomla\CMS\Session\Session' => 'JSession',
            'Joomla\CMS\Uri\Uri' => 'JUri',
        ];
    }

    spl_autoload_register(function ($class_name) use ($classes) {
        if (empty($classes[$class_name])) {
            return;
        }

        if (is_array($class = $classes[$class_name])) {
            [$class, $path] = $class;
            if (!class_exists($class, false)) {
                require_once $path;
            }
        }

        class_alias($class, $class_name);
    });
}
