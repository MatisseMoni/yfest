<?php

namespace YOOtheme;

if ($props['content'] == '') {
    return;
}

// Content
echo $this->el('div', [

    'class' => [
        'el-content uk-panel',
        'uk-text-{content_style}',
        'uk-dropcap {@content_dropcap}',
        'uk-column-{content_column}[@{content_column_breakpoint}]',
        'uk-column-divider {@content_column} {@content_column_divider}',
        'uk-margin[-{content_margin}]-top {@!content_margin: remove} {@image_align: top}' => $props['image'],
    ],

])->render($element, $props['content']);
