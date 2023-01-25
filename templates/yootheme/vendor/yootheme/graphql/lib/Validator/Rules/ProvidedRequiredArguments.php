<?php

declare(strict_types=1);

namespace YOOtheme\GraphQL\Validator\Rules;

use YOOtheme\GraphQL\Error\Error;
use YOOtheme\GraphQL\Language\AST\FieldNode;
use YOOtheme\GraphQL\Language\AST\NodeKind;
use YOOtheme\GraphQL\Language\Visitor;
use YOOtheme\GraphQL\Language\VisitorOperation;
use YOOtheme\GraphQL\Validator\ValidationContext;
use function sprintf;

class ProvidedRequiredArguments extends ValidationRule
{
    public function getVisitor(ValidationContext $context)
    {
        $providedRequiredArgumentsOnDirectives = new ProvidedRequiredArgumentsOnDirectives();

        return $providedRequiredArgumentsOnDirectives->getVisitor($context) + [
            NodeKind::FIELD => [
                'leave' => static function (FieldNode $fieldNode) use ($context) : ?VisitorOperation {
                    $fieldDef = $context->getFieldDef();

                    if (! $fieldDef) {
                        return Visitor::skipNode();
                    }
                    $argNodes = $fieldNode->arguments ?? [];

                    $argNodeMap = [];
                    foreach ($argNodes as $argNode) {
                        $argNodeMap[$argNode->name->value] = $argNode;
                    }
                    foreach ($fieldDef->args as $argDef) {
                        $argNode = $argNodeMap[$argDef->name] ?? null;
                        if ($argNode || ! $argDef->isRequired()) {
                            continue;
                        }

                        $context->reportError(new Error(
                            self::missingFieldArgMessage($fieldNode->name->value, $argDef->name, $argDef->getType()),
                            [$fieldNode]
                        ));
                    }

                    return null;
                },
            ],
        ];
    }

    public static function missingFieldArgMessage($fieldName, $argName, $type)
    {
        return sprintf(
            'Field "%s" argument "%s" of type "%s" is required but not provided.',
            $fieldName,
            $argName,
            $type
        );
    }
}
