<?php

declare(strict_types=1);

namespace YOOtheme\GraphQL\Validator\Rules;

use YOOtheme\GraphQL\Error\Error;
use YOOtheme\GraphQL\Language\AST\NameNode;
use YOOtheme\GraphQL\Language\AST\NodeKind;
use YOOtheme\GraphQL\Language\AST\OperationDefinitionNode;
use YOOtheme\GraphQL\Language\Visitor;
use YOOtheme\GraphQL\Language\VisitorOperation;
use YOOtheme\GraphQL\Validator\ValidationContext;
use function sprintf;

class UniqueOperationNames extends ValidationRule
{
    /** @var NameNode[] */
    public $knownOperationNames;

    public function getVisitor(ValidationContext $context)
    {
        $this->knownOperationNames = [];

        return [
            NodeKind::OPERATION_DEFINITION => function (OperationDefinitionNode $node) use ($context) : VisitorOperation {
                $operationName = $node->name;

                if ($operationName !== null) {
                    if (! isset($this->knownOperationNames[$operationName->value])) {
                        $this->knownOperationNames[$operationName->value] = $operationName;
                    } else {
                        $context->reportError(new Error(
                            self::duplicateOperationNameMessage($operationName->value),
                            [$this->knownOperationNames[$operationName->value], $operationName]
                        ));
                    }
                }

                return Visitor::skipNode();
            },
            NodeKind::FRAGMENT_DEFINITION  => static function () : VisitorOperation {
                return Visitor::skipNode();
            },
        ];
    }

    public static function duplicateOperationNameMessage($operationName)
    {
        return sprintf('There can be only one operation named "%s".', $operationName);
    }
}
