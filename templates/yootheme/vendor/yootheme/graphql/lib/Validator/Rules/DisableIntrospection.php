<?php

declare(strict_types=1);

namespace YOOtheme\GraphQL\Validator\Rules;

use YOOtheme\GraphQL\Error\Error;
use YOOtheme\GraphQL\Language\AST\FieldNode;
use YOOtheme\GraphQL\Language\AST\NodeKind;
use YOOtheme\GraphQL\Validator\ValidationContext;

class DisableIntrospection extends QuerySecurityRule
{
    public const ENABLED = 1;

    /** @var bool */
    private $isEnabled;

    public function __construct($enabled = self::ENABLED)
    {
        $this->setEnabled($enabled);
    }

    public function setEnabled($enabled)
    {
        $this->isEnabled = $enabled;
    }

    public function getVisitor(ValidationContext $context)
    {
        return $this->invokeIfNeeded(
            $context,
            [
                NodeKind::FIELD => static function (FieldNode $node) use ($context) : void {
                    if ($node->name->value !== '__type' && $node->name->value !== '__schema') {
                        return;
                    }

                    $context->reportError(new Error(
                        static::introspectionDisabledMessage(),
                        [$node]
                    ));
                },
            ]
        );
    }

    public static function introspectionDisabledMessage()
    {
        return 'GraphQL introspection is not allowed, but the query contained __schema or __type';
    }

    protected function isEnabled()
    {
        return $this->isEnabled !== self::DISABLED;
    }
}
