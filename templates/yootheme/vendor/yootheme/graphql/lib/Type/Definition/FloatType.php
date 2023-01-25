<?php

declare(strict_types=1);

namespace YOOtheme\GraphQL\Type\Definition;

use Exception;
use YOOtheme\GraphQL\Error\Error;
use YOOtheme\GraphQL\Language\AST\FloatValueNode;
use YOOtheme\GraphQL\Language\AST\IntValueNode;
use YOOtheme\GraphQL\Language\AST\Node;
use YOOtheme\GraphQL\Utils\Utils;
use function floatval;
use function is_bool;
use function is_finite;
use function is_float;
use function is_int;
use function is_numeric;

class FloatType extends ScalarType
{
    /** @var string */
    public $name = Type::FLOAT;

    /** @var string */
    public $description =
        'The `Float` scalar type represents signed double-precision fractional
values as specified by
[IEEE 754](http://en.wikipedia.org/wiki/IEEE_floating_point). ';

    /**
     * @param mixed $value
     *
     * @throws Error
     */
    public function serialize($value) : float
    {
        $float = is_numeric($value) || is_bool($value)
            ? (float) $value
            : null;

        if ($float === null || ! is_finite($float)) {
            throw new Error(
                'Float cannot represent non numeric value: ' .
                Utils::printSafe($value)
            );
        }

        return $float;
    }

    /**
     * @param mixed $value
     *
     * @throws Error
     */
    public function parseValue($value) : float
    {
        $float = is_float($value) || is_int($value)
            ? (float) $value
            : null;

        if ($float === null || ! is_finite($float)) {
            throw new Error(
                'Float cannot represent non numeric value: ' .
                Utils::printSafe($value)
            );
        }

        return $float;
    }

    /**
     * @param mixed[]|null $variables
     *
     * @return float
     *
     * @throws Exception
     */
    public function parseLiteral(Node $valueNode, ?array $variables = null)
    {
        if ($valueNode instanceof FloatValueNode || $valueNode instanceof IntValueNode) {
            return (float) $valueNode->value;
        }

        // Intentionally without message, as all information already in wrapped Exception
        throw new Error();
    }
}
