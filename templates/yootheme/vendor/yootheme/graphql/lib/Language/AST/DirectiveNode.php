<?php

declare(strict_types=1);

namespace YOOtheme\GraphQL\Language\AST;

class DirectiveNode extends Node
{
    /** @var string */
    public $kind = NodeKind::DIRECTIVE;

    /** @var NameNode */
    public $name;

    /** @var NodeList<ArgumentNode> */
    public $arguments;
}
