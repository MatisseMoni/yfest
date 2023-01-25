<?php

namespace YOOtheme;

class ImageProvider
{
    public const IMAGE = '/<(?:div|img)\s+[^>]*?((\w+-)?src=((["\'])[^\'"]+?\.(?:gif|png|jpe?g|webp|avif)#[^\'"]+?\4))[^>]*>/i';

    /**
     * @var string
     */
    public $cache;

    /**
     * @var string
     */
    public $route;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var array
     */
    protected $loaders = [];

    /**
     * Constructor.
     *
     * @param string $cache
     * @param array  $config
     */
    public function __construct($cache, array $config = [])
    {
        $this->cache = Path::resolve($cache);
        $this->route = $config['route'] ?? '@image';
        $this->secret = $config['secret'] ?? filemtime(__FILE__);
        $this->params = $config['params'] ?? [];
    }

    /**
     * Adds a loader callback.
     *
     * @param callable $loader
     *
     * @return static
     */
    public function addLoader(callable $loader)
    {
        $this->loaders[] = $loader;

        return $this;
    }

    /**
     * Creates an Image object from src attribute's relative URL.
     *
     * @param string $src
     * @param bool   $resource
     *
     * @return Image|void
     */
    public function create($src, $resource = true)
    {
        [$file, $params] = $this->parse($src);

        if (Path::isAbsolute($file) || str_ends_with(strtolower($file), '.svg')) {
            return;
        }

        $image = new Image($file, $resource);
        $image->setAttribute('src', $src);

        if ($image->getType()) {
            $image = $image->setAttribute('params', $params);

            foreach ($this->loaders as $loader) {
                $image = $loader($image, $this) ?: $image;
            }

            return $image;
        }
    }

    /**
     * Parse image url.
     *
     * @param string $url
     *
     * @return array
     */
    public function parse($url)
    {
        $url = urldecode($url);
        $file = parse_url($url, PHP_URL_PATH);
        $data = parse_url($url, PHP_URL_FRAGMENT) ?: '';

        // replace params
        if ($this->params) {
            $data = strtr($data, $this->params);
        }

        // parse params
        parse_str($data, $params);

        return [$file, $params];
    }

    /**
     * Replace images in HTML.
     *
     * @param string $text
     *
     * @return string
     */
    public function replace($text)
    {
        if (stripos($text, '<div') !== false || stripos($text, '<img') !== false) {
            return preg_replace_callback(static::IMAGE, [$this, 'replaceCallback'], $text);
        }

        return $text;
    }

    /**
     * Replace image callback.
     *
     * @param array $matches
     *
     * @return string
     */
    public function replaceCallback($matches)
    {
        [$element, $src, $prefix, $source] = $matches;

        $url = html_entity_decode(trim($source, "\"'"));

        if ($image = $this->create($url, false)) {
            // load sources
            $sources = $this->getSources($image);

            // apply params
            $attrs = $this->getSrcsetAttrs($image, $prefix);
            $image = $image->apply($image->getAttribute('params'));

            // skip srcset and sizes attributes if additional sources are found
            if ($sources) {
                $attrs = array_slice($attrs, 0, 1);
            }

            // add sources to attrs for none image tags
            if ($isImage = str_starts_with($element, '<img')) {
                $sources = static::getSourceElements($sources, $prefix);

                // set width
                if (!str_contains($element, 'width=')) {
                    $attrs['width'] = $image->width;
                }

                // set height
                if (!str_contains($element, 'height=')) {
                    $attrs['height'] = $image->height;
                }
            } else {
                $attrs["{$prefix}sources"] = json_encode($sources);
            }

            // add image
            $image = str_replace($src, static::getAttrs($attrs), $element);

            // use picture?
            if ($sources && $isImage) {
                return join("\n", array_merge(['<picture>'], $sources, [$image, '</picture>']));
            }

            return $image;
        }

        return $element;
    }

    /**
     * Gets the image hash.
     *
     * @param string $data
     *
     * @return string
     */
    public function getHash($data)
    {
        return hash('fnv132', hash_hmac('sha1', $data, $this->secret));
    }

