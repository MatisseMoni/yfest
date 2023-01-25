<?php

namespace YOOtheme\Joomla;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Document\Document;
use Joomla\CMS\Document\HtmlDocument;
use Joomla\Input\Input;
use YOOtheme\Application;
use YOOtheme\Arr;
use YOOtheme\Config;
use YOOtheme\Http\Exception;
use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use YOOtheme\Metadata;
use YOOtheme\Url;

class Platform
{
    /**
     * Handle application routes.
     */
    public static function handleRoute(
        Application $app,
        Config $config,
        CMSApplication $cms,
        Input $input
    ) {
        $response = null;

        if ($input->getCmd('option') === 'com_ajax' && $input->get('p')) {
            // disable cache
            $config('joomla.config')->set('caching', 0);

            // default format
            $input->def('format', 'raw');

            // get response
            $cms->registerEvent('onAfterDispatch', function () use ($app, &$response, $input) {
                // On administrator routes com_login is rendered for guest users
                if ($input->getCmd('option') !== 'com_ajax') {
                    return;
                }

                $response = $app->run(false);
            });

            // send response
            $cms->registerEvent('onAfterRender', function () use ($cms, &$response) {
                if (!$response) {
                    return;
                }

                $isHtml = strpos($response->getContentType(), 'html');

                if (!$isHtml) {
                    // disable gzip for none html responses like binary images
                    $cms->set('gzip', false);
                }

                if (version_compare(JVERSION, '4.0', '>')) {
                    $cms->allowCache(true);
                    $cms->setResponse($isHtml ? $response->write($cms->getBody()) : $response);
                    return;
                }

                // send headers
                if (!headers_sent()) {
                    $response->sendHeaders();
                }

                // set body for none html responses
                if (!$isHtml) {
                    $cms->setBody($response->getBody());
                }

                // set cms headers (fix issue when headers_sent() is still false)
                if (!headers_sent()) {
                    $cms->allowCache(true);
                    $cms->setHeader('Cache-Control', $response->getHeaderLine('Cache-Control'));
                    $cms->setHeader('Content-Type', $response->getContentType());
                }
            });
        }
    }

    /**
     * Handle application errors.
     *
     * @param Request    $request
     * @param Response   $response
     * @param \Exception $exception
     *
     * @throws \Exception
     *
     * @return Response
     */
    public static function handleError(Request $request, $response, $exception)
    {
        if ($exception instanceof Exception) {
            if (str_contains($request->getHeaderLine('Accept'), 'application/json')) {
                return $response->withJson($exception->getMessage());
            }

            return $response
                ->write($exception->getMessage())
                ->withHeader('Content-Type', 'text/plain');
        }

        throw $exception;
    }

    /**
     * Callback to register assets.
     *
     * @param Metadata $metadata
     * @param Document $document
     */
    public static function registerAssets(Metadata $metadata, Document $document)
    {
        foreach ($metadata->all('style:*') as $style) {
            if ($style->href) {
                $attrs = Arr::omit($style->getAttributes(), ['version', 'href', 'rel', 'defer']);

                if ($style->defer && $document instanceof HtmlDocument) {
                    $document->addHeadLink(
                        htmlentities(Url::to($style->href, ['ver' => $style->version])),
                        'preload',
                        'rel',
                        [
                            'as' => 'style',
                            'onload' => "this.onload=null;this.rel='stylesheet'",
                        ] + $attrs
                    );
                } else {
                    $document->addStyleSheet(
                        htmlentities(Url::to($style->href)),
                        ['version' => $style->version],
                        $attrs
                    );
                }
            } elseif ($value = $style->getValue()) {
                $document->addStyleDeclaration($value);
            }
        }

        foreach ($metadata->all('script:*') as $script) {
            if ($script->src) {
                $document->addScript(
                    htmlentities(Url::to($script->src)),
                    ['version' => $script->version],
                    Arr::omit($script->getAttributes(), ['version', 'src'])
                );
            } elseif ($script->getValue()) {
                if ($document instanceof HtmlDocument) {
                    $document->addCustomTag((string) $script->withAttribute('version', ''));
                } else {
                    $document->addScriptDeclaration((string) $script->withAttribute('version', ''));
                }
            }
        }
    }
}
