<?php

declare(strict_types=1);

namespace YOOtheme\GraphQL\Validator\Rules;

use YOOtheme\GraphQL\Error\Error;
use YOOtheme\GraphQL\Language\AST\ArgumentNode;
use YOOtheme\GraphQL\Language\AST\NameNode;
use YOOtheme\GraphQL\Language\AST\NodeKind;
use YOOtheme\GraphQL\Language\Visitor;
use YOOtheme\GraphQL\Language\VisitorOperation;
use YOOtheme\GraphQL\Validator\ASTValidationContext;
use YOOtheme\GraphQL\Validator\SDLValidationContext;
use YOOtheme\GraphQL\Validator\ValidationContext;
use function sprintf;

class UniqueArgumentNames extends ValidationRule
{
    /** @var NameNode[] */
    public $knownArgNames;

    public function getSDLVisitor(SDLValidationContext $context)
    {
        return $this->getASTVisitor($context);
    }

    public function getVisitor(ValidationContext $context)
    {
        return $this->getASTVisitor($context);
    }

    public function getASTVisitor(ASTValidationContext $context)
    {
        $this->knownArgNames = [];

        return [
            NodeKind::FIELD     => function () : void {
                $this->knownArgNames = [];
            },
            NodeKind::DIRECTIVE => function () : void {
                $this->knownArgNames = [];
            },
            NodeKind::ARGUMENT  => function (ArgumentNode $node) use ($context) : VisitorOperation {
                $argName = $node->name->value;
                if ($this->knownArgNames[$argName] ?? false) {
                    $context->reportError(new Error(
                        self::duplicateArgMessage($argName),
                        [$this->knownArgNames[$argName], $node->name]
                    ));
                } else {
                    $this->knownArgNames[$argName] = $node->name;
                }

                return Visitor::skipNode();
            },
        ];
    }

    public static function duplicateArgMessage($argName)
    {
        return sprintf('There can be only one argument named "%s".', $argName);
    }
}
