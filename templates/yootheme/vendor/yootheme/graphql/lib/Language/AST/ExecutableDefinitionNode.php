<?php

declare(strict_types=1);

namespace YOOtheme\GraphQL\Language\AST;

/**
 * export type ExecutableDefinitionNode =
 *   | OperationDefinitionNode
 *   | FragmentDefinitionNode;
 */
interface ExecutableDefinitionNode extends DefinitionNode
{
}
