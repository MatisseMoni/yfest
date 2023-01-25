<?php

namespace YOOtheme;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Pagination\PaginationObject;
use YOOtheme\Builder\Joomla\Source\ArticleHelper;

return [
    'transforms' => [
        'render' => function ($node, $params) {
            // Single Article
            if (!isset($params['pagination'])) {
                $article = $params['item'] ?? ($params['article'] ?? false);

                if (!$article || !ArticleHelper::applyPageNavigation($article)) {
                    return false;
                }

                $params['pagination'] = [
                    'previous' => $article->prev
                        ? new PaginationObject($article->prev_label, '', null, $article->prev)
                        : null,
                    'next' => $article->next
                        ? new PaginationObject($article->next_label, '', null, $article->next)
                        : null,
                ];
            }

            if (is_callable($params['pagination'])) {
                $params['pagination'] = $params['pagination']();
            }

            if (is_array($params['pagination'])) {
                $node->props['pagination_type'] = 'previous/next';
                $node->props['pagination'] = $params['pagination'];
                return;
            }

            // Article Index
            if (empty($params['pagination']) || $params['pagination']->pagesTotal < 2) {
                return false;
            }

            $list = $params['pagination']->getPaginationPages();

            $total = $params['pagination']->pagesTotal;
            $current = (int) $params['pagination']->pagesCurrent;
            $endSize = 1;
            $midSize = 3;
            $dots = false;

            $pagination = [];

            if ($list['previous']['active']) {
                $pagination['previous'] = $list['previous']['data'];
            }

            $list['start']['data']->text = 1;
            $list['end']['data']->text = $total;

            for ($n = 1; $n <= $total; $n++) {
                $active =
                    $n <= $endSize ||
                    ($current && $n >= $current - $midSize && $n <= $current + $midSize) ||
                    $n > $total - $endSize;

                if ($active || $dots) {
                    if ($active) {
                        $pagination[$n] =
                            $n === 1
                                ? $list['start']['data']
                                : ($n === $total
                                    ? $list['end']['data']
                                    : $list['pages'][$n]['data']);

                        $pagination[$n]->active = $n === $current;
                    } else {
                        $pagination[$n] = new PaginationObject(Text::_('&hellip;'));
                    }

                    $dots = $active;
                }
            }

            if ($list['next']['active']) {
                $pagination['next'] = $list['next']['data'];
            }

            $node->props['pagination'] = $pagination;
        },
    ],
];
