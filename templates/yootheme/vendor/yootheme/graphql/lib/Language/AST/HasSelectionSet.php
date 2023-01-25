<?php

declare(strict_types=1);

namespace YOOtheme\GraphQL\Language\AST;

/**
 * export type DefinitionNode = OperationDefinitionNode
 *                        | FragmentDefinitionNode
 *
 * @property SelectionSetNode $selectionSet
 */
interface HasSelectionSet
{
}
