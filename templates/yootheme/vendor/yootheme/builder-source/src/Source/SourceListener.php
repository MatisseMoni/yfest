<?php

namespace YOOtheme\Builder\Source;

use YOOtheme\Builder\Source;
use YOOtheme\Config;
use YOOtheme\Event;
use YOOtheme\File;
use YOOtheme\GraphQL\Error\SyntaxError;
use YOOtheme\GraphQL\SchemaPrinter;
use YOOtheme\Http\Request;
use YOOtheme\Metadata;

class SourceListener
{
    public static function initSource(Config $config, Request $request, $source)
    {
        $dir = $config('image.cacheDir');
        $name = "schema-{$config('source.id')}";
        $file = "{$dir}/{$name}.gql";

        try {
            if (
                $config('app.isSite') &&
                !$request->getAttribute('customizer') &&
                is_file($file) &&
                filectime($file) > filectime(__FILE__)
            ) {
                // load schema from cache
                $hash = hash('crc32b', $file);
                $source->setSchema($source->loadSchema($file, "{$dir}/schema-{$hash}.php"));

                // stop event
                return false;
            }

            // delete invalid schema cache
        } catch (SyntaxError $e) {
            Event::emit('source.error', [$e]);
            File::rename($file, "{$dir}/{$name}.error.gql");
        }
    }

    public static function typeMetadataSource($metadata)
    {
        if (!empty($metadata['arguments'])) {
            $metadata['arguments'] = self::applyFieldOrder($metadata['arguments']);
        }

        if (!empty($metadata['fields'])) {
            $metadata['fields'] = self::applyFieldOrder($metadata['fields']);
        }

        return $metadata;
    }

    public static function errorSource(Metadata $metadata, Config $config, $errors)
    {
        if ($config('app.isCustomizer') || $config('app.debug')) {
            $metadata->set(
                'script:graphql-errors',
                join(
                    "\n",
                    array_map(function ($error) {
                        $error = json_encode($error);
                        return "console.warn({$error});";
                    }, $errors)
                )
            );
        }
    }

    public static function initCustomizer(Config $config, Source $source)
    {
        $dir = $config('image.cacheDir');
        $file = "{$dir}/schema-{$config('source.id')}.gql";
        $result = $source->queryIntrospection()->toArray();
        $content = SchemaPrinter::doPrint($source->getSchema());

        // update schema cache
        if (isset($result['data'])) {
            file_put_contents($file, $content);
        } elseif (is_file($file)) {
            unlink($file);
        }

        $config->add(
            'customizer.schema',
            isset($result['data']) ? $result['data']['__schema'] : $result
        );
    }

    protected static function applyFieldOrder($fields)
    {
        uasort($fields, function ($fieldA, $fieldB) {
            return (!empty($fieldA['@order']) ? $fieldA['@order'] : 0) -
                (!empty($fieldB['@order']) ? $fieldB['@order'] : 0);
        });

        return $fields;
    }
}
