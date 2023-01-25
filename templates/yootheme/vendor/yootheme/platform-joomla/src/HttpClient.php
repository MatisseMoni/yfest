<?php

namespace YOOtheme\Joomla;

use Joomla\CMS\Http\HttpFactory;
use Joomla\Registry\Registry;
use YOOtheme\Http\Response;
use YOOtheme\HttpClientInterface;

class HttpClient implements HttpClientInterface
{
    /**
     * Execute a GET HTTP request.
     *
     * @param string $url
     * @param array  $options
     *
     * @return Response
     */
    public function get($url, $options = [])
    {
        $response = HttpFactory::getHttp(new Registry($options))->get($url);

        return (new Response($response->code, $response->headers))->write($response->body);
    }

    /**
     * Execute a POST HTTP request.
     *
     * @param string $url
     * @param string $data
     * @param array  $options
     *
     * @return Response
     */
    public function post($url, $data = null, $options = [])
    {
        $response = HttpFactory::getHttp(new Registry($options))->post($url, $data);

        return (new Response($response->code, $response->headers))->write($response->body);
    }

    /**
     * Execute a PUT HTTP request.
     *
     * @param string $url
     * @param string $data
     * @param array  $options
     *
     * @return Response
     */
    public function put($url, $data = null, $options = [])
    {
        $response = HttpFactory::getHttp(new Registry($options))->put($url, $data);

        return (new Response($response->code, $response->headers))->write($response->body);
    }

    /**
     * Execute a DELETE HTTP request.
     *
     * @param string $url
     * @param array  $options
     *
     * @return Response
     */
    public function delete($url, $options = [])
    {
        $response = HttpFactory::getHttp(new Registry($options))->delete($url);

        return (new Response($response->code, $response->headers))->write($response->body);
    }
}
