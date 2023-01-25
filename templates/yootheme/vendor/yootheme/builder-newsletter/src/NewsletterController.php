<?php

namespace YOOtheme\Builder\Newsletter;

use YOOtheme\Application;
use YOOtheme\Config;
use YOOtheme\Encrypter;
use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use function YOOtheme\trans;

class NewsletterController
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var Config
     */
    protected $config;

    public function __construct(Application $app, Config $config)
    {
        $this->app = $app;
        $this->config = $config;
    }

    public function lists(Request $request, Response $response)
    {
        $settings = $request->getParam('settings');

        try {
            if (!($provider = $this->getProvider($settings))) {
                return $response->withJson('Invalid provider', 400);
            }

            return $response->withJson($provider->lists($settings));
        } catch (\Exception $e) {
            return $response->withJson($e->getMessage(), 400);
        }
    }

    public function subscribe(Request $request, Response $response, Encrypter $encrypter)
    {
        $settings = $encrypter->decrypt($request->getParam('settings'));

        try {
            if (!($provider = $this->getProvider($settings))) {
                return $response->withJson('Invalid provider', 400);
            }

            $provider->subscribe(
                $request->getParam('email'),
                [
                    'first_name' => $request->getParam('first_name', ''),
                    'last_name' => $request->getParam('last_name', ''),
                ],
                $settings
            );
        } catch (\Exception $e) {
            return $response->withJson($e->getMessage(), 400);
        }

        $return = ['successful' => true];

        if ($settings['after_submit'] === 'redirect') {
            $return['redirect'] = $settings['redirect'];
        } else {
            $return['message'] = trans($settings['message']);
        }

        return $response->withJson($return);
    }

    /**
     * @param array $settings
     *
     * @return AbstractProvider|false
     */
    protected function getProvider($settings)
    {
        $name = $settings['name'] ?? '';
        $service = $this->config->get("theme.newsletterProvider.{$name}", '');
        return $name && $service ? $this->app->get($service) : false;
    }
}
