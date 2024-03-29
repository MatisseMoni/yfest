<?php // $file = C:/wamp64/www/yfest/templates/yootheme/vendor/yootheme/builder/config/customizer.json

return [
  '@import' => [$filter->apply('path', '../../builder-source/config/customizer.json', $file), $filter->apply('path', sprintf('../../builder-%s/config/customizer.json', $config->get('app.platform')), $file)], 
  'sections' => [
    'builder' => [
      'title' => 'Builder', 
      'heading' => false, 
      'width' => 500, 
      'priority' => 20, 
      'prefix' => 'page#'
    ]
  ], 
  'panels' => [
    'builder-parallax' => [
      'title' => 'Parallax', 
      'width' => 500, 
      'fields' => [
        'parallax_x' => [
          'text' => 'Translate X', 
          'type' => 'parallax-stops', 
          'attrs' => [
            'min' => -600, 
            'max' => 600, 
            'step' => 10
          ]
        ], 
        'parallax_y' => [
          'text' => 'Translate Y', 
          'type' => 'parallax-stops', 
          'attrs' => [
            'min' => -600, 
            'max' => 600, 
            'step' => 10
          ]
        ], 
        'parallax_scale' => [
          'text' => 'Scale', 
          'type' => 'parallax-stops', 
          'attrs' => [
            'min' => 0.5, 
            'max' => 2, 
            'step' => 0.1
          ]
        ], 
        'parallax_rotate' => [
          'text' => 'Rotate', 
          'type' => 'parallax-stops', 
          'attrs' => [
            'min' => 0, 
            'max' => 360, 
            'step' => 10
          ]
        ], 
        'parallax_opacity' => [
          'text' => 'Opacity', 
          'type' => 'parallax-stops', 
          'attrs' => [
            'min' => 0, 
            'max' => 1, 
            'step' => 0.1
          ]
        ], 
        '_parallax_description' => [
          'description' => 'Animate properties to specific values. Add multiple stops to define start, intermediate and end values along the animation sequence for each property. Optionally, specify percentage to position the stops along the animation sequence. Translate and scale can have optional <code>%</code>, <code>vw</code> and <code>vh</code> units.', 
          'type' => 'description'
        ], 
        'parallax_transform_origin' => [
          'label' => 'Transform Origin', 
          'description' => 'Define the origin of the element\'s transformation when scaling or rotating the element.', 
          'type' => 'select', 
          'options' => [
            'Top Left' => 'top-left', 
            'Top Center' => 'top-center', 
            'Top Right' => 'top-right', 
            'Center Left' => 'center-left', 
            'Center Center' => '', 
            'Center Right' => 'center-right', 
            'Bottom Left' => 'bottom-left', 
            'Bottom Center' => 'bottom-center', 
            'Bottom Right' => 'bottom-right'
          ]
        ], 
        'parallax_easing' => [
          'label' => 'Easing', 
          'description' => 'Set the animation easing. Zero transitions at an even speed, a negative value starts off quickly while a positive value starts off slowly.', 
          'type' => 'range', 
          'attrs' => [
            'min' => -2, 
            'max' => 2, 
            'step' => 0.1
          ]
        ], 
        'parallax_target' => [
          'label' => 'Target', 
          'description' => 'The animation starts and stops depending on the element position in the viewport. Alternatively, use the position of a parent container.', 
          'type' => 'select', 
          'options' => [
            'Element' => '', 
            'Column' => '![uk-grid].tm-grid-expand>*', 
            'Row' => '![uk-grid].tm-grid-expand', 
            'Section' => '!.uk-section'
          ]
        ], 
        'parallax_start_end' => [
          'description' => 'The animation starts when the element enters the viewport and ends when it leaves the viewport. Optionally, set a start and end offset, e.g. <code>100px</code>, <code>50vh</code> or <code>50vh + 50%</code>. Percent relates to the target\'s height.', 
          'type' => 'grid', 
          'width' => '1-2', 
          'fields' => [
            'parallax_start' => [
              'label' => 'Start'
            ], 
            'parallax_end' => [
              'label' => 'End'
            ]
          ]
        ], 
        'parallax_zindex' => [
          'label' => 'Z Index', 
          'type' => 'checkbox', 
          'text' => 'Set a higher stacking order.'
        ], 
        'parallax_breakpoint' => [
          'label' => 'Breakpoint', 
          'description' => 'Display the parallax effect only on this device width and larger.', 
          'type' => 'select', 
          'options' => [
            'Always' => '', 
            'Small (Phone Landscape)' => 's', 
            'Medium (Tablet Landscape)' => 'm', 
            'Large (Desktop)' => 'l', 
            'X-Large (Large Screens)' => 'xl'
          ]
        ]
      ], 
      'help' => [
        'Builder' => [[
            'title' => 'Creating Parallax Effects', 
            'src' => 'https://www.youtube-nocookie.com/watch?v=PKVnGZjd0dE&amp;list=PLrqT0WH0HPdPfykSwhMt6Jl2_RgJ6ixU-', 
            'duration' => '9:59', 
            'documentation' => 'support/yootheme-pro/joomla/joomla/elements#parallax-settings', 
            'support' => 'support/search?tags=125&q=parallax'
          ], [
            'title' => 'Creating Sticky Parallax Effects', 
            'src' => 'https://www.youtube-nocookie.com/watch?v=_fykqti6DYc&amp;list=PLrqT0WH0HPdPfykSwhMt6Jl2_RgJ6ixU-', 
            'duration' => '19:00', 
            'documentation' => 'support/yootheme-pro/joomla/joomla/elements#sticky-parallax-settings', 
            'support' => 'support/search?tags=125&q=sticky%20parallax'
          ]]
      ]
    ]
  ]
];
