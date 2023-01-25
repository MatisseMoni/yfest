<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Factory;
use Joomla\CMS\Router\Router;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Content\Site\Helper\RouteHelper;
use Joomla\Registry\Registry;
use YOOtheme\Config;
use YOOtheme\Metadata;
use YOOtheme\Path;
use YOOtheme\Url;

class ArticlesListener
{
    public static function prepareData(Config $config, Metadata $metadata, $event)
    {
        [$context, $article] = $event->getArguments();

        if ($context !== 'com_content.article') {
            return;
        }

        // on error $article is an array instead of object
        $article = (object) $article;
        $template = static::getTemplate();

        if (empty($article->id) || empty($template->id)) {
            return;
        }

        $articles = json_encode([
            'context' => $context,
            'apikey' => $config('app.apikey'),
            'url' => Url::route('customizer', [
                'templateStyle' => $template->id,
                'section' => 'builder',
                'format' => 'html',
                'site' => static::getRoute($article, $config),
            ]),
        ]);

        $metadata->set('script:articles-data', "var \$articles = {$articles};");
        $metadata->set('script:articles', [
            'src' => Path::get('../app/articles.min.js'),
            'defer' => true,
        ]);
    }

    public static function beforeSave(Config $config, $event)
    {
        [$context, $article] = $event->getArguments();

        if (!in_array($context, ['com_content.form', 'com_content.article'], true)) {
            return;
        }

        // use "jform.articletext" from request to keep builder data, when JText filters are active
        if (
            preg_match('/<!--\s{.*}\s-->\s*$/', $config('req.body.jform.articletext', ''), $matches)
        ) {
            $article->fulltext = $matches[0];
        }
    }

    protected static function getRoute($article, $config)
    {
        $route = RouteHelper::getArticleRoute($article->id, $article->catid, $article->language);

        // Workaround for sh404sef to get article link with language code
        if ($config('app.isAdmin') && defined('SH404SEF_IS_RUNNING')) {
            $site = \Sh404sefHelperGeneral::getSefFromNonSef($route);
        } else {
            $site = (string) Router::getInstance('site')->build($route);
        }

        // Workaround for bug in Joomla 3.7
        if (str_starts_with($site, $base = Uri::root(true) . '/administrator')) {
            $site = Uri::root(true) . substr($site, strlen($base));
        }

        return $site;
    }

    protected static function getTemplate()
    {
        $db = Factory::getDbo();
        $db->setQuery(
            'SELECT id, params from #__template_styles WHERE client_id = 0 ORDER BY home DESC'
        );

        foreach ($db->loadObjectList() as $templ) {
            $params = new Registry($templ->params);

            if ($params->get('yootheme')) {
                return $templ;
            }
        }
    }
}
