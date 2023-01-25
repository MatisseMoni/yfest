<?php

namespace YOOtheme\Http;

use YOOtheme\Http\Message\Response as BaseResponse;
use YOOtheme\Http\Message\Stream;

class Response extends BaseResponse
{
    use MessageTrait;

    /**
     * @var array
     */
    protected $cookies = [];

    /**
     * Writes data to the body.
     *
     * @param string $data
     *
     * @return static
     */
    public function write($data)
    {
        $body = $this->getBody();
        $body->write($data);

        return $this;
    }

    /**
     * Writes a file to body.
     *
     * @param string|resource $file
     * @param string          $mimetype
     *
     * @throws \InvalidArgumentException
     *
     * @return static
     */
    public function withFile($file, $mimetype = null)
    {
        $body = Stream::create(is_string($file) ? fopen($file, 'r') : $file);

        if (!$mimetype && is_string($file) && is_callable('finfo_file')) {
            $mimetype = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $file);
        }

        if (!$mimetype) {
            $mimetype = mime_content_type($file);
        }

        if (!$mimetype) {
            throw new \InvalidArgumentException('Unknown file MIME type.');
        }

        return $this->withBody($body)
            ->withHeader('Content-Type', $mimetype)
            ->withHeader('Content-Length', $body->getSize());
    }

    /**
     * Writes JSON to the body.
     *
     * @param mixed $data
     * @param int   $status
     * @param int   $options
     *
     * @throws \InvalidArgumentException
     *
     * @return static
     */
    public function withJson(
        $data,
        $status = null,
        $options = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    ) {
        if (!is_string($json = @json_encode($data, $options))) {
            throw new \InvalidArgumentException(json_last_error_msg(), json_last_error());
        }

        $body = Stream::create($json);
        $response = $this->withBody($body)
            // Disable Content-Length header (Essentials YOOtheme Pro 1.7.3 echos white-space, causing the response to be cut)
            // ->withHeader('Content-Length', $body->getSize())
            ->withHeader('Content-Type', 'application/json; charset=utf-8');

        return is_null($status) ? $response : $response->withStatus($status);
    }

    /**
     * Redirect response.
     *
     * @param string $url
     * @param int    $status
     *
     * @return static
     */
    public function withRedirect($url, $status = 302)
    {
        return $this->withStatus($status)->withHeader('Location', (string) $url);
    }

    /**
     * Sets a response cookie.
     *
     * @param string $name
     * @param string $value
     * @param array  $options
     *
     * @return static
     */
    public function withCookie($name, $value = '', array $options = [])
    {
        $defaults = [
            'expire' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => false,
            'httponly' => false,
        ];

        $cookie = array_replace($defaults, $options);
        $cookie['value'] = strval($value);
        $cookie['expire'] = is_string($cookie['expire'])
            ? strtotime($cookie['expire'])
            : intval($cookie['expire']);

        $clone = clone $this;
        $clone->cookies[$name] = $cookie;

        return $clone;
    }

    /**
     * Sends the response.
     *
     * @return static
     */
    public function send()
    {
        if (!headers_sent()) {
            $this->sendHeaders();
        }

        echo $this->getBody();

        flush();

        return $this;
    }

    /**
     * Sends the response headers.
     *
     * @return static
     */
    public function sendHeaders()
    {
        header(
            sprintf(
                'HTTP/%s %s %s',
                $this->getProtocolVersion(),
                $this->getStatusCode(),
                $this->getReasonPhrase()
            )
        );

        foreach ($this->getHeaders() as $name => $values) {
            header(sprintf('%s: %s', $name, implode(',', $values)));
        }

        foreach ($this->cookies as $name => $cookie) {
            setcookie(
                $name,
                $cookie['value'],
                $cookie['expire'],
                $cookie['path'],
                $cookie['domain'],
                $cookie['secure'],
                $cookie['httponly']
            );
        }

        flush();

        return $this;
    }

    /**
     * Is this response informational?
     *
     * @return bool
     */
    public function isInformational()
    {
        return $this->getStatusCode() >= 100 && $this->getStatusCode() < 200;
    }

    /**
     * Is this response OK?
     *
     * @return bool
     */
    public function isOk()
    {
        return $this->getStatusCode() == 200;
    }

    /**
     * Is this response empty?
     *
     * @return bool
     */
    public function isEmpty()
    {
        return in_array($this->getStatusCode(), [204, 205, 304]);
    }

    /**
     * Is this response successful?
     *
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->getStatusCode() >= 200 && $this->getStatusCode() < 300;
    }

    /**
     * Is this response a redirect?
     *
     * @return bool
     */
    public function isRedirect()
    {
        return in_array($this->getStatusCode(), [301, 302, 303, 307]);
    }

    /**
     * Is this response a redirection?
     *
     * @return bool
     */
    public function isRedirection()
    {
        return $this->getStatusCode() >= 300 && $this->getStatusCode() < 400;
    }

    /**
     * Is this response forbidden?
     *
     * @return bool
     */
    public function isForbidden()
    {
        return $this->getStatusCode() == 403;
    }

    /**
     * Is this response not Found?
     *
     * @return bool
     */
    public function isNotFound()
    {
        return $this->getStatusCode() == 404;
    }

    /**
     * Is this response a client error?
     *
     * @return bool
     */
    public function isClientError()
    {
        return $this->getStatusCode() >= 400 && $this->getStatusCode() < 500;
    }

    /**
     * Is this response a server error?
     *
     * @return bool
     */
    public function isServerError()
    {
        return $this->getStatusCode() >= 500 && $this->getStatusCode() < 600;
    }

    /**
     * Returns the body as a string.
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getBody();
    }
}
