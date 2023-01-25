<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Document\Document;
use Joomla\CMS\Document\HtmlDocument;
use Joomla\CMS\Editor\Editor;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Language;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Uri\Uri;
use YOOtheme\Config;
use YOOtheme\Url;

class EditorListener
{
    public static function initCustomizer(Config $config, Language $language, Document $document)
    {
        $root = Uri::root();
        $editor = Editor::getInstance();
        $element = $config('joomla.config.editor');
        $language->load("plg_editors_{$element}");

        // skip visual editor
        if (in_array($element, ['none', 'codemirror'])) {
            return;
        }

        // current editor plugin
        $plugin = Table::getInstance('Extension');
        $plugin->load([
            'folder' => 'editors',
            'element' => $element,
        ]);

        // Prevent xtd editor buttons from adding assets to the current document
        Factory::$document = clone $document;

        // add editor config
        $config->add('customizer', [
            'editor' => [
                'id' => 'editor-xtd',
                'title' => isset($plugin->name) ? $language->_($plugin->name) : 'Editor',
                'iframe' => Url::route('theme/editor', ['format' => 'html', 'tmpl' => 'component']),
                'buttons' => array_filter(
                    $editor->getButtons('editor-xtd', ['pagebreak', 'readmore', 'widgetkit']),
                    function ($button) {
                        return !empty($button->modal);
                    }
                ),
                'settings' => static::loadSettings() + [
                    'branding' => false,
                    'content_css' => version_compare(JVERSION, '4.0', '<')
                        ? "{$root}templates/system/css/editor.css"
                        : "{$root}media/system/css/editor.min.css",
                    'directionality' => $config('locale.rtl') ? 'rtl' : 'ltr',
                    'document_base_url' => $root,
                    'entity_encoding' => 'raw',
                    'insert_button_items' => '', // e.g. 'hr charmap',
                    'plugins' => 'link autolink hr lists charmap paste',
                    'toolbar1' =>
                        'formatselect bold italic bullist numlist blockquote alignleft aligncenter alignright link insert strikethrough hr pastetext removeformat charmap outdent indent',
                ],
            ],
        ]);

        // Recover document
        Factory::$document = $document;
    }

    public static function loadSettings()
    {
        $tinymce = PluginHelper::getPlugin('editors', 'tinymce');
        $params = $tinymce ? json_decode($tinymce->params, true) : [];

        if (!empty($params['newlines'])) {
            $settings = [
                'forced_root_block' => '',
                'force_p_newlines' => false,
                'force_br_newlines' => true,
            ];
        } else {
            $settings = [
                'forced_root_block' => 'p',
                'force_p_newlines' => true,
                'force_br_newlines' => false,
            ];
        }

        return $settings;
    }

    public static function renderEditor(Config $config, HtmlDocument $document)
    {
        $type = $config('joomla.config.editor');
        $editor = Editor::getInstance($type);
        $exclude = ['pagebreak', 'readmore', 'widgetkit'];

        // core.js needs to initialize Joomla.editors early
        HTMLHelper::_('behavior.core');

        ob_start();

        echo "<form>{$editor->display('content', '', '100%', '550', '', '30', $exclude)}</form>";

        $document->setBuffer(ob_get_clean(), [
            'type' => 'component',
            'name' => null,
            'title' => null,
        ]);
    }
}
