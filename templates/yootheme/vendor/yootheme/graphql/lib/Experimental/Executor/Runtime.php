<?php

declare(strict_types=1);

namespace YOOtheme\GraphQL\Experimental\Executor;

use YOOtheme\GraphQL\Language\AST\ValueNode;
use YOOtheme\GraphQL\Type\Definition\EnumType;
use YOOtheme\GraphQL\Type\Definition\InputObjectType;
use YOOtheme\GraphQL\Type\Definition\InputType;
use YOOtheme\GraphQL\Type\Definition\ListOfType;
use YOOtheme\GraphQL\Type\Definition\NonNull;
use YOOtheme\GraphQL\Type\Definition\ScalarType;

/**
 * @internal
 */
interface Runtime
{
    /**
     * @param ScalarType|EnumType|InputObjectType|ListOfType|NonNull $type
     */
    public function evaluate(ValueNode $valueNode, InputType $type);

    public function addError($error);
}
