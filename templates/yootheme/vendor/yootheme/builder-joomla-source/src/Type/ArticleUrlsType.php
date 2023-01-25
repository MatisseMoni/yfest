<?php

namespace YOOtheme\Builder\Joomla\Source\Type;

class ArticleUrlsType
{
    /**
     * @return array
     */
    public static function config()
    {
        $fields = [];

        foreach (['a', 'b', 'c'] as $letter) {
            $fields["url{$letter}"] = [
                'type' => 'String',
                'metadata' => [
                    'label' => ucfirst($letter),
                ],
                'extensions' => [
                    'call' => __CLASS__ . '::resolve',
                ],
            ];

            $fields["url{$letter}text"] = [
                'type' => 'String',
                'metadata' => [
                    'label' => ucfirst($letter) . ' Text',
                    'filters' => ['limit'],
                ],
            ];
        }

        return compact('fields');
    }

    public static function resolve($item, $args, $context, $info)
    {
        return $item->{$info->fieldName} ?: '';
    }
}
