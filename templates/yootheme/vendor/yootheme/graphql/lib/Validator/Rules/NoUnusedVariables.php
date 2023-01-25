<?php

declare(strict_types=1);

namespace YOOtheme\GraphQL\Validator\Rules;

use YOOtheme\GraphQL\Error\Error;
use YOOtheme\GraphQL\Language\AST\NodeKind;
use YOOtheme\GraphQL\Language\AST\OperationDefinitionNode;
use YOOtheme\GraphQL\Language\AST\VariableDefinitionNode;
use YOOtheme\GraphQL\Validator\ValidationContext;
use function sprintf;

class NoUnusedVariables extends ValidationRule
{
    /** @var VariableDefinitionNode[] */
    public $variableDefs;

    public function getVisitor(ValidationContext $context)
    {
        $this->variableDefs = [];

        return [
            NodeKind::OPERATION_DEFINITION => [
                'enter' => function () : void {
                    $this->variableDefs = [];
                },
                'leave' => function (OperationDefinitionNode $operation) use ($context) : void {
                    $variableNameUsed = [];
                    $usages           = $context->getRecursiveVariableUsages($operation);
                    $opName           = $operation->name !== null
                        ? $operation->name->value
                        : null;

                    foreach ($usages as $usage) {
                        $node                                 = $usage['node'];
                        $variableNameUsed[$node->name->value] = true;
                    }

                    foreach ($this->variableDefs as $variableDef) {
                        $variableName = $variableDef->variable->name->value;

                        if ($variableNameUsed[$variableName] ?? false) {
                            continue;
                        }

                        $context->reportError(new Error(
                            self::unusedVariableMessage($variableName, $opName),
                            [$variableDef]
                        ));
                    }
                },
            ],
            NodeKind::VARIABLE_DEFINITION  => function ($def) : void {
                $this->variableDefs[] = $def;
            },
        ];
    }

    public static function unusedVariableMessage($varName, $opName = null)
    {
        return $opName
            ? sprintf('Variable "$%s" is never used in operation "%s".', $varName, $opName)
            : sprintf('Variable "$%s" is never used.', $varName);
    }
}
