<?php

namespace YOOtheme\Theme;

use YOOtheme\Config;
use YOOtheme\Image;

class ImageLoader
{
    /**
     * @var array
     */
    protected $convert = [];

    /**
     * Constructor.
     */
    public function __construct(Config $config)
    {
        // supports image avif?
        if ($config('~theme.avif') && is_callable('imageavif') && PHP_VERSION_ID >= 80100) {
            $this->convert['png']['image/avif'] = 'avif,85';
            $this->convert['jpeg']['image/avif'] = 'avif,75';
        }

        // supports image webp?
        if ($config('~theme.webp') && is_callable('imagewebp')) {
            $this->convert['png']['image/webp'] = 'webp,100';
            $this->convert['jpeg']['image/webp'] = 'webp,85';
        }
    }

    public function __invoke(Image $image)
    {
        $type = $image->getType();
        $params = $image->getAttribute('params', []);

        // convert image type?
        if (isset($this->convert[$type])) {
            $image->setAttribute('types', $this->convert[$type]);
        }

        // image covers
        if (isset($params['covers']) && $params['covers'] && !isset($params['sizes'])) {
            $img = $image->apply($params);
            $ratio = round(($img->width / $img->height) * 100);
            $params['sizes'] = "(max-aspect-ratio: {$img->width}/{$img->height}) {$ratio}vh";
        }

        // set default srcset
        if (isset($params['srcset']) && $params['srcset'] === '1') {
            $params['srcset'] = '768,1024,1366,1600,1920,200%';
        }

        $image->setAttribute('params', $params);
    }
}
