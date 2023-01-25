<?php

namespace YOOtheme;

/**
 * A static class which provides utilities for working with directory paths.
 */
abstract class Path
{
    /**
     * @var array
     */
    protected static $aliases = [];

    /**
     * Gets an absolute path by resolving aliases and current directory.
     *
     * @param string $path
     *
     * @return string
     *
     * @example
     * Path::get('~app/dir');
     * // => /app/dir
     */
    public static function get($path)
    {
        $path = static::resolveAlias($path);

        // path does not start with ./ or ../
        if (!preg_match('/^\.\.?(?:\/.*)?$/', $path)) {
            return $path;
        }

        // get caller file
        $file = Reflection::getCaller('file');

        return static::join(dirname($file), $path);
    }

    /**
     * Sets a path alias.
     *
     * @param string $alias
     * @param string $path
     *
     * @example
     * Path::setAlias('~app', '/app');
     *
     * Path::resolveAlias('~app/resource');
     * // => /app/resource
     */
    public static function setAlias($alias, $path)
    {
        if (substr($alias, 0, 1) !== '~') {
            throw new \InvalidArgumentException("The alias '{$alias}' must start with ~");
        }

        $path = rtrim(static::resolveAlias($path), '/');
        $alias = rtrim(strtr($alias, '\\', '/'), '/');

        [$name] = explode('/', $alias, 2);

        static::$aliases[$name]["$alias/"] = "$path/";
    }

    /**
     * Resolve a path with alias.
     *
     * @param string $path
     *
     * @return string
     *
     * @example
     * Path::setAlias('~app', '/app');
     *
     * Path::resolveAlias('~app/resource');
     * // => /app/resource
     */
    public static function resolveAlias($path)
    {
        $path = strtr($path, '\\', '/');
        $trim = substr($path, -1) !== '/';

        [$name] = explode('/', $path, 2);

        if (substr($name, 0, 1) !== '~') {
            return $path;
        }

        $path = Event::emit("path {$name}|filter", $path, substr($path, strlen($name)));

        if (isset(static::$aliases[$name])) {
            $path = strtr($trim ? "{$path}/" : $path, static::$aliases[$name]);
        }

        return $trim ? rtrim($path, '/') : $path;
    }

    /**
     * Resolves a sequence of paths or path segments into an absolute path. All path segments are processed from right to left.
     *
     * @param string $paths
     *
     * @return string
     *
     * @example
     * Path::resolve('~app/dir/dir', '../resource');
     * // => /app/dir/resource
     */
    public static function resolve(...$paths)
    {
        $parts = [];

        foreach (array_reverse($paths) as $path) {
            $path = static::resolveAlias($path);

            array_unshift($parts, $path);

            if (static::isAbsolute($path)) {
                break;
            }
        }

        $path = static::join(...$parts);

        return $path !== '/' ? rtrim($path, '/') : $path;
    }

    /**
     * Returns trailing name component of path.
     *
     * @param string $path
     * @param string $suffix
     *
     * @return string
     *
     * @example
     * Path::basename('~app/dir/file.php');
     * // => file.php
     */
    public static function basename($path, $suffix = '')
    {
        return basename(static::resolveAlias($path), $suffix);
    }

    /**
     * Returns the extension of the path.
     *
     * @param string $path
     *
     * @return string
     *
     * @example
     * Path::extname('~app/dir/file.php');
     * // => .php
     */
    public static function extname($path)
    {
        $basename = static::basename($path);
        $position = strrpos($basename, '.');

        return $position ? substr($basename, $position) : '';
    }

    /**
     * Returns a parent directory's path.
     *
     * @param string $path
     *
     * @return string
     *
     * @example
     * Path::dirname('~app/dir/file.php');
     * // => /app/dir
     */
    public static function dirname($path)
    {
        return dirname(static::resolveAlias($path));
    }

