<?php

namespace YOOtheme;

use YOOtheme\Http\Request;
use YOOtheme\Http\Response;

class ImageController
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        Memory::raise();
    }

    /**
     * Gets the image file.
     *
     * @param Request       $request
     * @param Response      $response
     * @param ImageProvider $provider
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function get(Request $request, Response $response, ImageProvider $provider)
    {
        $src = $request->getQueryParam('src');
        $hash = $request->getQueryParam('hash');

        $request->abortIf($hash !== $provider->getHash($src), 400, 'Invalid image hash');
        $response = $response->withHeader('Cache-Control', 'max-age=600, must-revalidate');

        parse_str($src, $params);
        $file = $params['file'];
        unset($params['file']);

        if ($provider->cache && $request->getAttribute('save')) {
            if (!($image = $provider->create($file, false))) {
                $request->abort(404, "Image '{$file}' not found");
            }

            if ($params) {
                $image = $image->apply($params);
            }

            $cache = $image->getFilename(
                Path::join($provider->cache, substr($image->getHash(), 0, 2))
            );

            if (is_file($cache)) {
                $type = pathinfo($cache, PATHINFO_EXTENSION);
                return $response->withFile($cache, "image/{$type}");
            }

            if (!File::makeDir(dirname($cache))) {
                $request->abort(500, 'Image cache dir not found');
            }
        } else {
            $cache = fopen('php://temp', 'rw+');
        }

        if (!($image = $provider->create($file))) {
            $request->abort(404, "Image '{$file}' not found");
        }

        if ($params) {
            $image = $image->apply($params);
        }

        if (!$image->save($cache)) {
            $request->abort(500, 'Image cache saving failed');
        }

        return $response->withFile($cache, "image/{$image->getType()}");
    }
}
