<?php

namespace YOOtheme;

use Joomla\CMS\Component\ComponentHelper;

return [
    '2.7.0-beta.0.1' => function ($config) {
        $contentParams = ComponentHelper::getParams('com_content');

        if (!Arr::has($config, 'blog.image_align')) {
            Arr::set(
                $config,
                'blog.image_align',
                $contentParams->get('float_intro') === 'none' ? 'top' : 'between'
            );
        }

        if (!Arr::has($config, 'post.image_align')) {
            Arr::set(
                $config,
                'post.image_align',
                $contentParams->get('float_fulltext') === 'none' ? 'top' : 'between'
            );
        }

        return $config;
    },
];
