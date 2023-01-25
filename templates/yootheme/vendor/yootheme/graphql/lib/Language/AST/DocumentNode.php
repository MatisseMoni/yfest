<?php

declare(strict_types=1);

namespace YOOtheme\GraphQL\Language\AST;

class DocumentNode extends Node
{
    /** @var string */
    public $kind = NodeKind::DOCUMENT;

    /** @var NodeList<DefinitionNode&Node> */
    public $definitions;
}
