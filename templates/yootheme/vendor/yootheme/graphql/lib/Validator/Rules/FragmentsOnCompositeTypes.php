<?php

declare(strict_types=1);

namespace YOOtheme\GraphQL\Validator\Rules;

use YOOtheme\GraphQL\Error\Error;
use YOOtheme\GraphQL\Language\AST\FragmentDefinitionNode;
use YOOtheme\GraphQL\Language\AST\InlineFragmentNode;
use YOOtheme\GraphQL\Language\AST\NodeKind;
use YOOtheme\GraphQL\Language\Printer;
use YOOtheme\GraphQL\Type\Definition\Type;
use YOOtheme\GraphQL\Utils\TypeInfo;
use YOOtheme\GraphQL\Validator\ValidationContext;
use function sprintf;

class FragmentsOnCompositeTypes extends ValidationRule
{
    public function getVisitor(ValidationContext $context)
    {
        return [
            NodeKind::INLINE_FRAGMENT     => static function (InlineFragmentNode $node) use ($context) : void {
                if (! $node->typeCondition) {
                    return;
                }

                $type = TypeInfo::typeFromAST($context->getSchema(), $node->typeCondition);
                if (! $type || Type::isCompositeType($type)) {
                    return;
                }

                $context->reportError(new Error(
                    static::inlineFragmentOnNonCompositeErrorMessage($type),
                    [$node->typeCondition]
                ));
            },
            NodeKind::FRAGMENT_DEFINITION => static function (FragmentDefinitionNode $node) use ($context) : void {
                $type = TypeInfo::typeFromAST($context->getSchema(), $node->typeCondition);

                if (! $type || Type::isCompositeType($type)) {
                    return;
                }

                $context->reportError(new Error(
                    static::fragmentOnNonCompositeErrorMessage(
                        $node->name->value,
                        Printer::doPrint($node->typeCondition)
                    ),
                    [$node->typeCondition]
                ));
            },
        ];
    }

    public static function inlineFragmentOnNonCompositeErrorMessage($type)
    {
        return sprintf('Fragment cannot condition on non composite type "%s".', $type);
    }

    public static function fragmentOnNonCompositeErrorMessage($fragName, $type)
    {
        return sprintf('Fragment "%s" cannot condition on non composite type "%s".', $fragName, $type);
    }
}
