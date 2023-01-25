<?php

namespace YOOtheme\GraphQL\Utils;

use YOOtheme\GraphQL\Type\Definition\FieldDefinition;
use YOOtheme\GraphQL\Type\Definition\InputObjectField;
use YOOtheme\GraphQL\Type\Definition\InputObjectType;
use YOOtheme\GraphQL\Type\Definition\ObjectType;

class ASTHelper extends AST
{
    public static function objectType(ObjectType $type)
    {
        $node = [
            'kind' => 'ObjectTypeDefinition',
            'name' => [
                'kind' => 'Name',
                'value' => $type->name,
            ],
            'fields' => [],
            'interfaces' => [],
            'directives' => [],
        ];

        if (isset($type->config['directives'])) {
            foreach ($type->config['directives'] as $config) {
                $node['directives'][] = static::directive($config);
            }
        }

        foreach ($type->getFields() as $field) {
            $field->astNode = static::field($field);
        }

        return static::fromArray($node);
    }

    public static function inputType(InputObjectType $type)
    {
        $node = [
            'kind' => 'InputObjectTypeDefinition',
            'name' => [
                'kind' => 'Name',
                'value' => $type->name,
            ],
            'fields' => [],
            'directives' => [],
        ];

        if (isset($type->config['directives'])) {
            foreach ($type->config['directives'] as $config) {
                $node['directives'][] = static::directive($config);
            }
        }

        foreach ($type->getFields() as $field) {
            $field->astNode = static::inputField($field);
        }

        return static::fromArray($node);
    }

    public static function field(FieldDefinition $field)
    {
        $node = [
            'kind' => 'FieldDefinition',
            'name' => [
                'kind' => 'Name',
                'value' => $field->name,
            ],
            'arguments' => [],
            'directives' => [],
        ];

        if (isset($field->config['directives'])) {
            foreach ($field->config['directives'] as $config) {
                $node['directives'][] = static::directive($config);
            }
        }

        return static::fromArray($node);
    }

    public static function inputField(InputObjectField $field)
    {
        $node = [
            'kind' => 'InputValueDefinition',
            'name' => [
                'kind' => 'Name',
                'value' => $field->name,
            ],
            'directives' => [],
        ];

        if (isset($field->config['directives'])) {
            foreach ($field->config['directives'] as $config) {
                $node['directives'][] = static::directive($config);
            }
        }

        return static::fromArray($node);
    }

    public static function directive(array $config)
    {
        $directive = [
            'kind' => 'Directive',
            'name' => [
                'kind' => 'Name',
                'value' => $config['name'],
            ],
        ];

        if (isset($config['args'])) {
            foreach ($config['args'] as $name => $value) {
                $directive['arguments'][] = static::argument($name, $value);
            }
        }

        return static::fromArray($directive);
    }

    public static function argument($name, $value)
    {
        $argument = [
            'kind' => 'Argument',
            'name' => [
                'kind' => 'Name',
                'value' => $name,
            ],
            'value' => [
                'kind' => 'StringValue',
                'value' => $value,
            ],
        ];

        return static::fromArray($argument);
    }
}
