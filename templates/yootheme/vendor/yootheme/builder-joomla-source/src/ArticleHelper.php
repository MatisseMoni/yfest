<?php

namespace YOOtheme\Builder\Joomla\Source;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Object\CMSObject;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Registry\Registry;

class ArticleHelper
{
    /**
     * Gets the articles.
     *
     * @param int[] $ids
     * @param array $args
     *
     * @return object[]
     */
    public static function get($ids, array $args = [])
    {
        return $ids ? static::query(['article' => (array) $ids] + $args) : [];
    }

    /**
     * Query articles.
     *
     * @param array $args
     *
     * @return object[]
     */
    public static function query(array $args = [])
    {
        $model = new ArticlesModel(['ignore_request' => true]);
        $model->setState('params', ComponentHelper::getParams('com_content'));
        $model->setState('filter.access', true);
        $model->setState('filter.published', 1);
        $model->setState('filter.language', Multilanguage::isEnabled());
        $model->setState('filter.subcategories', false);

        $args += [
            'article_operator' => 'IN',
            'cat_operator' => 'IN',
            'tag_operator' => 'IN',
            'users_operator' => 'IN',
        ];

        if (!empty($args['order'])) {
            if ($args['order'] === 'rand') {
                $args['order'] = Factory::getDbo()
                    ->getQuery(true)
                    ->Rand();
            } elseif ($args['order'] === 'front') {
                $args['order'] = 'fp.ordering';
            } else {
                $args['order'] = "a.{$args['order']}";
            }
        }

        $props = [
            'offset' => 'list.start',
            'limit' => 'list.limit',
            'order' => 'list.ordering',
            'order_direction' => 'list.direction',
            'order_alphanum' => 'list.alphanum',
            'featured' => 'filter.featured',
            'subcategories' => 'filter.subcategories',
            'tags' => 'filter.tags',
            'tag_operator' => 'filter.tag_operator',
        ];

        foreach (array_intersect_key($props, $args) as $key => $prop) {
            $model->setState($prop, $args[$key]);
        }

        if (!empty($args['article'])) {
            $model->setState('filter.article_id', (array) $args['article']);
            $model->setState('filter.article_id.include', $args['article_operator'] === 'IN');
        }

        if (!empty($args['catid'])) {
            $model->setState('filter.category_id', (array) $args['catid']);
            $model->setState('filter.category_id.include', $args['cat_operator'] === 'IN');
        }

        if (!empty($args['users'])) {
            $model->setState('filter.author_id', (array) $args['users']);
            $model->setState('filter.author_id.include', $args['users_operator'] === 'IN');
        }

        return $model->getItems();
    }

    public static function applyPageNavigation($article)
    {
        if (!isset($article->pagination)) {
            $p = clone $article->params;
            $p->set('show_item_navigation', true);

            if (!PluginHelper::importPlugin('content', 'pagenavigation')) {
                return null;
            }

            $reflection = new \ReflectionClass(\PlgContentPagenavigation::class);
            $plugin = $reflection->newInstanceWithoutConstructor();
            $plugin->params = new Registry(['display' => 0]);
            $plugin->onContentBeforeDisplay('com_content.article', $article, $p, 0);
        }

        return !empty($article->prev) || !empty($article->next);
    }
}
