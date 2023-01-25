<?php

namespace YOOtheme;

return [

    'theme' => function () {

        return [

            'name' => 'YOOtheme',

            'version' => '3.0.21',

            'url' => Url::to(__DIR__),

            'rootDir' => __DIR__,

            'menus' => [
                'navbar' => 'Navbar',
                'header' => 'Header',
                'toolbar-left' => 'Toolbar Left',
                'toolbar-right' => 'Toolbar Right',
                'dialog' => 'Dialog',
                'navbar-mobile' => 'Mobile Navbar',
                'header-mobile' => 'Mobile Header',
                'dialog-mobile' => 'Mobile Dialog',
            ],

            'positions' => [
                'toolbar-left' => 'Toolbar Left',
                'toolbar-right' => 'Toolbar Right',
                'logo' => 'Logo',
                'navbar' => 'Navbar',
                'header' => 'Header',
                'dialog' => 'Dialog',
                'logo-mobile' => 'Mobile Logo',
                'navbar-mobile' => 'Mobile Navbar',
                'header-mobile' => 'Mobile Header',
                'dialog-mobile' => 'Mobile Dialog',
                'top' => 'Top',
                'sidebar' => 'Sidebar',
                'bottom' => 'Bottom',
                'builder-1' => 'Builder 1',
                'builder-2' => 'Builder 2',
                'builder-3' => 'Builder 3',
                'builder-4' => 'Builder 4',
                'builder-5' => 'Builder 5',
                'builder-6' => 'Builder 6',
            ],

            'styles' => [

                'imports' => [
                    Path::get('./vendor/assets/uikit/src/images/backgrounds/*.svg'),
                    Path::get('./vendor/assets/uikit-themes/master/images/*.svg'),
                ],

            ],

        ];

    },

    'config' => function () {

        return [

            'image' => [
                'cacheDir' => Path::get('./cache'),
            ],

        ];

    },

];
