<?php

namespace YOOtheme\Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\UriInterface;
use YOOtheme\Http\Message\Factory\Psr17Factory;

class HttpFactory
{
    /**
     * @var Psr17Factory
     */
    protected $factory;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->factory = new Psr17Factory();
    }

    /**
     * Creates an instance from server globals.
     *
     * @param string|UriInterface $uri
     *
     * @return ServerRequestInterface
     */
    public function createServerRequestFromGlobals($uri)
    {
        $body = fopen('php://input', 'r') ?: null;
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        $version = str_replace('HTTP/', '', $_SERVER['SERVER_PROTOCOL'] ?? '1.1');
        $headers = is_callable('getallheaders')
            ? getallheaders()
            : $this->getHeadersFromServer($_SERVER);

        $request = new Request($method, $uri, $headers, $body, $version, $_SERVER);

        // Ensure $_FILES has not been unset (https://wordpress.org/plugins/acf-extended/)
        // @phpstan-ignore-next-line
        if ($files = $this->normalizeFiles($_FILES ?? [])) {
            $request = $request->withUploadedFiles($files);
        }

        if ($override = $request->getHeaderLine('X-Http-Method-Override')) {
            $request = $request->withMethod($override);
        }

        return $request
            ->withParsedBody($_POST)
            ->withQueryParams($_GET)
            ->withCookieParams($_COOKIE);
    }

    /**
     * Implementation from Laminas\Diactoros\marshalHeadersFromSapi().
     */
    public function getHeadersFromServer(array $server)
    {
        $headers = [];

        foreach ($server as $key => $value) {
            // Apache prefixes environment variables with REDIRECT_
            // if they are added by rewrite rules
            if (0 === strpos($key, 'REDIRECT_')) {
                $key = substr($key, 9);

                // We will not overwrite existing variables with the
                // prefixed versions, though
                if (array_key_exists($key, $server)) {
                    continue;
                }
            }

            if ($value && 0 === strpos($key, 'HTTP_')) {
                $name = strtr(strtolower(substr($key, 5)), '_', '-');
                $headers[$name] = $value;

                continue;
            }

            if ($value && 0 === strpos($key, 'CONTENT_')) {
                $name = 'content-' . strtolower(substr($key, 8));
                $headers[$name] = $value;

                continue;
            }
        }

        return $headers;
    }

    /**
     * Return an UploadedFile instance array.
     *
     * @param array $files An array which respect $_FILES structure
     *
     * @return UploadedFileInterface[]
     *
     * @throws \InvalidArgumentException for unrecognized values
     */
    protected function normalizeFiles(array $files)
    {
        $normalized = [];

        foreach ($files as $key => $value) {
            if ($value instanceof UploadedFileInterface) {
                $normalized[$key] = $value;
            } elseif (is_array($value) && isset($value['tmp_name'])) {
                $normalized[$key] = $this->createUploadedFileFromSpec($value);
            } elseif (is_array($value)) {
                $normalized[$key] = $this->normalizeFiles($value);
            } else {
                throw new \InvalidArgumentException('Invalid value in files specification');
            }
        }

        return $normalized;
    }

    /**
     * Create and return an UploadedFile instance from a $_FILES specification.
     *
     * If the specification represents an array of values, this method will
     * delegate to normalizeNestedFileSpec() and return that return value.
     *
     * @param array $value $_FILES struct
     *
     * @return array|UploadedFileInterface
     */
    protected function createUploadedFileFromSpec(array $value)
    {
        if (is_array($value['tmp_name'])) {
            return $this->normalizeNestedFileSpec($value);
        }

        if (UPLOAD_ERR_OK !== $value['error']) {
            $stream = $this->factory->createStream();
        } else {
            try {
                $stream = $this->factory->createStreamFromFile($value['tmp_name']);
            } catch (\RuntimeException $e) {
                $stream = $this->factory->createStream();
            }
        }

        return $this->factory->createUploadedFile(
            $stream,
            (int) $value['size'],
            (int) $value['error'],
            $value['name'],
            $value['type']
        );
    }

    /**
     * Normalize an array of file specifications.
     *
     * Loops through all nested files and returns a normalized array of
     * UploadedFileInterface instances.
     *
     * @return UploadedFileInterface[]
     */
    protected function normalizeNestedFileSpec(array $files = [])
    {
        $normalizedFiles = [];

        foreach (array_keys($files['tmp_name']) as $key) {
            $spec = [
                'tmp_name' => $files['tmp_name'][$key],
                'size' => $files['size'][$key],
                'error' => $files['error'][$key],
                'name' => $files['name'][$key],
                'type' => $files['type'][$key],
            ];
            $normalizedFiles[$key] = $this->createUploadedFileFromSpec($spec);
        }

        return $normalizedFiles;
    }
}
