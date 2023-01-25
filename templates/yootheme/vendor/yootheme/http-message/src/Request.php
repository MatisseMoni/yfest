<?php

namespace YOOtheme\Http;

use Psr\Http\Message\UploadedFileInterface;
use YOOtheme\Http\Message\ServerRequest;

class Request extends ServerRequest
{
    use MessageTrait;

    /**
     * Gets a parameter (shortcut).
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function __invoke($key, $default = null)
    {
        return $this->getParam($key, $default);
    }

    /**
     * Retrieve a parameter value from body or query string (in that order).
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getParam($key, $default = null)
    {
        $body = $this->getParsedBody();

        if (is_array($body) && isset($body[$key])) {
            return $body[$key];
        }

        if (is_object($body) && property_exists($body, $key)) {
            return $body->$key;
        }

        return $this->getQueryParam($key, $default);
    }

    /**
     * Retrieve a value from query string parameters.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getQueryParam($key, $default = null)
    {
        $query = $this->getQueryParams();

        return $query[$key] ?? $default;
    }

    /**
     * Retrieve a value from cookies.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getCookieParam($key, $default = null)
    {
        $cookies = $this->getCookieParams();

        return $cookies[$key] ?? $default;
    }

    /**
     * Retrieve a value from server parameters.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getServerParam($key, $default = null)
    {
        $server = $this->getServerParams();

        return $server[$key] ?? $default;
    }

    /**
     * Retrieve a single file upload.
     *
     * @param string $key
     *
     * @return UploadedFileInterface|null
     */
    public function getUploadedFile($key)
    {
        $files = $this->getUploadedFiles();

        return $files[$key] ?? null;
    }

    /**
     * Does this request use a given method?
     *
     * @param string $method
     *
     * @return bool
     */
    public function isMethod($method)
    {
        return $this->getMethod() === strtoupper($method);
    }

    /**
     * Throws an exception.
     *
     * @param int    $code
     * @param string $message
     *
     * @throws Exception
     */
    public function abort($code, $message = '')
    {
        throw new Exception($code, $message);
    }

    /**
     * Throws an exception if given condition is true.
     *
     * @param bool   $bool
     * @param int    $code
     * @param string $message
     *
     * @throws Exception
     *
     * @return $this
     */
    public function abortIf($bool, $code, $message = '')
    {
        if ($bool) {
            throw new Exception($code, $message);
        }

        return $this;
    }
}
