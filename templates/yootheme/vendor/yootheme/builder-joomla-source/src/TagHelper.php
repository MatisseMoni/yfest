<?php

namespace YOOtheme\Builder\Joomla\Source;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Object\CMSObject;
use Joomla\Component\Tags\Administrator\Table\TagTable;

class TagHelper
{
    /**
     * Gets the tags.
     *
     * @param int[] $ids
     *
     * @return object[]
     */
    public static function get($ids)
    {
        $tags = [];

        // Get a level row instance.
        $table = new TagTable(Factory::getDbo());

        foreach ((array) $ids as $id) {
            $table->load($id);

            if ($table->get('published') != 1) {
                continue;
            }
            if (!in_array($table->get('access'), Factory::getUser()->getAuthorisedViewLevels())) {
                continue;
            }

            $tags[] = (object) $table->getProperties(true);
        }

        return $tags;
    }

    public static function query($args = [])
    {
        $model = new TagsModel(['ignore_request' => true]);
        $params = ComponentHelper::getParams('com_tags');
        $params->set('show_pagination_limit', false);
        $params->set('published', 1);

        $model->setState('tag.parent_id', !empty($args['parent_id']) ? $args['parent_id'] : 0);
        $model->setState('tag.language', Multilanguage::isEnabled() ? 'current_language' : 'all');

        $props = [
            'limit' => 'maximum',
            'order' => 'all_tags_orderby',
            'order_direction' => 'all_tags_orderby_direction',
            'offset' => 'list.start',
        ];

        foreach (array_intersect_key($props, $args) as $key => $prop) {
            $params->set($prop, $args[$key]);
        }

        $model->setState('params', $params);

        return $model->getItems();
    }

    public static function filterTags($tags, $parentId)
    {
        $parent = current(static::get($parentId));

        return $parent
            ? array_filter($tags, function ($tag) use ($parent) {
                return $tag->lft > $parent->lft && $tag->rgt < $parent->rgt;
            })
            : [];
    }
}
