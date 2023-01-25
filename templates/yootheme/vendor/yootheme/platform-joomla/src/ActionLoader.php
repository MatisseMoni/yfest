<?php

namespace YOOtheme\Joomla;

use Joomla\CMS\Factory;
use YOOtheme\Application\EventLoader;
use YOOtheme\Container;

class ActionLoader extends EventLoader
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $app = Factory::getApplication();

        if (version_compare(JVERSION, '4.0', '<')) {
            $dispatcher = new Dispatcher($app);
        } else {
            $dispatcher = $app->getDispatcher();
        }

        parent::__construct($dispatcher);
    }

    /**
     * Load action listeners.
     *
     * @param Container $container
     * @param array     $configs
     */
    public function __invoke(Container $container, array $configs)
    {
        if (!$container->has('dispatcher')) {
            $container->set('dispatcher', $this->dispatcher);
        }

        parent::__invoke($container, $configs);
    }
}
