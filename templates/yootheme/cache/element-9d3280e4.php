<?php // $file = C:/wamp64/www/yfest/templates/yootheme/vendor/yootheme/builder/elements/column/element.json

return [
  '@import' => $filter->apply('path', './element.php', $file), 
  'name' => 'column', 
  'title' => 'Column', 
  'container' => true, 
  'width' => 500, 
  'defaults' => [
    'position_sticky_breakpoint' => 'm', 
    'image_position' => 'center-center', 
    'media_overlay_gradient' => ''
  ], 
  'templates' => [
    'render' => $filter->apply('path', './templates/template.php', $file), 
    'content' => $filter->apply('path', './templates/content.php', $file)
  ], 
  'fields' => [
    'image' => [
      'label' => 'Image', 
      'description' => 'Upload a background image.', 
      'type' => 'image', 
      'source' => true, 
      'show' => '!video'
    ], 
    'video' => [
      'label' => 'Video', 
      'description' => 'Select a video file or enter a link from <a href="https://www.youtube.com" target="_blank">YouTube</a> or <a href="https://vimeo.com" target="_blank">Vimeo</a>.', 
      'type' => 'video', 
      'source' => true, 
      'show' => '!image'
    ], 
    '_media' => [
      'type' => 'button-panel', 
      'panel' => 'builder-column-media', 
      'text' => 'Edit Settings', 
      'show' => 'image || video'
    ], 
    'vertical_align' => [
      'label' => 'Vertical Alignment', 
      'description' => 'Vertically align the elements in the column.', 
      'type' => 'select', 
      'options' => [
        'Top' => '', 
        'Middle' => 'middle', 
        'Bottom' => 'bottom'
      ]
    ], 
    'style' => [
      'label' => 'Style', 
      'type' => 'select', 
      'options' => [
        'None' => '', 
        'Default' => 'default', 
        'Muted' => 'muted', 
        'Primary' => 'primary', 
        'Secondary' => 'secondary'
      ]
    ], 
    'preserve_color' => [
      'description' => 'Preserve the text color, for example when using cards.', 
      'type' => 'checkbox', 
      'text' => 'Preserve text color', 
      'enable' => '$match(style, \'primary|secondary\')'
    ], 
    'text_color' => [
      'label' => 'Text Color', 
      'description' => 'Force a light or dark color for text, buttons and controls on the image or video background.', 
      'type' => 'select', 
      'options' => [
        'None' => '', 
        'Light' => 'light', 
        'Dark' => 'dark'
      ], 
      'source' => true, 
      'enable' => '!style || image || video'
    ], 
    'padding' => [
      'label' => 'Padding', 
      'description' => 'Set the padding.', 
      'type' => 'select', 
      'options' => [
        'Default' => '', 
        'X-Small' => 'xsmall', 
        'Small' => 'small', 
        'Large' => 'large', 
        'X-Large' => 'xlarge', 
        'None' => 'none'
      ], 
      'enable' => 'style || image || video'
    ], 
    'position_sticky' => [
      'label' => 'Position Sticky', 
      'description' => 'Stick the column or its elements to the top of the viewport while scrolling down. They will stop being sticky when they reach the bottom of the containing column, row or section.', 
      'type' => 'select', 
      'options' => [
        'None' => '', 
        'Elements within Column' => 'column', 
        'Column within Row' => 'row', 
        'Column within Section' => 'section'
      ]
    ], 
    'position_sticky_offset' => [
      'label' => 'Position Sticky Offset', 
      'description' => 'Set the offset from the top of the viewport where the column or its elements should become sticky.', 
      'attrs' => [
        'placeholder' => '0'
      ], 
      'enable' => 'position_sticky'
    ], 
    'position_sticky_breakpoint' => [
      'label' => 'Position Sticky Breakpoint', 
      'description' => 'Make the column or its elements sticky only from this device width and larger.', 
      'type' => 'select', 
      'options' => [
        'Always' => '', 
        'Small (Phone Landscape)' => 's', 
        'Medium (Tablet Landscape)' => 'm', 
        'Large (Desktop)' => 'l', 
        'X-Large (Large Screens)' => 'xl'
      ], 
      'enable' => 'position_sticky'
    ], 
    'source' => $config->get('builder.source'), 
    'id' => $config->get('builder.id'), 
    'class' => $config->get('builder.cls'), 
    'attributes' => $config->get('builder.attrs'), 
    'css' => [
      'label' => 'CSS', 
      'description' => 'Enter your own custom CSS. The following selectors will be prefixed automatically for this element: <code>.el-column</code>', 
      'type' => 'editor', 
      'editor' => 'code', 
      'mode' => 'css', 
      'attrs' => [
        'debounce' => 500, 
        'hints' => ['.el-column']
      ]
    ]
  ], 
  'fieldset' => [
    'default' => [
      'type' => 'tabs', 
      'fields' => [[
          'title' => 'Content', 
          'fields' => ['image', 'video', '_media']
        ], [
          'title' => 'Settings', 
          'fields' => ['vertical_align', 'style', 'preserve_color', 'text_color', 'padding', 'position_sticky', 'position_sticky_offset', 'position_sticky_breakpoint']
        ], [
          'title' => 'Advanced', 
          'fields' => ['source', 'id', 'class', 'attributes', 'css']
        ]]
    ]
  ], 
  'panels' => [
    'builder-column-media' => [
      'title' => 'Image/Video', 
      'width' => 500, 
      'fields' => [
        'image_width' => [
          'label' => 'Width', 
          'attrs' => [
            'placeholder' => 'auto'
          ]
        ], 
        'image_height' => [
          'label' => 'Height', 
          'attrs' => [
            'placeholder' => 'auto'
          ]
        ], 
        'image_loading' => [
          'label' => 'Loading', 
          'description' => 'By default, images are loaded lazy. Enable eager loading for images in the initial viewport.', 
          'type' => 'checkbox', 
          'text' => 'Load image eagerly', 
          'show' => 'image && !video'
        ], 
        'image_size' => [
          'label' => 'Image Size', 
          'description' => 'Determine whether the image will fit the section dimensions by clipping it or by filling the empty areas with the background color.', 
          'type' => 'select', 
          'options' => [
            'Auto' => '', 
            'Cover' => 'cover', 
            'Contain' => 'contain', 
            'Width 100%' => 'width-1-1', 
            'Height 100%' => 'height-1-1'
          ], 
          'show' => 'image && !video'
        ], 
        'image_position' => [
          'label' => 'Image Position', 
          'description' => 'Set the initial background position, relative to the section layer.', 
          'type' => 'select', 
          'options' => [
            'Top Left' => 'top-left', 
            'Top Center' => 'top-center', 
            'Top Right' => 'top-right', 
            'Center Left' => 'center-left', 
            'Center Center' => 'center-center', 
            'Center Right' => 'center-right', 
            'Bottom Left' => 'bottom-left', 
            'Bottom Center' => 'bottom-center', 
            'Bottom Right' => 'bottom-right'
          ], 
          'show' => 'image && !video'
        ], 
        'image_effect' => [
          'label' => 'Image Effect', 
          'description' => 'Add a parallax effect or fix the background with regard to the viewport while scrolling.', 
          'type' => 'select', 
          'options' => [
            'None' => '', 
            'Parallax' => 'parallax', 
            'Fixed' => 'fixed'
          ], 
          'show' => 'image && !video'
        ], 
        'image_parallax_bgx' => [
          'text' => 'Translate X', 
          'type' => 'parallax-stops', 
          'attrs' => [
            'min' => -600, 
            'max' => 600, 
            'step' => 10
          ], 
          'show' => 'image_effect == \'parallax\' && image && !video'
        ], 
        'image_parallax_bgy' => [
          'text' => 'Translate Y', 
          'type' => 'parallax-stops', 
          'attrs' => [
            'min' => -600, 
            'max' => 600, 
            'step' => 10
          ], 
          'show' => 'image_effect == \'parallax\' && image && !video'
        ], 
        'image_parallax_easing' => [
          'label' => 'Parallax Easing', 
          'description' => 'Set the animation easing. Zero transitions at an even speed, a negative value starts off quickly while a positive value starts off slowly.', 
          'type' => 'range', 
          'attrs' => [
            'min' => -2, 
            'max' => 2, 
            'step' => 0.1
          ], 
          'show' => 'image_effect == \'parallax\' && image && !video'
        ], 
        'image_parallax_breakpoint' => [
          'label' => 'Parallax Breakpoint', 
          'description' => 'Display the parallax effect only on this device width and larger.', 
          'type' => 'select', 
          'options' => [
            'Always' => '', 
            'Small (Phone Landscape)' => 's', 
            'Medium (Tablet Landscape)' => 'm', 
            'Large (Desktop)' => 'l', 
            'X-Large (Large Screens)' => 'xl'
          ], 
          'show' => 'image_effect == \'parallax\' && image && !video'
        ], 
        'media_visibility' => [
          'label' => 'Visibility', 
          'description' => 'Display the image only on this device width and larger.', 
          'type' => 'select', 
          'options' => [
            'Always' => '', 
            'Small (Phone Landscape)' => 's', 
            'Medium (Tablet Landscape)' => 'm', 
            'Large (Desktop)' => 'l', 
            'X-Large (Large Screens)' => 'xl'
          ]
        ], 
        'media_background' => [
          'label' => 'Background Color', 
          'description' => 'Use the background color in combination with blend modes, a transparent image or to fill the area, if the image doesn\'t cover the whole section.', 
          'type' => 'color'
        ], 
        'media_blend_mode' => [
          'label' => 'Blend Mode', 
          'description' => 'Determine how the image or video will blend with the background color.', 
          'type' => 'select', 
          'options' => [
            'Normal' => '', 
            'Multiply' => 'multiply', 
            'Screen' => 'screen', 
            'Overlay' => 'overlay', 
            'Darken' => 'darken', 
            'Lighten' => 'lighten', 
            'Color-dodge' => 'color-dodge', 
            'Color-burn' => 'color-burn', 
            'Hard-light' => 'hard-light', 
            'Soft-light' => 'soft-light', 
            'Difference' => 'difference', 
            'Exclusion' => 'exclusion', 
            'Hue' => 'hue', 
            'Saturation' => 'saturation', 
            'Color' => 'color', 
            'Luminosity' => 'luminosity'
          ]
        ], 
        'media_overlay' => [
          'label' => 'Overlay Color', 
          'description' => 'Set an additional transparent overlay to soften the image or video.', 
          'type' => 'gradient', 
          'internal' => 'media_overlay_gradient'
        ]
      ], 
      'fieldset' => [
        'default' => [
          'fields' => [[
              'description' => 'Set the width and height in pixels. Setting just one value preserves the original proportions. The image will be resized and cropped automatically.', 
              'name' => '_image_dimension', 
              'type' => 'grid', 
              'width' => '1-2', 
              'show' => 'image && !video', 
              'fields' => ['image_width', 'image_height']
            ], 'image_loading', 'image_size', 'image_position', 'image_effect', 'image_parallax_bgx', 'image_parallax_bgy', 'image_parallax_easing', 'image_parallax_breakpoint', 'media_visibility', 'media_background', 'media_blend_mode', 'media_overlay']
        ]
      ]
    ]
  ]
];
