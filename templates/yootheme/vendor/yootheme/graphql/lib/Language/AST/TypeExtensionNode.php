<?php

declare(strict_types=1);

namespace YOOtheme\GraphQL\Language\AST;

/**
 * export type TypeExtensionNode =
 * | ScalarTypeExtensionNode
 * | ObjectTypeExtensionNode
 * | InterfaceTypeExtensionNode
 * | UnionTypeExtensionNode
 * | EnumTypeExtensionNode
 * | InputObjectTypeExtensionNode;
 */
interface TypeExtensionNode extends TypeSystemDefinitionNode
{
}