    /**
     * Gets the image URL.
     *
     * @param string|Image $image
     *
     * @return string|null
     */
    public function getUrl($image)
    {
        $cached = null;

        if (is_string($image)) {
            if (!($image = $this->create($src = $image, false))) {
                return Url::to($src);
            }

            $image = $image->apply($image->getAttribute('params'));
        }

        if ($hash = $image->getHash()) {
            $cached = $image->getFilename(Path::join($this->cache, substr($hash, 0, 2)));
        }

        // url to source
        if (is_null($cached)) {
            return Url::to($image->getFile());
        }

        // url to cached image
        if (is_file($cached)) {
            return Url::to($cached);
        }

        return Url::route($this->route, [
            'src' => ($src = strval($image)),
            'hash' => $this->getHash($src),
        ]);
    }

    /**
     * Gets the image sources (webp, ...).
     *
     * @param Image  $image
     * @param int    $minWidth
     *
     * @return string[][]
     */
    public function getSources(Image $image, $minWidth = 0)
    {
        $sources = [];

        foreach ($image->getAttribute('types', []) as $mime => $type) {
            $image = $image->apply(['type' => $type]);
            $attrs = array_slice($this->getSrcsetAttrs($image, '', $minWidth), 1);
            $sources[] = ['type' => $mime] + $attrs;
        }

        return $sources;
    }

    /**
     * Gets the image source set.
     *
     * @param Image $image
     *
     * @return array
     */
    public function getSrcset(Image $image)
    {
        $params = $image->getAttribute('params');

        if (!isset($params['srcset'])) {
            return [];
        }

        $imageDst = $image->apply($params);
        $maxWidth = min(max($image->width, $imageDst->width), $imageDst->width * 2);
        $maxHeight = min(max($image->height, $imageDst->height), $imageDst->height * 2);

        if (!$maxWidth || !$maxHeight) {
            return [];
        }

        foreach (explode(',', $params['srcset']) as $value) {
            $resized = $imageDst->resize($value);

            // if oversized, use original image sizes
            if (1 < ($scale = max($resized->width / $maxWidth, $resized->height / $maxHeight))) {
                $sizes = [round($resized->width / $scale), round($resized->height / $scale)];
            } else {
                $sizes = [$resized->width, $resized->height];
            }

            // set image parameters
            $parameters = array_map(function ($val) use ($sizes) {
                return join(',', $sizes + explode(',', $val));
            }, static::filterParams($params));

            $resized = $image->apply($parameters);
            $images[$resized->width] = $resized;
        }

        $images[$imageDst->width] = $imageDst;

        ksort($images);

        return $images;
    }

    public function getSrcsetAttrs(Image $image, $prefix = '', $minWidth = 0)
    {
        $images = $this->getSrcset($image);
        $params = $image->getAttribute('params');
        $image = $image->apply($params);
        $attrs = ["{$prefix}src" => $this->getUrl($image)];

        foreach ($images as $img) {
            if ($minWidth && $img->width < $minWidth) {
                continue;
            }
            $srcset[] = "{$this->getUrl($img)} {$img->width}w";
        }

        if (isset($srcset)) {
            // merge default sizes
            $params = array_merge(
                [
                    'sizes' => "(min-width: {$image->width}px) {$image->width}px",
                ],
                $params
            );

            // set image srcset/sizes
            $attrs["{$prefix}srcset"] = join(', ', $srcset);
            $attrs["{$prefix}sizes"] = $params['sizes'];
        }

        return $attrs;
    }

    /**
     * Gets the image info.
     *
     * @param string $file
     *
     * @return array|void
     */
    public static function getInfo($file)
    {
        static $cache = [];

        if (isset($cache[$file])) {
            return $cache[$file];
        }

        if ($data = @getimagesize($file, $info)) {
            return $cache[$file] = [$data[0], $data[1], substr($data['mime'], 6), $info];
        }
    }

    /**
     * Gets the image attributes.
     *
     * @param array $attributes
     *
     * @return string
     */
    public static function getAttrs(array $attributes)
    {
        $attrs = [];

        foreach ($attributes as $key => $value) {
            $attrs[] = sprintf('%1$s="%2$s"', $key, htmlspecialchars($value));
        }

        return join(' ', $attrs);
    }

    /**
     * @param array $sources
     * @param string $prefix
     *
     * @return array
     */
    protected static function getSourceElements($sources, $prefix)
    {
        $elements = [];

        foreach ($sources as $source) {
            if ($prefix) {
                $source["{$prefix}srcset"] = $source['srcset'];
                unset($source['srcset']);
            }

            $elements[] = '<source ' . static::getAttrs($source) . '>';
        }

        return $elements;
    }

    /**
     * Filter image parameters.
     */
    protected static function filterParams($params)
    {
        return array_intersect_key($params, array_flip(['crop', 'resize', 'thumbnail']));
    }
}
