<?php

declare(strict_types=1);

namespace YOOtheme\GraphQL\Type\Definition;

interface WrappingType
{
    public function getWrappedType(bool $recurse = false) : Type;
}
