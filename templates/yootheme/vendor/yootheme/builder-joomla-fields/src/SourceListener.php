<?php

namespace YOOtheme\Builder\Joomla\Fields;

use YOOtheme\Config;
use function YOOtheme\trans;

class SourceListener
{
    public static function initSource($source)
    {
        $types = [
            'User' => 'com_users.user',
            'Article' => 'com_content.article',
            'Category' => 'com_content.categories',
            'Contact' => 'com_contact.contact',
        ];

        $source->objectType('SqlField', Type\SqlFieldType::config());
        $source->objectType('ValueField', Type\ValueFieldType::config());
        $source->objectType('MediaField', Type\MediaFieldType::config());
        $source->objectType('ChoiceField', Type\ChoiceFieldType::config());
        $source->objectType('ChoiceFieldString', Type\ChoiceFieldStringType::config());

        foreach ($types as $type => $context) {
            // has custom fields?
            if ($fields = FieldsHelper::getFields($context)) {
                static::configFields($source, $type, $context, $fields);
            }
        }
    }

    public static function initCustomizer(Config $config)
    {
        $fields = [];

        foreach (FieldsHelper::getFields('com_content.article') as $field) {
            if (
                $field->fieldparams->get('multiple') ||
                $field->fieldparams->get('repeat') ||
                $field->type === 'repeatable'
            ) {
                continue;
            }

            $fields[] = ['value' => "field:{$field->id}", 'text' => $field->title];
        }

        if ($fields) {
            $config->add(
                'customizer.sources.articleOrderOptions',
                array_merge($config('customizer.sources.articleOrderOptions'), [
                    ['label' => trans('Custom Fields'), 'options' => $fields],
                ])
            );
        }
    }

    protected static function configFields($source, $type, $context, array $fields)
    {
        // add field on type
        $source->objectType(
            $type,
            $config = [
                'fields' => [
                    'field' => [
                        'type' => ($fieldType = "{$type}Fields"),
                        'metadata' => [
                            'label' => trans('Fields'),
                        ],
                        'extensions' => [
                            'call' => Type\FieldsType::class . '::field',
                        ],
                    ],
                ],
            ]
        );

        if ($type === 'Article') {
            $source->objectType('TagItem', $config);
        }

        // configure field type
        $source->objectType($fieldType, Type\FieldsType::config($source, $type, $context, $fields));
    }
}
