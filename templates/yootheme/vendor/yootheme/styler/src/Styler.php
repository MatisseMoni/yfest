<?php

namespace YOOtheme\Theme;

use YOOtheme\Config;
use YOOtheme\File;
use YOOtheme\Path;
use YOOtheme\Url;

class Styler
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * Constructor.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Gets theme styles.
     *
     * @return array
     */
    public function getThemes()
    {
        $themes = [];
        $directories = join(
            ',',
            array_filter([
                $this->config->get('theme.rootDir'),
                $this->config->get('theme.childDir'),
            ])
        );

        foreach (File::glob("{{$directories}}/less/theme.*.less") as $file) {
            $themes[] = $this->getTheme(substr(basename($file, '.less'), 6));
        }

        return $themes;
    }

    public function getTheme($id)
    {
        $file = File::get("~theme/less/theme.{$id}.less");

        if (!$file) {
            return;
        }

        return array_merge(
            [
                'id' => $id,
                'file' => $file,
                'name' => static::namify($id),
            ],
            static::getMeta($file)
        );
    }

    public function resolveImports($file, $vars = [])
    {
        $importFile = $file;
        $extension = Path::extname($file);

        if (!file_exists($file)) {
            if ($extension || !file_exists($file = "{$file}.less")) {
                return [];
            }
        }

        $contents = @file_get_contents($file) ?: '';

        if (!$extension || $extension === '.less') {
            $contents = preg_replace('/^\s*\/\/.*?$/m', '', $contents);
            $contents = preg_replace('/\/\*.*?\*\//s', '', $contents);
            $contents = preg_replace('/(^@[a-z0-9-]+:)\s+/m', '$1 ', $contents);
            $contents = preg_replace('/\n{2,}/', "\n", $contents);
            $contents = preg_replace('/^\n|\n$/', '', $contents);
        }

        $imports = [Path::normalize(Url::to($importFile)) => $contents];

        if (preg_match_all('/^@import.*?"([^"]+)";/m', $contents, $matches)) {
            $replacePairs = [];
            foreach ($vars as $name => $value) {
                $name = substr($name, 1);
                $replacePairs["@{{$name}}"] = $value;
            }

            foreach ($matches[1] as $path) {
                $imports += $this->resolveImports(
                    Path::resolve(dirname($file), strtr($path, $replacePairs)),
                    $vars
                );
            }
        }

        return $imports;
    }

    protected static function getMeta($file)
    {
        $meta = [];
        $style = false;
        $handle = fopen($file, 'r');
        $content = str_replace("\r", "\n", fread($handle, 8192));
        fclose($handle);

        // parse first comment
        if (!preg_match('/^\s*\/\*(?:(?!\*\/).|\n)+\*\//', $content, $matches)) {
            return $meta;
        }

        // parse all metadata
        if (
            !preg_match_all(
                '/^[ \t\/*#@]*(name|style|background|color|type|preview):(.*)$/mi',
                $matches[0],
                $matches
            )
        ) {
            return $meta;
        }

        foreach ($matches[1] as $i => $key) {
            $key = strtolower(trim($key));
            $value = trim($matches[2][$i]);

            if (!in_array($key, ['name', 'style', 'preview'])) {
                $value = array_map('ucwords', array_map('trim', explode(',', $value)));
            }

            if ($key === 'preview') {
                $value = Url::to(Path::resolve(dirname($file), $value));
            }

            if (!$style && $key != 'style') {
                $meta[$key] = $value;
            } elseif ($key == 'style') {
                $style = $value;
                $meta['styles'][$style] = ['name' => static::namify($style)];
            } else {
                $meta['styles'][$style][$key] = $value;
            }
        }

        return $meta;
    }

    protected static function namify($id)
    {
        return ucwords(str_replace('-', ' ', $id));
    }
}
