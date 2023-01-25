<?php

namespace YOOtheme\Builder\Source\Query;

class AST
{
    public static function build(Node $node)
    {
        $build = [static::class, $node->kind];

        return $build($node);
    }

    public static function field(Node $node)
    {
        $result = [
            'kind' => 'Field',
            'name' => static::name($node->name),
            'arguments' => static::arguments($node->arguments),
            'directives' => array_map([static::class, 'directive'], $node->directives),
        ];

        if ($node->alias) {
            $result['alias'] = static::name($node->alias);
        }

        if ($node->children) {
            $result['selectionSet'] = static::selections($node->children);
        }

        return $result;
    }

    public static function query(Node $node)
    {
        $result = [
            'kind' => 'OperationDefinition',
            'operation' => 'query',
            'selectionSet' => static::selections($node->children),
        ];

        if ($node->name) {
            $result['name'] = static::name($node->name);
        }

        return $result;
    }

    public static function document(Node $node)
    {
        return [
            'kind' => 'Document',
            'definitions' => array_map([static::class, 'build'], $node->children),
        ];
    }

    public static function directive(Node $node)
    {
        return [
            'kind' => 'Directive',
            'name' => static::name($node->name),
            'arguments' => static::arguments($node->arguments),
        ];
    }

    public static function name($name)
    {
        return [
            'kind' => 'Name',
            'value' => $name,
        ];
    }

    public static function value($value)
    {
        if (is_array($value)) {
            return [
                'kind' => 'ListValue',
                'values' => array_map([static::class, 'value'], $value),
            ];
        }

        if (is_object($value)) {
            $fields = [];

            foreach (get_object_vars($value) as $key => $val) {
                $fields[] = static::objectField($key, $val);
            }

            return [
                'kind' => 'ObjectValue',
                'fields' => $fields,
            ];
        }

        return [
            'kind' => ucfirst(strtr(gettype($value), ['integer' => 'int']) . 'Value'),
            'value' => (string) $value,
        ];
    }

    public static function objectField($name, $value)
    {
        return [
            'kind' => 'ObjectField',
            'name' => static::name($name),
            'value' => static::value($value),
        ];
    }

    public static function arguments(array $arguments)
    {
        $result = [];

        foreach ($arguments as $name => $value) {
            $result[] = [
                'kind' => 'Argument',
                'name' => static::name($name),
                'value' => static::value($value),
            ];
        }

        return $result;
    }

    public static function selections(array $selections)
    {
        $result = [
            'kind' => 'SelectionSet',
            'selections' => [],
        ];

        foreach ($selections as $selection) {
            $result['selections'][] = static::build($selection);
        }

        return $result;
    }
}
