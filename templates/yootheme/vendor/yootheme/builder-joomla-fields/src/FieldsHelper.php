<?php

namespace YOOtheme\Builder\Joomla\Fields;

use Joomla\Component\Fields\Administrator\Helper\FieldsHelper as BaseFieldHelper;

class FieldsHelper
{
    public static function getFields($context, $item = null, $includeSubformFields = false)
    {
        if (!class_exists(BaseFieldHelper::class)) {
            return [];
        }

        // Currently, retrieves subfields too: bug in Joomla 4.0.4 (https://github.com/joomla/joomla-cms/pull/35924)
        return array_filter(
            BaseFieldHelper::getFields($context, $item, false, null, true),
            function ($field) use ($includeSubformFields) {
                return $field->state == 1 &&
                    (version_compare(JVERSION, '4.0', '<') ||
                        $includeSubformFields ||
                        !$field->only_use_in_subform);
            }
        );
    }
}
