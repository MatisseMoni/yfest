<?php

namespace YOOtheme\Theme;

use YOOtheme\Event;
use YOOtheme\Path;

class SvgHelper
{
    public static function getDimensions($file, $attrs)
    {
        return static::ratio(static::readDimensions(static::getSvgTag($file)), $attrs);
    }

    protected static function getSvgTag($file)
    {
        $file = Path::resolve('~', Event::emit('svg.resolve|filter', $file));

        $result = '';

        if ($file && is_readable($file) && ($resource = @fopen($file, 'r'))) {
            while (($line = fgets($resource, 4096)) !== false) {
                if ($result) {
                    $result .= $line;
                } elseif (str_contains($line, '<svg')) {
                    $result = $line;
                }

                if ($result && str_contains($line, '>')) {
                    $result = substr($result, 0, strpos($line, '>') - (strlen($line) - 1));
                    break;
                }
            }
            fclose($resource);
        }

        return $result;
    }

    protected static function readDimensions($tag)
    {
        if (
            !preg_match_all(
                '/(?<prop>height|width|viewBox)=([\'\"])(?<value>[\\d\\s.]*?)(?:px)?\\2/i',
                $tag,
                $matches
            )
        ) {
            return [];
        }

        $dim = ['width' => null, 'height' => null];
        foreach ($matches['prop'] as $i => $prop) {
            $dim[strtolower($prop)] = $matches['value'][$i];
        }

        if ((empty($dim['width']) || empty($dim['height'])) && !empty($dim['viewbox'])) {
            return static::ratio(array_slice(explode(' ', $dim['viewbox']), 2), $dim);
        }
        return [$dim['width'], $dim['height']];
    }

    protected static function ratio($props, $attrs)
    {
        $dim = array_pad($props, 2, null);
        $props = ['width', 'height'];
        foreach ($props as $i => $prop) {
            $aprop = $props[1 - $i];
            if (empty((int) $attrs[$prop]) && !empty((int) $attrs[$aprop]) && !empty($dim[$i])) {
                $$prop = (string) round($dim[$i] * ((int) $attrs[$aprop] / $dim[1 - $i]));
            } else {
                $$prop = empty((int) $attrs[$prop]) ? $dim[$i] : $attrs[$prop];
            }
        }

        return [$width, $height]; // @phpstan-ignore-line
    }
}
