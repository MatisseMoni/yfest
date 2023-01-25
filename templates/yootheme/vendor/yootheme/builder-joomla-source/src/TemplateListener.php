<?php

namespace YOOtheme\Builder\Joomla\Source;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Document\Document;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Content\Site\Helper\RouteHelper;
use YOOtheme\Application;
use YOOtheme\Builder;
use YOOtheme\Builder\Templates\TemplateHelper;
use YOOtheme\Config;
use YOOtheme\Event;
use YOOtheme\Theme\Joomla\ThemeLoader;

class TemplateListener
{
    public static function loadTemplate(
        TemplateHelper $helper,
        Builder $builder,
        Config $config,
        $event
    ) {
        [$view, $tpl] = $event->getArguments();

        $template = Event::emit('builder.template', $view, $tpl);

        if (empty($template['type'])) {
            return;
        }

        if ($config('app.isCustomizer')) {
            $config->set('customizer.view', $template['type']);
        }

        if ($config('app.isBuilder')) {
            return;
        }

        if ($matched = $helper->match($template)) {
            $template += $matched + ['layout' => [], 'params' => []];

            // set template identifier
            if ($config('app.isCustomizer')) {
                $config->set('customizer.template', $template['id']);
            }

            // get template from request?
            if (($templ = $config('req.customizer.template')) && $templ['id'] == $template['id']) {
                $template['layout'] = $templ['layout'];
            }

            // get output from builder
            $output = $builder->render(
                json_encode($template['layout']),
                $template['params'] + [
                    'prefix' => "template-{$template['id']}",
                ]
            );

            // append frontend edit button?
            if ($output && isset($template['editUrl']) && !$config('app.isCustomizer')) {
                $output .=
                    "<a style=\"position: fixed!important\" class=\"uk-position-medium uk-position-bottom-right uk-position-z-index uk-button uk-button-primary\" href=\"{$template['editUrl']}\">" .
                    Text::_('JACTION_EDIT') .
                    '</a>';
            }

            if ($output) {
                $view->set('_output', $output);
                $config->set('app.isBuilder', true);
            }
        }
    }

    public static function matchTemplate(Document $document, $view, $tpl)
    {
        if ($tpl) {
            return;
        }

        $layout = $view->getLayout();
        $context = $view->get('context');

        if ($context === 'com_content.article' && $layout === 'default') {
            $item = $view->get('item');

            return [
                'type' => $context,
                'query' => [
                    'catid' => $item->catid,
                    'tag' => array_column($item->tags->itemTags, 'id'),
                    'lang' => $document->language,
                ],
                'params' => ['item' => $item],
                'editUrl' => $item->params->get('access-edit')
                    ? Route::_(
                        RouteHelper::getFormRoute($item->id) .
                            '&return=' .
                            base64_encode(Uri::getInstance())
                    )
                    : null,
            ];
        }

        if ($context === 'com_content.category' && $layout === 'blog') {
            $category = $view->get('category');
            $pagination = $view->get('pagination');

            return [
                'type' => $context,
                'query' => [
                    'catid' => $category->id,
                    'tag' => array_column($category->tags->itemTags, 'id'),
                    'pages' => $pagination->pagesCurrent === 1 ? 'first' : 'except_first',
                    'lang' => $document->language,
                ],
                'params' => [
                    'category' => $category,
                    'items' => array_merge($view->get('lead_items'), $view->get('intro_items')),
                    'pagination' => $pagination,
                ],
            ];
        }

        if ($context === 'com_content.featured') {
            $pagination = $view->get('pagination');

            return [
                'type' => $context,
                'query' => [
                    'pages' => $pagination->pagesCurrent === 1 ? 'first' : 'except_first',
                    'lang' => $document->language,
                ],
                'params' => ['items' => $view->get('items'), 'pagination' => $pagination],
            ];
        }

        if ($context === 'com_tags.tag') {
            $pagination = $view->get('pagination');

            return [
                'type' => $context,
                'query' => [
                    'pages' => $pagination->pagesCurrent === 1 ? 'first' : 'except_first',
                    'lang' => $document->language,
                ],
                'params' => [
                    'tags' => $view->get('item'),
                    'items' => $view->get('items'),
                    'pagination' => $pagination,
                ],
            ];
        }

        if ($context === 'com_tags.tags') {
            return [
                'type' => $context,
                'query' => ['lang' => $document->language],
                'params' => ['tags' => $view->get('items')],
            ];
        }

        if ($context === 'com_contact.contact') {
            $item = $view->get('item');
            return [
                'type' => $context,
                'query' => [
                    'catid' => $item->catid,
                    'tag' => array_column($item->tags->itemTags, 'id'),
                    'lang' => $document->language,
                ],
                'params' => ['item' => $item],
            ];
        }

        if ($context === 'com_search.search') {
            $pagination = $view->get('pagination');

            return [
                'type' => $context,
                'query' => [
                    'pages' => $pagination->pagesCurrent === 1 ? 'first' : 'except_first',
                    'lang' => $document->language,
                ],
                'params' => [
                    'search' => [
                        'searchword' => $view->searchword,
                        'total' => $view->total,
                        'error' => $view->error ?: null,
                    ],
                    'items' => $view->get('results'),
                    'pagination' => $pagination,
                ],
            ];
        }

        if ($context === 'com_finder.search') {
            $pagination = $view->get('pagination');
            $query = $view->get('query');

            return [
                'type' => $context,
                'query' => [
                    'pages' => $pagination->pagesCurrent === 1 ? 'first' : 'except_first',
                    'lang' => $document->language,
                ],
                'params' => [
                    'search' => [
                        'searchword' => $query->input ?: '',
                        'total' => $pagination->total,
                    ],
                    'items' => $view->get('results'),
                    'pagination' => $pagination,
                ],
            ];
        }

        if ($view->getName() === '404') {
            return [
                'type' => 'error-404',
                'query' => ['lang' => $document->language],
            ];
        }
    }

    public static function load404(Application $app, CMSApplication $cms, Config $config, $event)
    {
        [$result] = $event->getArguments();

        if (!$config('theme.template')) {
            ThemeLoader::initTheme($app, $config);
        }

        $v = new HtmlView(['name' => '404', 'base_path' => '', 'template_path' => '']);
        $cms->triggerEvent('onLoadTemplate', [$v, null]);

        $result['customizer'] = sprintf(
            '<script>var $customizer = JSON.parse(atob("%s"));</script>',
            base64_encode(json_encode($config('customizer')))
        );

        if (!empty($v->get('_output'))) {
            $result['404'] = $v->get('_output');
        }
    }
}
