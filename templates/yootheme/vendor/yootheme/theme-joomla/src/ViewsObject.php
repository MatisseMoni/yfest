<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\Event\Event as JoomlaEvent;
use YOOtheme\Event;

/**
 * Only needed for Joomla 3.x because it has no "onBeforeDisplay" event.
 */
class ViewsObject extends \ArrayObject
{
    /**
     * Returns the value at the specified index.
     *
     * @param string $index
     *
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($index)
    {
        if (!$this->offsetExists($index)) {
            $this->offsetSet($index, new \ArrayObject());
        }

        $views = parent::offsetGet($index);

        if (isset($views['html'])) {
            foreach ($views['html'] as $view) {
                Event::emit('view.init', new JoomlaEvent('onBeforeDisplay', ['subject' => $view]));
            }
        }

        return $views;
    }

    /**
     * Register views object as cache array.
     *
     * @return void
     */
    public static function register()
    {
        $class = new \ReflectionClass(BaseController::class);

        if ($class->hasProperty('views')) {
            $views = $class->getProperty('views');
            $views->setAccessible(true);
            $views->setValue(new self());
        }
    }
}
