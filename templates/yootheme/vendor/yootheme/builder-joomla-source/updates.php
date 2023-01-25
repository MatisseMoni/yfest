<?php

namespace YOOtheme;

use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;

return [
    '3.0.0-beta.7.1' => function ($node) {
        if (
            !empty($node->source->query->name) &&
            str_starts_with($node->source->query->name, 'customArticle') &&
            isset($node->source->query->arguments->featured)
        ) {
            $node->source->query->arguments->featured = empty(
                $node->source->query->arguments->featured
            )
                ? ''
                : 'only';
        }
    },
    '2.6.0-beta.0.1' => function ($node) {
        if (class_exists(FieldsHelper::class) && isset($node->source->props)) {
            static $fields;

            if ($fields === null) {
                $fields = FieldsHelper::getFields('', null, false, null, true);
            }

            // update media fields to new MediaFieldType
            foreach ((array) $node->source->props as $prop) {
                if (isset($prop->name) && str_contains($prop->name, 'field.')) {
                    foreach ($fields as $field) {
                        if (
                            str_ends_with($prop->name, 'field.' . strtr($field->name, '-', '_')) &&
                            $field->type === 'media'
                        ) {
                            $prop->name .= '.imagefile';
                        }
                    }
                    $prop->name = strtr($prop->name, '-', '_');
                }
            }

            if (
                !empty($node->source->query->field->name) &&
                str_contains($node->source->query->field->name, 'field.')
            ) {
                foreach ($fields as $field) {
                    if (
                        str_ends_with(
                            $node->source->query->field->name,
                            'field.' . strtr($field->name, '-', '_')
                        )
                    ) {
                        if ($field->type === 'subform') {
                            foreach ((array) $node->source->props as $prop) {
                                $prop->name = Str::snakeCase($prop->name);
                            }

                            foreach ((array) $field->fieldparams->get('options', []) as $option) {
                                foreach ($fields as $subField) {
                                    if (
                                        $subField->id === $option->customfield &&
                                        $subField->type === 'media'
                                    ) {
                                        $prefix = "{$field->name}_";
                                        foreach ((array) $node->source->props as $prop) {
                                            if (
                                                $prop->name === strtr($subField->name, '-', '_') ||
                                                $prop->name ===
                                                    strtr(
                                                        substr($subField->name, strlen($prefix)),
                                                        '-',
                                                        '_'
                                                    )
                                            ) {
                                                $prop->name .= '.imagefile';
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        if ($field->type === 'repeatable') {
                            foreach ((array) $field->fieldparams->get('fields', []) as $subField) {
                                if ($subField->fieldtype === 'media') {
                                    foreach ((array) $node->source->props as $prop) {
                                        if ($prop->name === Str::snakeCase($subField->fieldname)) {
                                            $prop->name .= '.imagefile';
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    },

    '2.4.0-beta.5' => function ($node) {
        if (isset($node->source->props)) {
            // refactor show_category argument into show_taxonomy argument
            foreach ((array) $node->source->props as $prop) {
                if (
                    isset($prop->name) &&
                    $prop->name === 'metaString' &&
                    isset($prop->arguments->show_category)
                ) {
                    /** @var object $arguments */
                    $arguments = $prop->arguments;
                    $arguments->show_taxonomy = $arguments->show_category ? 'category' : '';
                    unset($arguments->show_category);
                }
            }
        }
    },

    '2.2.0-beta.0.1' => function ($node) {
        static $fields;

        if (class_exists(FieldsHelper::class) && is_null($fields)) {
            $fields = array_column(
                FieldsHelper::getFields('', null, false, null, true),
                'type',
                'name'
            );
        }

        if (
            isset($node->source->query->field->name) &&
            in_array('field', $field = explode('.', $node->source->query->field->name))
        ) {
            $node->source->query->field->name = strtr($node->source->query->field->name, '-', '_');

            // snake case repeatable field names
            if (isset($fields[end($field)]) && $fields[end($field)] === 'repeatable') {
                foreach ((array) $node->source->props as $prop) {
                    $prop->name = Str::snakeCase($prop->name);
                }
            }
        }

        if (isset($node->source->props)) {
            // snake case custom field names
            foreach ((array) $node->source->props as $prop) {
                if (isset($prop->name) && in_array('field', explode('.', $prop->name))) {
                    $prop->name = strtr($prop->name, '-', '_');
                }
            }
        }
    },
];
