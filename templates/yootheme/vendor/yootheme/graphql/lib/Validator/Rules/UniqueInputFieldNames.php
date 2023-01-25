<?php

declare(strict_types=1);

namespace YOOtheme\GraphQL\Validator\Rules;

use YOOtheme\GraphQL\Error\Error;
use YOOtheme\GraphQL\Language\AST\NameNode;
use YOOtheme\GraphQL\Language\AST\NodeKind;
use YOOtheme\GraphQL\Language\AST\ObjectFieldNode;
use YOOtheme\GraphQL\Language\Visitor;
use YOOtheme\GraphQL\Language\VisitorOperation;
use YOOtheme\GraphQL\Validator\ASTValidationContext;
use YOOtheme\GraphQL\Validator\SDLValidationContext;
use YOOtheme\GraphQL\Validator\ValidationContext;
use function array_pop;
use function sprintf;

class UniqueInputFieldNames extends ValidationRule
{
    /** @var array<string, NameNode> */
    public $knownNames;

    /** @var array<array<string, NameNode>> */
    public $knownNameStack;

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
        $this->knownNames     = [];
        $this->knownNameStack = [];

        return [
            NodeKind::OBJECT       => [
                'enter' => function () : void {
                    $this->knownNameStack[] = $this->knownNames;
                    $this->knownNames       = [];
                },
                'leave' => function () : void {
                    $this->knownNames = array_pop($this->knownNameStack);
                },
            ],
            NodeKind::OBJECT_FIELD => function (ObjectFieldNode $node) use ($context) : VisitorOperation {
                $fieldName = $node->name->value;

                if (isset($this->knownNames[$fieldName])) {
                    $context->reportError(new Error(
                        self::duplicateInputFieldMessage($fieldName),
                        [$this->knownNames[$fieldName], $node->name]
                    ));
                } else {
                    $this->knownNames[$fieldName] = $node->name;
                }

                return Visitor::skipNode();
            },
        ];
    }

    public static function duplicateInputFieldMessage($fieldName)
    {
        return sprintf('There can be only one input field named "%s".', $fieldName);
    }
}
