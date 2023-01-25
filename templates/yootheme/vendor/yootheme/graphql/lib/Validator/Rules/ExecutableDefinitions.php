<?php

declare(strict_types=1);

namespace YOOtheme\GraphQL\Validator\Rules;

use YOOtheme\GraphQL\Error\Error;
use YOOtheme\GraphQL\Language\AST\DocumentNode;
use YOOtheme\GraphQL\Language\AST\ExecutableDefinitionNode;
use YOOtheme\GraphQL\Language\AST\NodeKind;
use YOOtheme\GraphQL\Language\AST\TypeSystemDefinitionNode;
use YOOtheme\GraphQL\Language\Visitor;
use YOOtheme\GraphQL\Language\VisitorOperation;
use YOOtheme\GraphQL\Validator\ValidationContext;
use function sprintf;

/**
 * Executable definitions
 *
 * A GraphQL document is only valid for execution if all definitions are either
 * operation or fragment definitions.
 */
class ExecutableDefinitions extends ValidationRule
{
    public function getVisitor(ValidationContext $context)
    {
        return [
            NodeKind::DOCUMENT => static function (DocumentNode $node) use ($context) : VisitorOperation {
                /** @var ExecutableDefinitionNode|TypeSystemDefinitionNode $definition */
                foreach ($node->definitions as $definition) {
                    if ($definition instanceof ExecutableDefinitionNode) {
                        continue;
                    }

                    $context->reportError(new Error(
                        self::nonExecutableDefinitionMessage($definition->name->value),
                        [$definition->name]
                    ));
                }

                return Visitor::skipNode();
            },
        ];
    }

    public static function nonExecutableDefinitionMessage($defName)
    {
        return sprintf('The "%s" definition is not executable.', $defName);
    }
}
