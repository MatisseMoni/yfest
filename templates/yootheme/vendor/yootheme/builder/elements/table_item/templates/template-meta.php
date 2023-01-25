<?php

if (!$props['meta']) {
    return;
}

// Meta
$el = $this->el('div', [

    'class' => [
        'el-meta',
        'uk-{meta_style} [uk-margin-remove {@meta_style: h1|h2|h3|h4|h5|h6}]',
        'uk-text-{meta_color}',
    ],

]);

echo $el($element, $props['meta']);
