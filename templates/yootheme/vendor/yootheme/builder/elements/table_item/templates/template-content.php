<?php

if ($props['content'] == '') {
    return;
}

// Content
$el = $this->el('div', [

    'class' => [
        'el-content uk-panel',
        'uk-{content_style} [uk-margin-remove {@content_style: h1|h2|h3|h4|h5|h6}]',
    ],

]);

echo $el($element, $props['content']);
