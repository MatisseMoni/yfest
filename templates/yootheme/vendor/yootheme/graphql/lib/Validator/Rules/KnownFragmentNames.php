<?php

declare(strict_types=1);

namespace YOOtheme\GraphQL\Validator\Rules;

use YOOtheme\GraphQL\Error\Error;
use YOOtheme\GraphQL\Language\AST\FragmentSpreadNode;
use YOOtheme\GraphQL\Language\AST\NodeKind;
use YOOtheme\GraphQL\Validator\ValidationContext;
use function sprintf;

class KnownFragmentNames extends ValidationRule
{
    public function getVisitor(ValidationContext $context)
    {
        return [
            NodeKind::FRAGMENT_SPREAD => static function (FragmentSpreadNode $node) use ($context) : void {
                $fragmentName = $node->name->value;
                $fragment     = $context->getFragment($fragmentName);
                if ($fragment) {
                    return;
                }

                $context->reportError(new Error(
                    self::unknownFragmentMessage($fragmentName),
                    [$node->name]
                ));
            },
        ];
    }

    /**
     * @param string $fragName
     */
    public static function unknownFragmentMessage($fragName)
    {
        return sprintf('Unknown fragment "%s".', $fragName);
    }
}
