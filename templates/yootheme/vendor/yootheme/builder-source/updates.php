<?php

namespace YOOtheme;

return [
    '3.0.0-beta.1.2' => function ($node) {
        if (isset($node->source->query->name) && empty($node->source->query->name)) {
            unset($node->source);
        }
    },
];
