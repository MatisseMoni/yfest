<?php

namespace YOOtheme\Builder\Source;

use YOOtheme\Builder\Source\Query\Node;

class SourceQuery
{
    /**
     * Creates a source query.
     *
     * @param object $node
     *
     * @return Node
     */
    public function create($node)
    {
        $document = Node::document();

        $this->querySource($node->source, $document->query());

        return $document;
    }

    /**
     * Query source definition.
     *
     * @param object $source
     * @param Node   $node
     *
     * @return void
     */
    public function querySource($source, Node $node)
    {
        $field = $this->queryField($source->query, $node);

        // add field selection
        if (isset($source->query->field)) {
            $field = $this->queryField($source->query->field, $field);
        }

        // add source properties
        if (isset($source->props)) {
            foreach ((array) $source->props as $prop) {
                $this->queryField($prop, $field);
            }
        }
    }

    /**
     * Create nested field nodes.
     *
     * @param object $field
     * @param Node   $node
     *
     * @return Node
     */
    public function queryField($field, Node $node)
    {
        $parts = explode('.', $field->name);
        $name = array_pop($parts);
        $arguments = (array) ($field->arguments ?? []);
        $directives = (array) ($field->directives ?? []);

        foreach ($parts as $part) {
            $node = is_null($_node = $node->get($part)) ? $node->field($part) : $_node;
        }

        // check if field already exists
        $nodeExists = $node->get($name);

        // create node for field
        $node = $node->field($name, $arguments);

        // add directives
        foreach ($directives as $directive) {
            $node->directive($directive->name, (array) ($directive->arguments ?? []));
        }

        // add alias
        if ($nodeExists && $nodeExists->toHash() !== ($hash = $node->toHash())) {
            $node->alias = "{$name}_{$hash}";
            $field->name .= "_{$hash}";
        }

        return $node;
    }
}
