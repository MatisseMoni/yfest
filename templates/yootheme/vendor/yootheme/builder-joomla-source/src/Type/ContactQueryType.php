<?php

namespace YOOtheme\Builder\Joomla\Source\Type;

use function YOOtheme\trans;

class ContactQueryType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [
            'fields' => [
                'contact' => [
                    'type' => 'Contact',

                    'metadata' => [
                        'group' => trans('Page'),
                        'label' => trans('Contact'),
                        'view' => ['com_contact.contact'],
                    ],

                    'extensions' => [
                        'call' => __CLASS__ . '::resolve',
                    ],
                ],
            ],
        ];
    }

    public static function resolve($root)
    {
        if (isset($root['item'])) {
            return $root['item'];
        }
    }
}