    /**
     * Gets the relative path to a given base path.
     *
     * @param string $from
     * @param string $to
     *
     * @return string
     *
     * @example
     * Path::relative('/path/dir/test/aaa', '/path/dir/impl/bbb');
     * // => ../../impl/bbb
     */
    public static function relative($from, $to)
    {
        $from = static::resolveAlias($from);
        $to = static::resolveAlias($to);

        if ($to === '') {
            return $from;
        }

        $_from = static::parse($from);
        $_to = static::parse($to);

        if ($_from['root'] !== $_to['root']) {
            throw new \InvalidArgumentException(
                "The path '{$to}' can\'t be made relative to the path '{$from}'. Path roots aren\'t equal."
            );
        }

        $fromParts = explode('/', $_from['pathname']);
        $toParts = explode('/', $_to['pathname']);

        $match = true;
        $prefix = '';

        foreach ($fromParts as $i => $fromPart) {
            if ('' === $fromPart) {
                continue;
            }

            if ($match && isset($toParts[$i]) && $fromPart === $toParts[$i]) {
                unset($toParts[$i]);
                continue;
            }

            $match = false;
            $prefix .= '../';
        }

        return rtrim($prefix . join('/', $toParts), '/');
    }

    /**
     * Normalizes a path, resolving '..' and '.' segments.
     *
     * @param string $path
     *
     * @return string
     *
     * @example
     * Path::normalize('/path1/.././file.txt');
     * // => /file.txt
     */
    public static function normalize($path)
    {
        static $cache;

        if (!$path) {
            return '';
        }

        if (isset($cache[$path])) {
            return $cache[$path];
        }

        $result = [];
        $parsed = static::parse($path);
        $parts = explode('/', $parsed['pathname']);

        foreach ($parts as $i => $part) {
            if ('.' === $part) {
                continue;
            }

            if ('' === $part && isset($parts[$i + 1])) {
                continue;
            }

            if ($part === '..' && $result && end($result) !== '..') {
                array_pop($result);
                continue;
            }

            if ($part !== '..' || $parsed['root'] === '') {
                $result[] = $part;
            }
        }

        return $cache[$path] = $parsed['root'] . join('/', $result);
    }

    /**
     * Joins all given path segments together.
     *
     * @param string $parts
     *
     * @return string
     *
     * @example
     * Path::join('/foo', '/bar', 'baz/asdf', 'quux', '..');
     * // => /foo/bar/baz/asdf
     */
    public static function join(...$parts)
    {
        return static::normalize(join('/', $parts));
    }

    /**
     * Returns information about a path.
     *
     * @param string $path
     *
     * @return array
     *
     * @example
     * Path::parse('/foo/file.txt');
     * // => ['root' => '/', 'pathname' => 'foo/file.txt', 'dirname' => '/foo', 'basename' => 'file.txt', 'filename' => 'file', 'extension' => 'txt']
     */
    public static function parse($path)
    {
        $path = strtr($path, '\\', '/');
        $root = static::root($path) ?: '';

        return pathinfo($path) + [
            'root' => $root,
            'pathname' => substr($path, strlen($root)),
            'dirname' => null,
            'extension' => null,
        ];
    }

    /**
     * Checks if path is absolute.
     *
     * @param string $path
     *
     * @return bool
     *
     * @example
     * Path::isAbsolute('/foo/file.txt');
     * // => true
     */
    public static function isAbsolute($path)
    {
        return (bool) static::root($path);
    }

    /**
     * Checks if path is relative.
     *
     * @param string $path
     *
     * @return bool
     *
     * @example
     * Path::isRelative('foo/file.txt');
     * // => true
     */
    public static function isRelative($path)
    {
        return !static::root($path);
    }

    /**
     * Checks if path is a base path of another path.
     *
     * @param string $basePath
     * @param string $path
     *
     * @return bool
     *
     * @example
     * Path::isBasePath('/foo/', '/foo/file.txt');
     * // => true
     * Path::isBasePath('/foo', '/foo');
     * // => true
     * Path::isBasePath('/foo', '/foo/..');
     * // => false
     */
    public static function isBasePath($basePath, $path)
    {
        $basePath = static::normalize(static::resolveAlias($basePath));
        $path = static::normalize(static::resolveAlias($path));

        return str_starts_with($path . '/', rtrim($basePath, '/') . '/');
    }

    /**
     * Returns path root.
     *
     * @param string $path
     *
     * @return mixed
     */
    public static function root($path)
    {
        $path = strtr($path, '\\', '/');

        if ($path && $path[0] === '/') {
            return '/';
        }

        if (strpos($path, ':') && preg_match('/^([a-z]*:)?(\/\/|\/)/i', $path, $matches)) {
            return $matches[0];
        }
    }
}
