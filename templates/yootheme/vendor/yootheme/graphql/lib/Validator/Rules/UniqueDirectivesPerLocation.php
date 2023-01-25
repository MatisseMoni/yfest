<?php

declare(strict_types=1);

namespace YOOtheme\GraphQL\Validator\Rules;

use YOOtheme\GraphQL\Error\Error;
use YOOtheme\GraphQL\Language\AST\DirectiveDefinitionNode;
use YOOtheme\GraphQL\Language\AST\DirectiveNode;
use YOOtheme\GraphQL\Language\AST\Node;
use YOOtheme\GraphQL\Type\Definition\Directive;
use YOOtheme\GraphQL\Validator\ASTValidationContext;
use YOOtheme\GraphQL\Validator\SDLValidationContext;
use YOOtheme\GraphQL\Validator\ValidationContext;
use function sprintf;

/**
 * Unique directive names per location
 *
 * A GraphQL document is only valid if all non-repeatable directives at
 * a given location are uniquely named.
 */
class UniqueDirectivesPerLocation extends ValidationRule
{
    public function getVisitor(ValidationContext $context)
    {
        return $this->getASTVisitor($context);
    }

    public function getSDLVisitor(SDLValidationContext $context)
    {
        return $this->getASTVisitor($context);
    }

    public function getASTVisitor(ASTValidationContext $context)
    {
        /** @var array<string, true> $uniqueDirectiveMap */
        $uniqueDirectiveMap = [];

        $schema            = $context->getSchema();
        $definedDirectives = $schema !== null
            ? $schema->getDirectives()
            : Directive::getInternalDirectives();
        foreach ($definedDirectives as $directive) {
            if ($directive->isRepeatable) {
                continue;
            }

            $uniqueDirectiveMap[$directive->name] = true;
        }

        $astDefinitions = $context->getDocument()->definitions;
        foreach ($astDefinitions as $definition) {
            if (! ($definition instanceof DirectiveDefinitionNode)
                || $definition->repeatable
            ) {
                continue;
            }

            $uniqueDirectiveMap[$definition->name->value] = true;
        }

        return [
            'enter' => static function (Node $node) use ($uniqueDirectiveMap, $context) : void {
                if (! isset($node->directives)) {
                    return;
                }

                $knownDirectives = [];

                /** @var DirectiveNode $directive */
                foreach ($node->directives as $directive) {
                    $directiveName = $directive->name->value;

                    if (! isset($uniqueDirectiveMap[$directiveName])) {
                        continue;
                    }

                    if (isset($knownDirectives[$directiveName])) {
                        $context->reportError(new Error(
                            self::duplicateDirectiveMessage($directiveName),
                            [$knownDirectives[$directiveName], $directive]
                        ));
                    } else {
                        $knownDirectives[$directiveName] = $directive;
                    }
                }
            },
        ];
    }

    public static function duplicateDirectiveMessage($directiveName)
    {
        return sprintf('The directive "%s" can only be used once at this location.', $directiveName);
    }
}
