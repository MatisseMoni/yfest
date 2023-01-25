<?php

declare(strict_types=1);

namespace YOOtheme\GraphQL\Validator\Rules;

use YOOtheme\GraphQL\Error\Error;
use YOOtheme\GraphQL\Language\AST\FragmentDefinitionNode;
use YOOtheme\GraphQL\Language\AST\NodeKind;
use YOOtheme\GraphQL\Language\AST\OperationDefinitionNode;
use YOOtheme\GraphQL\Language\Visitor;
use YOOtheme\GraphQL\Language\VisitorOperation;
use YOOtheme\GraphQL\Validator\ValidationContext;
use function sprintf;

class NoUnusedFragments extends ValidationRule
{
    /** @var OperationDefinitionNode[] */
    public $operationDefs;

    /** @var FragmentDefinitionNode[] */
    public $fragmentDefs;

    public function getVisitor(ValidationContext $context)
    {
        $this->operationDefs = [];
        $this->fragmentDefs  = [];

        return [
            NodeKind::OPERATION_DEFINITION => function ($node) : VisitorOperation {
                $this->operationDefs[] = $node;

                return Visitor::skipNode();
            },
            NodeKind::FRAGMENT_DEFINITION  => function (FragmentDefinitionNode $def) : VisitorOperation {
                $this->fragmentDefs[] = $def;

                return Visitor::skipNode();
            },
            NodeKind::DOCUMENT             => [
                'leave' => function () use ($context) : void {
                    $fragmentNameUsed = [];

                    foreach ($this->operationDefs as $operation) {
                        foreach ($context->getRecursivelyReferencedFragments($operation) as $fragment) {
                            $fragmentNameUsed[$fragment->name->value] = true;
                        }
                    }

                    foreach ($this->fragmentDefs as $fragmentDef) {
                        $fragName = $fragmentDef->name->value;
                        if ($fragmentNameUsed[$fragName] ?? false) {
                            continue;
                        }

                        $context->reportError(new Error(
                            self::unusedFragMessage($fragName),
                            [$fragmentDef]
                        ));
                    }
                },
            ],
        ];
    }

    public static function unusedFragMessage($fragName)
    {
        return sprintf('Fragment "%s" is never used.', $fragName);
    }
}
