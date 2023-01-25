<?php

namespace YOOtheme\Builder;

use YOOtheme\Event;
use YOOtheme\GraphQL\Executor\ExecutionResult;
use YOOtheme\GraphQL\GraphQL;
use YOOtheme\GraphQL\SchemaBuilder;
use YOOtheme\GraphQL\Type\Schema;
use YOOtheme\GraphQL\Utils\AST;
use YOOtheme\GraphQL\Utils\Introspection;

class Source extends SchemaBuilder
{
    /**
     * @var Schema|null
     */
    protected $schema;

    /**
     * Gets the schema.
     *
     * @return Schema
     */
    public function getSchema()
    {
        return $this->schema ?: ($this->schema = $this->buildSchema());
    }

    /**
     * Sets the schema.
     *
     * @param Schema $schema
     *
     * @return Schema
     */
    public function setSchema(Schema $schema)
    {
        return $this->schema = $schema;
    }

    /**
     * Executes a query on schema.
     *
     * @param mixed       $source
     * @param mixed       $value
     * @param mixed       $context
     * @param array|null  $variables
     * @param string|null $operation
     * @param callable    $fieldResolver
     * @param array       $validationRules
     *
     * @return ExecutionResult
     */
    public function query(
        $source,
        $value = null,
        $context = null,
        $variables = null,
        $operation = null,
        $fieldResolver = null,
        $validationRules = null
    ) {
        if (is_array($source)) {
            $source = AST::fromArray($source);
        }

        return GraphQL::executeQuery(
            $this->getSchema(),
            $source,
            $value,
            $context,
            $variables,
            $operation,
            $fieldResolver,
            $validationRules
        );
    }

    /**
     * Executes an introspection on schema.
     *
     * @param array $options
     *
     * @return ExecutionResult
     */
    public function queryIntrospection(array $options = [])
    {
        $metadata = [
            'type' => $this->getType('Object'),
            'resolve' => function ($type) {
                return Event::emit(
                    'source.type.metadata|filter',
                    $type->config['metadata'] ?? null,
                    $type
                );
            },
        ];

        $options += [
            '__Type' => compact('metadata'),
            '__Field' => compact('metadata'),
            '__Directive' => compact('metadata'),
            '__InputValue' => compact('metadata'),
        ];

        return GraphQL::executeQuery(
            $this->getSchema(),
            Introspection::getIntrospectionQuery($options)
        );
    }
}
