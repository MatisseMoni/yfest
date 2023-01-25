<?php

namespace YOOtheme\Builder\Templates;

use YOOtheme\Config;
use YOOtheme\Storage;

class TemplateHelper
{
    /**
     * @var array
     */
    public $templates;

    /**
     * @var bool
     */
    public $customizer;

    /**
     * Constructor.
     *
     * @param Config  $config
     * @param Storage $storage
     */
    public function __construct(Config $config, Storage $storage)
    {
        $this->templates = $storage('templates', []);
        $this->customizer = $config('app.isCustomizer');
    }

    public function match(array $template)
    {
        foreach ($this->templates as $id => $templ) {
            if (!empty($templ['status']) && $templ['status'] === 'disabled' && !$this->customizer) {
                continue;
            }

            if (empty($templ['type']) || $templ['type'] !== $template['type']) {
                continue;
            }

            if (isset($template['query'])) {
                if (is_callable($template['query']) && !$template['query']($templ, $template)) {
                    continue;
                }

                if (
                    is_array($template['query']) &&
                    !static::matchQuery($templ, $template['query'])
                ) {
                    continue;
                }
            }

            return compact('id') + $templ;
        }
    }

    protected static function matchQuery(array $templ, array $query)
    {
        foreach ($query as $key => $value) {
            if (empty($templ['query'][$key])) {
                continue;
            }

            if (!array_intersect((array) $value, (array) $templ['query'][$key])) {
                return false;
            }
        }

        return true;
    }
}
