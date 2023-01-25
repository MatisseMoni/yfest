<?php

namespace YOOtheme\Theme;

use YOOtheme\Config;
use YOOtheme\ImageProvider;
use YOOtheme\Url;
use YOOtheme\View;

class ViewHelper
{
    // https://developer.mozilla.org/en-US/docs/Web/Media/Formats/Image_types
    public const REGEX_IMAGE = '#\.(avif|gif|a?png|jpe?g|svg|webp)($|\#.*)#i';

    public const REGEX_VIDEO = '#\.(mp4|m4v|ogv|webm)$#i';

    public const REGEX_VIMEO = '#(?:player\.)?vimeo\.com(?:/video)?/(\d+)#i';

    public const REGEX_YOUTUBE = '#(?:youtube(-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})#i';

    public const REGEX_UNSPLASH = '#images.unsplash.com/(?<id>(?:[\w-]+/)?[\w\-.]+)#i';

    /**
     * @var View
     */
    protected $view;

    /**
     * @var ImageProvider
     */
    protected $image;

    /**
     * @var Config
     */
    protected $config;

    /**
     * Constructor.
     *
     * @param Config        $config
     * @param View          $view
     * @param ImageProvider $image
     */
    public function __construct(Config $config, View $view, ImageProvider $image)
    {
        $this->view = $view;
        $this->image = $image;
        $this->config = $config;
    }

    /**
     * Register helper.
     *
     * @param View $view
     */
    public function register($view)
    {
        // Loaders
        $view->addLoader(function ($name, $parameters, $next) {
            $content = $next($name, $parameters);

            // Apply to root template view only
            if (empty($parameters['_root'])) {
                return $content;
            }

            return $this->image->replace($content);
        });

        // Functions
        $view->addFunction('social', [$this, 'social']);
        $view->addFunction('uid', [$this, 'uid']);
        $view->addFunction('iframeVideo', [$this, 'iframeVideo']);
        $view->addFunction('isVideo', [$this, 'isVideo']);
        $view->addFunction('isImage', [$this, 'isImage']);
        $view->addFunction('image', [$this, 'image']);
        $view->addFunction('bgImage', [$this, 'bgImage']);
        $view->addFunction('parallaxOptions', [$this, 'parallaxOptions']);
        $view->addFunction('striptags', [$this, 'striptags']);
        $view->addFunction('margin', [$this, 'margin']);

        // Components
        $view['html']->addComponent('image', [$this, 'comImage']);
    }

    public function social($link)
    {
        $link = strval($link);

        if (str_starts_with($link, 'mailto:')) {
            return 'mail';
        }

        if (str_starts_with($link, 'tel:')) {
            return 'receiver';
        }

        if (preg_match('#(google|goo)\.(.+?)/maps(?>/?.+)?#i', $link)) {
            return 'location';
        }

        $link = parse_url($link, PHP_URL_HOST);
        $link = str_replace(['wa.me'], ['whatsapp'], $link);
        $link = explode('.', $link);

        $icons = $this->config->get('theme.social_icons');
        $social = 'social';

        foreach ($icons as $icon) {
            if (in_array($icon, $link)) {
                $social = $icon;
                break;
            }
        }

        return $social;
    }

    /**
     * @param string $link
     * @param array  $params
     * @param bool   $defaults
     *
     * @return false|string
     */
    public function iframeVideo($link, $params = [], $defaults = true)
    {
        $link = strval($link);
        $query = parse_url($link, PHP_URL_QUERY);

        if ($query) {
            parse_str($query, $_params);
            $params = array_merge($_params, $params);
        }

        if (preg_match(static::REGEX_VIMEO, $link, $matches)) {
            return Url::to(
                "https://player.vimeo.com/video/{$matches[1]}",
                $defaults
                    ? array_merge(
                        [
                            'loop' => 1,
                            'autoplay' => 1,
                            'title' => 0,
                            'byline' => 0,
                            'setVolume' => 0,
                        ],
                        $params
                    )
                    : $params
            );
        }

        if (preg_match(static::REGEX_YOUTUBE, $link, $matches)) {
            if (!empty($params['loop'])) {
                $params['playlist'] = $matches[2];
            }

            if (empty($params['controls'])) {
                $params['disablekb'] = 1;
            }

            return Url::to(
                "https://www.youtube{$matches[1]}.com/embed/{$matches[2]}",
                $defaults
                    ? array_merge(
                        [
                            'rel' => 0,
                            'loop' => 1,
                            'playlist' => $matches[2],
                            'autoplay' => 1,
                            'controls' => 0,
                            'showinfo' => 0,
                            'iv_load_policy' => 3,
                            'modestbranding' => 1,
                            'wmode' => 'transparent',
                            'playsinline' => 1,
                        ],
                        $params
                    )
                    : $params
            );
        }

        return false;
    }

    public function uid()
    {
        return substr(uniqid(), -4);
    }

