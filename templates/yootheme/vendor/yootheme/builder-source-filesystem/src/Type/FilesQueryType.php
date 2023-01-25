<?php

namespace YOOtheme\Builder\Source\Filesystem\Type;

use function YOOtheme\app;
use YOOtheme\Builder\Source\Filesystem\FileHelper;
use function YOOtheme\trans;

class FilesQueryType
{
    /**
     * @param string $rootDir
     *
     * @return array
     */
    public static function config($rootDir)
    {
        return [
            'fields' => [
                'files' => [
                    'type' => [
                        'listOf' => 'File',
                    ],

                    'args' => [
                        'pattern' => [
                            'type' => 'String',
                        ],
                        'offset' => [
                            'type' => 'Int',
                        ],
                        'limit' => [
                            'type' => 'Int',
                        ],
                        'order' => [
                            'type' => 'String',
                        ],
                        'order_direction' => [
                            'type' => 'String',
                        ],
                    ],

                    'metadata' => [
                        'label' => trans('Files'),
                        'group' => trans('External'),
                        'fields' => [
                            'pattern' => [
                                'label' => trans('Path Pattern'),
                                'description' => "Pick a folder to load file content dynamically. Alternatively, set a path <a href=\"https://www.php.net/manual/en/function.glob.php\" target=\"_blank\">glob pattern</a> to filter files. For example <code>{$rootDir}/*.{jpg,png}</code>. The path is relative to the system folder and has to be a subdirectory of <code>{$rootDir}</code>.",
                                'type' => 'select-file',
                            ],
                            '_offset' => [
                                'description' => trans(
                                    'Set the starting point and limit the number of files.'
                                ),
                                'type' => 'grid',
                                'width' => '1-2',
                                'fields' => [
                                    'offset' => [
                                        'label' => trans('Start'),
                                        'type' => 'number',
                                        'default' => 0,
                                        'modifier' => 1,
                                        'attrs' => [
                                            'min' => 1,
                                            'required' => true,
                                        ],
                                    ],
                                    'limit' => [
                                        'label' => trans('Quantity'),
                                        'type' => 'limit',
                                        'default' => 10,
                                        'attrs' => [
                                            'min' => 1,
                                        ],
                                    ],
                                ],
                            ],
                            '_order' => [
                                'type' => 'grid',
                                'width' => '1-2',
                                'description' => trans(
                                    'The Default order will follow the order set by the brackets or fallback to the default files order set by the system.'
                                ),
                                'fields' => [
                                    'order' => [
                                        'label' => trans('Order'),
                                        'type' => 'select',
                                        'default' => 'name',
                                        'options' => [
                                            trans('Default') => 'default',
                                            trans('Alphabetical') => 'name',
                                            trans('Random') => 'rand',
                                        ],
                                    ],
                                    'order_direction' => [
                                        'label' => trans('Direction'),
                                        'type' => 'select',
                                        'default' => 'ASC',
                                        'options' => [
                                            trans('Ascending') => 'ASC',
                                            trans('Descending') => 'DESC',
                                        ],
                                        'enable' => 'order != "rand"',
                                    ],
                                ],
                            ],
                        ],
                    ],

                    'extensions' => [
                        'call' => __CLASS__ . '::resolve',
                    ],
                ],
            ],
        ];
    }

    public static function resolve($root, array $args)
    {
        return app(FileHelper::class)->query($args);
    }
}
