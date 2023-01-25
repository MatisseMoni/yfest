<?php

namespace YOOtheme\Builder\Source\Query;

class Node implements \JsonSerializable
{
    /**
     * @var string
     */
    public $kind;

    /**
     * @var ?string
     */
    public $name;

    /**
     * @var ?string
     */
    public $alias;

    /**
     * @var array
     */
    public $children = [];

    /**
     * @var array
     */
    public $arguments = [];

    /**
     * @var array
     */
    public $directives = [];

    /**
     * Constructor.
     */
    public function __construct($kind, $name, array $options = [])
    {
        $this->kind = $kind;
        $this->name = $name;

        foreach ($options as $key => $value) {
            $this->$key = $value;
        }
    }

    public function get($name)
    {
        foreach ($this->children as $child) {
            if ($child->name === $name) {
                return $child;
            }
        }

        return null;
    }

    public function query($name = null)
    {
        static::assertNode($this, 'Document');

        return $this->children[] = new self('Query', $name);
    }

    public function field($name, array $arguments = [])
    {
        static::assertNode($this, 'Field', 'Query');

        return $this->children[] = new self('Field', $name, ['arguments' => $arguments]);
    }

    public function directive($name, array $arguments = [])
    {
        static::assertNode($this, 'Field');

        return $this->directives[] = new self('Directive', $name, ['arguments' => $arguments]);
    }

    public function toAST()
    {
        return AST::build($this);
    }

    public function toHash()
    {
        return hash('crc32b', json_encode($this));
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return array_values(
            array_filter([$this->kind, $this->name, $this->arguments, $this->directives])
        );
    }

    public static function document()
    {
        return new self('Document', null);
    }

    protected static function assertNode(self $node, ...$kind)
    {
        if (!in_array($node->kind, $kind, true)) {
            throw new \Exception('Node must be a ' . join(', ', $kind));
        }
    }
}