    public function isVideo($link)
    {
        return $link && preg_match(static::REGEX_VIDEO, $link, $matches) ? $matches[1] : false;
    }

    /**
     * @param string|array $url
     * @param array        $attrs
     *
     * @return string
     */
    public function image($url, array $attrs = [])
    {
        $url = (array) $url;
        $path = array_shift($url);
        $isAbsolute = $this->isAbsolute($path);
        $type = $this->isImage($path);

        if (!empty($url['thumbnail']) && $isAbsolute) {
            if (is_array($url['thumbnail'])) {
                $attrs['width'] = is_numeric($url['thumbnail'][0]) ? $url['thumbnail'][0] : null;
                $attrs['height'] = is_numeric($url['thumbnail'][1]) ? $url['thumbnail'][1] : null;
            }

            if ($attrs['width'] && $attrs['height']) {
                // use unsplash resizing?
                if (preg_match(static::REGEX_UNSPLASH, $path, $matches)) {
                    $path = "https://images.unsplash.com/{$matches['id']}?fit=crop&w={$attrs['width']}&h={$attrs['height']}";
                } else {
                    $this->addAttr(
                        $attrs,
                        'style',
                        "aspect-ratio: {$attrs['width']} / {$attrs['height']}"
                    );
                    $this->addAttr($attrs, 'class', 'uk-object-cover');
                }
            }
        }

        $attrs['src'] =
            !$isAbsolute && !in_array($type, ['gif', 'svg']) && !empty($url)
                ? parse_url($path, PHP_URL_PATH) .
                    '#' .
                    http_build_query(
                        array_map(function ($value) {
                            return is_array($value) ? implode(',', $value) : $value;
                        }, $url),
                        '',
                        '&'
                    )
                : $path;

        if (empty($attrs['alt'])) {
            $attrs['alt'] = true;
        }

        if ($type === 'svg' && (empty($attrs['width']) || empty($attrs['height']))) {
            [$attrs['width'], $attrs['height']] = SvgHelper::getDimensions($path, $attrs);
        }

        // Deprecated YOOtheme Pro < v2.8.0
        if (!empty($attrs['uk-img'])) {
            unset($attrs['uk-img']);
        }

        $attrs['loading'] = $attrs['loading'] ?? 'lazy' ?: 'eager';

        return "<img{$this->view->attrs($attrs)}>";
    }

    /**
     * @param string $url
     * @param array  $params
     *
     * @return array
     */
    public function bgImage($url, array $params = [])
    {
        $attrs = [];
        $isResized = $params['width'] || $params['height'];
        $type = $this->isImage($url);

        if (preg_match(static::REGEX_UNSPLASH, $url, $matches)) {
            $url = "https://images.unsplash.com/{$matches['id']}?fit=crop&w={$params['width']}&h={$params['height']}";
        } elseif ($type == 'svg' || $this->isAbsolute($url)) {
            if ($isResized && !$params['size']) {
                $width = $params['width'] ? "{$params['width']}px" : 'auto';
                $height = $params['height'] ? "{$params['height']}px" : 'auto';
                $attrs['style'][] = "background-size: {$width} {$height};";
            }
        } elseif ($type != 'gif') {
            $url = parse_url($url, PHP_URL_PATH) . '#srcset=1';
            $url .= '&covers=' . ((int) ($params['size'] === 'cover'));
            $url .= '&thumbnail' . ($isResized ? "={$params['width']},{$params['height']}" : '');
        }

        if ($image = $this->image->create($url, false)) {
            $minWidth = 0;
            if (empty($params['size'])) {
                $img = $image->apply($image->getAttribute('params'));
                $minWidth = $img->getWidth();
                $attrs['style'][] = "background-size: {$img->getWidth()}px {$img->getHeight()}px;";
            }

            $sources = $this->image->getSources($image, $minWidth);
            $srcsetAttrs = $this->image->getSrcsetAttrs($image, 'data-', $minWidth);

            if ($sources) {
                $srcsetAttrs = array_slice($srcsetAttrs, 0, 1);
            }

            $attrs = array_merge($attrs, $srcsetAttrs, [
                'data-sources' => json_encode($sources),
            ]);
        } else {
            $attrs['data-src'][] = Url::to($url);
        }

        // use eager loading?
        if (isset($params['loading'])) {
            $attrs['loading'] = $params['loading'];
        }

        $attrs['uk-img'] = true;

        $attrs['class'] = [
            $this->view->cls(
                [
                    'uk-background-norepeat',
                    'uk-background-{size}',
                    'uk-background-{position}',
                    'uk-background-image@{visibility}',
                    'uk-background-blend-{blend_mode}',
                    'uk-background-fixed{@effect: fixed}',
                ],
                $params
            ),
        ];

        $attrs['style'][] = $params['background']
            ? "background-color: {$params['background']};"
            : '';

        switch ($params['effect']) {
            case '':
            case 'fixed':
                break;
            case 'parallax':
                $options = $this->parallaxOptions($params, '', ['bgx', 'bgy']);

                if ($options) {
                    $options[] =
                        'easing: ' .
                        (is_numeric($params['parallax_easing']) ? $params['parallax_easing'] : '0');
                    $options[] = $params['parallax_breakpoint']
                        ? "media: @{$params['parallax_breakpoint']}"
                        : '';
                    $options[] = !empty($params['parallax_target'])
                        ? "target: {$params['parallax_target']}"
                        : '';
                    $attrs['uk-parallax'] = implode('; ', array_filter($options));
                }

                break;
        }

        return $attrs;
    }

