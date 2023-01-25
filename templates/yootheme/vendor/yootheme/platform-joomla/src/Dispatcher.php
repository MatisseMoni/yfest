<?php

namespace YOOtheme\Joomla;

use Joomla\CMS\Application\CMSApplication;
use Joomla\Event\Event;
use YOOtheme\EventDispatcher;

class Dispatcher extends EventDispatcher
{
    /**
     * @var CMSApplication
     */
    protected $app;

    /**
     * Constructor.
     *
     * @param CMSApplication $app
     */
    public function __construct($app)
    {
        parent::__construct();

        $this->app = $app;
    }

    /**
     * Adds an event listener.
     *
     * @param string   $event
     * @param callable $listener
     * @param int      $priority
     */
    public function addListener($event, $listener, $priority = 0)
    {
        if (empty($this->listeners[$event])) {
            if ($event === 'onAfterCleanModuleList') {
                $handler = function (&$modules) use ($event) {
                    return $this->dispatch($event, new Event($event, [&$modules]));
                };
            } else {
                $handler = function (...$arguments) use ($event) {
                    return $this->dispatch($event, new Event($event, $arguments));
                };
            }

            $this->app->registerEvent($event, $handler);
        }

        parent::addListener($event, $listener, $priority);
    }
}
