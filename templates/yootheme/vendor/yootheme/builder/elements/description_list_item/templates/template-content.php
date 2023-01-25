<?php

namespace YOOtheme;

if ($props['content'] == '') {
    return;
}

// Content
$content = $this->el('div', [

    'class' => [
        'el-content uk-panel',
        'uk-{content_style} [uk-margin-remove {@content_style: h1|h2|h3|h4|h5|h6}]',
    ],

]);

// Link
$link = $this->el('a', [
    'class' => [
        'uk-link-{0}' => $element['link_style'],
        'uk-margin-remove-last-child',
    ],
    'href' => ['{link}'],
    'target' => ['_blank {@link_target}'],
    'uk-scroll' => str_contains((string) $props['link'], '#'),
]);

echo $content($element, $props['link'] ? $link($props, $props['content']) : $props['content']);