    public function comImage($element, array $params = [])
    {
        $defaults = ['src' => '', 'width' => '', 'height' => ''];
        $attrs = array_merge($defaults, $element->attrs);
        $type = $this->isImage($attrs['src']);
        $isAbsolute = $this->isAbsolute($attrs['src']);

        if (empty($attrs['alt'])) {
            $attrs['alt'] = true;
        }

        if ($type !== 'svg') {
            if (!empty($attrs['thumbnail'])) {
                $thumbnail = is_array($attrs['thumbnail'])
                    ? $attrs['thumbnail']
                    : [$attrs['width'], $attrs['height']];

                if ($isAbsolute) {
                    $width = $thumbnail[0];
                    $height = $thumbnail[1];

                    if ($width && $height) {
                        // use unsplash resizing?
                        if (preg_match(static::REGEX_UNSPLASH, $attrs['src'], $matches)) {
                            $attrs[
                                'src'
                            ] = "https://images.unsplash.com/{$matches['id']}?fit=crop&w={$width}&h={$height}";
                        } else {
                            $this->addAttr($attrs, 'style', "aspect-ratio: {$width} / {$height}");
                            $this->addAttr($attrs, 'class', 'uk-object-cover');
                        }
                    }
                } else {
                    $query['thumbnail'] = $thumbnail;
                    $query['srcset'] = true;
                    $attrs['width'] = $attrs['height'] = null;
                }
            }

            if (!empty($attrs['uk-cover'])) {
                $query['covers'] = true;
            }

            if ($type !== 'gif' && !$isAbsolute && $type && !empty($query)) {
                $attrs['src'] =
                    parse_url($attrs['src'], PHP_URL_PATH) .
                    '#' .
                    http_build_query(
                        array_map(function ($value) {
                            return is_array($value) ? join(',', $value) : $value;
                        }, $query),
                        '',
                        '&'
                    );
            }

            unset($attrs['uk-svg']);
        } elseif (empty($attrs['width']) || empty($attrs['height'])) {
            [$attrs['width'], $attrs['height']] = SvgHelper::getDimensions($attrs['src'], $attrs);
        }

        // use lazy loading?
        $attrs['loading'] = $attrs['loading'] ?? 'lazy' ?: 'eager';

        unset($attrs['thumbnail']);

        // update element
        $element->name = 'img';
        $element->attrs = $attrs;
    }

    public function isImage($link)
    {
        return $link && preg_match(static::REGEX_IMAGE, $link, $matches) ? $matches[1] : false;
    }

    public function isAbsolute($url)
    {
        return $url && preg_match('/^(\/|#|[a-z0-9-.]+:)/', $url);
    }

    public function parallaxOptions(
        $params,
        $prefix = '',
        $props = ['x', 'y', 'scale', 'rotate', 'opacity']
    ) {
        return array_reduce(
            $props,
            function ($options, $prop) use ($params, $prefix) {
                $key = "{$prefix}parallax_{$prop}";
                if (!isset($params[$key]) || $params[$key] == '') {
                    return $options;
                }

                $value = implode(
                    ',',
                    array_filter(
                        array_map(function ($stop) {
                            [$value, $position] = explode(' ', $stop) + ['', ''];
                            return $value != ''
                                ? $value . ($position ? " {$position}" : '')
                                : false;
                        }, explode(',', $params[$key])),
                        function ($value) {
                            return $value !== false;
                        }
                    )
                );
                $options[] = "{$prop}: {$value}";

                return $options;
            },
            []
        );
    }

    public function striptags(
        $str,
        $allowable_tags = '<div><h1><h2><h3><h4><h5><h6><p><ul><ol><li><img><svg><br><span><strong><em><sup><del>'
    ) {
        return strip_tags(strval($str), $allowable_tags);
    }

    /**
     * @param string $margin
     *
     * @return string|void
     */
    public function margin($margin)
    {
        switch ($margin) {
            case '':
                return;
            case 'default':
                return 'uk-margin-top';
            default:
                return "uk-margin-{$margin}-top";
        }
    }

    protected function addAttr(&$attrs, $name, $value)
    {
        if (empty($attrs[$name])) {
            $attrs[$name] = [];
        } elseif (is_string($attrs[$name])) {
            $attrs[$name] = [$attrs[$name]];
        }
        $attrs[$name][] = $value;
    }
}
