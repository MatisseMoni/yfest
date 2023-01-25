<?php

namespace YOOtheme\Builder\Joomla;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\TagsHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\User\User;
use Joomla\Component\Content\Administrator\Model\ArticleModel;
use Joomla\Component\Content\Site\Helper\RouteHelper;
use YOOtheme\Builder;
use YOOtheme\Builder\Joomla\Fields\FieldsHelper;
use YOOtheme\Config;
use YOOtheme\Http\Request;
use YOOtheme\Http\Response;

class ContentListener
{
    public const PATTERN = '/^<!-- (\{.*\}) -->/';

    /**
     * @var User|null
     */
    protected $user;

    public function afterRoute(Config $config, User $user, Session $session)
    {
        if (
            static::isArticleView() &&
            $config('req.customizer.admin') &&
            ($user_id = $config('req.customizer.user_id'))
        ) {
            $this->user = $user;
            $session->set('user', Factory::getUser($user_id));
        }
    }

    public function prepareContent(
        Config $config,
        CMSApplication $application,
        Builder $builder,
        $event
    ) {
        [$context, $article, $params] = $event->getArguments();

        static $first = true;

        if (!$first || !static::isArticleView() || $context !== 'com_content.article') {
            return;
        }

        // Make sure this is executed only once
        $first = false;

        $content = preg_match(self::PATTERN, $article->fulltext, $matches) ? $matches[1] : null;

        if ($params->get('access-edit') && $config('app.isCustomizer')) {
            if ($page = $config('req.customizer.page')) {
                if ($article->id === $page['id']) {
                    $content = !empty($page['content']) ? json_encode($page['content']) : null;
                } else {
                    unset($page);
                }
            }

            $modified = !empty($page);

            $config->add('customizer.page', [
                'id' => $article->id,
                'title' => $article->title,
                'content' => $content ? $builder->load($content) : $content,
                'modified' => $modified,
                'collision' => self::getCollisionInfo($article),
            ]);
        }

        if ($content) {
            /**
             * Redirect to login page if access-view is false.
             */
            if (!$params->get('access-view')) {
                $return = base64_encode(Uri::getInstance());
                $application->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'notice');
                $application->redirect(
                    Route::_("index.php?option=com_users&view=login&return={$return}"),
                    403
                );
            }

            // Render builder output
            $article->text = $builder->render($content, [
                'prefix' => 'page',
                'article' => $article,
            ]);

            // Used to determine active builder layout in html/helpers.php
            $config->set('app.isBuilder', true);
        }
    }

    public function loadTemplate(Config $config, Session $session, $event)
    {
        if ($this->user) {
            $session->set('user', $this->user);
        }

        if (!$config('app.isBuilder')) {
            return;
        }

        [$view] = $event->getArguments();

        $layout = $view->getLayout();
        $context = $view->get('context');

        if ($context !== 'com_content.article' || $layout !== 'default') {
            return;
        }

        $article = $view->get('item');

        $content = $article->text;

        if ($article->params->get('access-edit') && !$config('app.isCustomizer')) {
            $url = Route::_(
                RouteHelper::getFormRoute($article->id) .
                    '&return=' .
                    base64_encode(Uri::getInstance())
            );
            $content .=
                "<a style=\"position: fixed!important\" class=\"uk-position-medium uk-position-bottom-right uk-position-z-index uk-button uk-button-primary\" href=\"{$url}\">" .
                Text::_('JACTION_EDIT') .
                '</a>';
        }

        $view->set('_output', $content);
    }

    public function savePage(Request $request, Response $response, Builder $builder)
    {
        $request
            ->abortIf(!($page = $request->getParam('page')), 400)
            ->abortIf(!($page = base64_decode($page)), 400)
            ->abortIf(!($page = json_decode($page)), 400);

        BaseDatabaseModel::addIncludePath(
            JPATH_ADMINISTRATOR . '/components/com_content/models',
            'ContentModel'
        );

        /** @var ArticleModel $model */
        $model = BaseDatabaseModel::getInstance('article', 'ContentModel', [
            'ignore_request' => true,
        ]);
        $article = $model->getItem($page->id);

        $data = [
            'id' => $page->id,
            'catid' => $article->catid, // notice if missing
            'fulltext' => '',
            'introtext' => '',
        ];

        if ((array) $page->content) {
            $content = json_encode($page->content);
            $fulltext = json_encode($builder->withParams(['context' => 'save'])->load($content));
            $introtext = $builder
                ->withParams(['context' => 'content'])
                ->render($content, ['prefix' => 'page']);

            $data['fulltext'] = "<!-- {$fulltext} -->";
            $data['introtext'] = $introtext;
        }

        // JPATH_COMPONENT constant isn't set yet
        if (!defined('JPATH_COMPONENT')) {
            define('JPATH_COMPONENT', JPATH_BASE . '/components/com_ajax');
        }

        $request->abortIf(!$this->allowEdit($article), 403, 'Insufficient User Rights.');

        $collision = self::getCollisionInfo($article);

        if (
            !$request->getParam('overwrite') &&
            $collision['contentHash'] !== $page->collision->contentHash
        ) {
            return $response->withJson(['hasCollision' => true, 'collision' => $collision]);
        }

        if ($tags = (new TagsHelper())->getTagIds($page->id, 'com_content.article')) {
            $data['tags'] = explode(',', $tags);
        }

        foreach (FieldsHelper::getFields('com_content.article', $article) as $field) {
            $data['com_fields'][$field->name] = $field->rawvalue;
        }

        $model->save($data);

        // reload article after save
        $article = $model->getItem($page->id);

        return $response->withJson([
            'id' => $page->id,
            'collision' => self::getCollisionInfo($article),
        ]);
    }

    protected static function getCollisionInfo($article)
    {
        $user = Factory::getUser($article->modified_by);
        $modifiedBy = $user->username ?: '';

        return [
            'contentHash' => md5($article->fulltext . $article->introtext),
            'modifiedBy' => $modifiedBy,
        ];
    }

    protected function allowEdit($article)
    {
        $user = Factory::getUser();
        $asset = "com_content.article.{$article->id}";

        return $user->authorise('core.edit', $asset) ||
            ($user->authorise('core.edit.own', $asset) && $user->get('id') == $article->created_by);
    }

    protected function isArticleView()
    {
        $input = Factory::getApplication()->input;
        return $input->getCmd('option') === 'com_content' &&
            $input->getCmd('view') === 'article' &&
            $input->getCmd('task', '') === '';
    }
}
