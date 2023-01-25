<?php

namespace YOOtheme\Builder\Joomla\Source\Type;

use Joomla\CMS\Router\Route;
use Joomla\CMS\Router\Router;
use Joomla\Uri\Uri;
use YOOtheme\Builder\Joomla\Source\ArticleHelper;
use function YOOtheme\trans;

class ArticleQueryType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [
            'fields' => [
                'article' => [
                    'type' => 'Article',
                    'metadata' => [
                        'label' => trans('Article'),
                        'view' => ['com_content.article'],
                        'group' => trans('Page'),
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolve',
                    ],
                ],
                'prevArticle' => [
                    'type' => 'Article',
                    'metadata' => [
                        'label' => trans('Previous Article'),
                        'view' => ['com_content.article'],
                        'group' => trans('Page'),
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolvePreviousArticle',
                    ],
                ],
                'nextArticle' => [
                    'type' => 'Article',
                    'metadata' => [
                        'label' => trans('Next Article'),
                        'view' => ['com_content.article'],
                        'group' => trans('Page'),
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveNextArticle',
                    ],
                ],
            ],
        ];
    }

    public static function resolve($root)
    {
        if (isset($root['article'])) {
            return $root['article'];
        }

        if (isset($root['item'])) {
            return $root['item'];
        }
    }

    public static function resolvePreviousArticle($root)
    {
        $article = static::resolve($root);

        if (!$article) {
            return;
        }

        ArticleHelper::applyPageNavigation($article);

        if (!empty($article->prev)) {
            return static::getArticleFromUrl($article->prev);
        }
    }

    public static function resolveNextArticle($root)
    {
        $article = static::resolve($root);

        if (!$article) {
            return;
        }

        ArticleHelper::applyPageNavigation($article);

        if (!empty($article->next)) {
            return static::getArticleFromUrl($article->next);
        }
    }

    protected static function getArticleFromUrl($url)
    {
        if (version_compare(JVERSION, '4.0', '<')) {
            $uri = new Uri(Route::_($url));
            $vars = Router::getInstance('site')->parse($uri);
            $id = $vars['id'] ?? 0;
        } else {
            $id = (new Uri($url))->getVar('id', '0');
        }

        if (!$id) {
            return null;
        }

        $articles = ArticleHelper::get($id);
        return array_shift($articles);
    }
}
