<?php

namespace YOOtheme\Container;

use Psr\Container\ContainerExceptionInterface;

class BadFunctionCallException extends \BadFunctionCallException implements
    ContainerExceptionInterface
{
    /**
     * Creates an exception from given callback.
     *
     * @param string|callable|object $callback
     * @param mixed                  $code
     * @param null|mixed             $previous
     *
     * @return self
     */
    public static function create($callback, $code = 0, $previous = null)
    {
        $function = $callback;

        if (is_object($callback)) {
            $function = get_class($callback);
        } elseif (is_array($callback)) {
            [$class, $method] = $callback;

            if (is_string($class)) {
                $function = "{$class}::{$method}";
            } else {
                $function = get_class($class) . "@{$method}";
            }
        }

        return new self("Function {$function} is not a callable", $code, $previous);
    }
}
