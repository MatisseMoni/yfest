<?php

namespace YOOtheme\Builder\Templates;

use YOOtheme\Config;

class TemplateListener
{
    public static function initCustomizer(Config $config)
    {
        $options = [];

        foreach ($config('customizer.templates', []) as $name => $template) {
            if (isset($template['group'])) {
                $options[$template['group']][] = ['value' => $name, 'text' => $template['label']];
            } else {
                $options[$template['label']] = $name;
            }
        }

        $config->add(
            'customizer.sections.builder-templates.fieldset.default.fields.type.options',
            $options
        );
    }
}
