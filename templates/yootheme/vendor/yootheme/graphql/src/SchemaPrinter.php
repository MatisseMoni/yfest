<?php

namespace YOOtheme\GraphQL;

use YOOtheme\GraphQL\Error\Error;
use YOOtheme\GraphQL\Language\Printer;
use YOOtheme\GraphQL\Type\Definition\InterfaceType;
use YOOtheme\GraphQL\Type\Definition\ObjectType;
use YOOtheme\GraphQL\Type\Schema;
use YOOtheme\GraphQL\Utils\SchemaPrinter as BasePrinter;
use YOOtheme\GraphQL\Utils\Utils;
use function array_keys;
use function array_map;
use function array_values;
use function compact;
use function count;
use function implode;
use function sprintf;

/**
 * Given an instance of Schema, prints it in GraphQL type language.
 */
class SchemaPrinter extends BasePrinter
{
    /**
     * @inheritdoc
     */
    public static function doPrint(Schema $schema, array $options = []): string
    {
        return parent::doPrint($schema, $options + compact('schema'));
    }

    /**
     * @inheritdoc
     */
    public static function printIntrospectionSchema(Schema $schema, array $options = []): string
    {
        return parent::printIntrospectionSchema($schema, $options + compact('schema'));
    }

    /**
     * @inheritdoc
     */
    protected static function printObject(ObjectType $type, array $options): string
    {
        $interfaces = $type->getInterfaces();
        $implementedInterfaces =
            count($interfaces) > 0
                ? ' implements ' .
                    implode(
                        ' & ',
                        array_map(static function (InterfaceType $interface): string {
                            return $interface->name;
                        }, $interfaces)
                    )
                : '';

        return static::printDescription($options, $type) .
            sprintf(
                "type %s%s%s {\n%s\n}",
                $type->name,
                $implementedInterfaces,
                static::printDirectives($type, $options),
                static::printFields($options, $type)
            );
    }

    /**
     * @inheritdoc
     */
    protected static function printFields(array $options, $type): string
    {
        $fields = array_values($type->getFields());

        return implode(
            "\n",
            array_map(
                static function ($f, $i) use ($options): string {
                    return static::printDescription($options, $f, '  ', !$i) .
                        '  ' .
                        $f->name .
                        static::printArgs($options, $f->args, '  ') .
                        ': ' .
                        (string) $f->getType() .
                        static::printDirectives($f, $options) .
                        static::printDeprecated($f);
                },
                $fields,
                array_keys($fields)
            )
        );
    }

    protected static function printDirectives($fieldOrType, array $options): string
    {
        $directives = [];

        if (isset($fieldOrType->astNode->directives)) {
            foreach ($fieldOrType->astNode->directives as $directive) {
                if (!$options['schema']->getDirective($directive->name->value)) {
                    throw new Error(
                        'Unknown directive: ' . Utils::printSafe($directive->name->value) . '.'
                    );
                }

                $directives[] = Printer::doPrint($directive);
            }
        }

        return $directives ? ' ' . implode(' ', $directives) : '';
    }
}
