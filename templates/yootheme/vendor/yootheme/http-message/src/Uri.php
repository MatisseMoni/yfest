<?php

namespace YOOtheme\Http;

use YOOtheme\Http\Message\Uri as BaseUri;

class Uri extends BaseUri
{
    /**
     * Retrieve query string arguments.
     *
     * @return array
     */
    public function getQueryParams()
    {
        parse_str($this->getQuery(), $query);

        return $query;
    }

    /**
     * Retrieve a value from query string arguments.
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
     * Return an instance with the specified query parameters.
     *
     * @param array $parameters
     *
     * @return static
     */
    public function withQueryParams(array $parameters)
    {
        return $this->withQuery(http_build_query($parameters, '', '&'));
    }
}
