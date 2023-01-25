<?php // $file = C:/wamp64/www/yfest/templates/yootheme/vendor/yootheme/builder-joomla/elements/module/element.json

return [
  '@import' => $filter->apply('path', './element.php', $file), 
  'name' => 'module', 
  'title' => 'Module', 
  'group' => 'system', 
  'icon' => $filter->apply('url', 'images/icon.svg', $file), 
  'iconSmall' => $filter->apply('url', 'images/iconSmall.svg', $file), 
  'element' => true, 
  'width' => 500, 
  'defaults' => [
    'menu_style' => 'default', 
    'menu_image_margin' => true, 
    'menu_image_align' => 'center'
  ], 
  'templates' => [
    'render' => $filter->apply('path', './templates/template.php', $file)
  ], 
  'fields' => [
    'module' => [
      'type' => 'select-module', 
      'label' => 'Module', 
      'description' => 'Any Joomla module can be displayed in your custom layout.'
    ], 
    'style' => [
      'type' => 'select', 
      'label' => 'Style', 
      'description' => 'Select a panel style.', 
      'options' => [
        'None' => '', 
        'Card Default' => 'card-default', 
        'Card Primary' => 'card-primary', 
        'Card Secondary' => 'card-secondary', 
        'Card Hover' => 'card-hover'
      ]
    ], 
    'title_style' => [
      'type' => 'select', 
      'label' => 'Style', 
      'description' => 'Title styles differ in font-size but may also come with a predefined color, size and font.', 
      'options' => [
        'None' => '', 
        'Heading 2X-Large' => 'heading-2xlarge', 
        'Heading X-Large' => 'heading-xlarge', 
        'Heading Large' => 'heading-large', 
        'Heading Medium' => 'heading-medium', 
        'Heading Small' => 'heading-small', 
        'Heading H1' => 'h1', 
        'Heading H2' => 'h2', 
        'Heading H3' => 'h3', 
        'Heading H4' => 'h4', 
        'Heading H5' => 'h5', 
        'Heading H6' => 'h6', 
        'Text Meta' => 'meta', 
        'Text Lead' => 'lead'
      ]
    ], 
    'title_decoration' => [
      'type' => 'select', 
      'label' => 'Decoration', 
      'description' => 'Decorate the title with a divider, bullet or a line that is vertically centered to the heading.', 
      'options' => [
        'None' => '', 
        'Divider' => 'divider', 
        'Bullet' => 'bullet', 
        'Line' => 'line'
      ]
    ], 
    'title_font_family' => [
      'label' => 'Font Family', 
      'description' => 'Select an alternative font family. Mind that not all styles have different font families.', 
      'type' => 'select', 
      'options' => [
        'None' => '', 
        'Default' => 'default', 
        'Primary' => 'primary', 
        'Secondary' => 'secondary', 
        'Tertiary' => 'tertiary'
      ]
    ], 
    'title_color' => [
      'type' => 'select', 
      'label' => 'Color', 
      'description' => 'Select the text color. If the background option is selected, styles that don\'t apply a background image use the primary color instead.', 
      'options' => [
        'None' => '', 
        'Muted' => 'muted', 
        'Primary' => 'primary', 
        'Success' => 'success', 
        'Warning' => 'warning', 
        'Danger' => 'danger', 
        'Background' => 'background'
      ]
    ], 
    'list_style' => [
      'label' => 'List Style', 
      'description' => 'Select the list style.', 
      'type' => 'select', 
      'options' => [
        'None' => '', 
        'Divider' => 'divider'
      ], 
      'enable' => '$match(type, \'articles_archive|articles_categories|articles_latest|articles_popular|tags_popular|tags_similar\')'
    ], 
    'link_style' => [
      'label' => 'Link Style', 
      'description' => 'Select the link style.', 
      'type' => 'select', 
      'options' => [
        'None' => '', 
        'Muted' => 'muted'
      ], 
      'enable' => '$match(type, \'articles_archive|articles_categories|articles_latest|articles_popular|tags_popular|tags_similar\')'
    ], 
    'menu_type' => [
      'label' => 'Type', 
      'description' => 'Select the menu type.', 
      'type' => 'select', 
      'default' => 'nav', 
      'options' => [
        'Nav' => 'nav', 
        'Subnav' => 'subnav', 
        'Iconnav' => 'iconnav'
      ], 
      'enable' => '$match(type, \'menu\')'
    ], 
    'menu_divider' => [
      'label' => 'Divider', 
      'description' => 'Show optional dividers between nav or subnav items.', 
      'type' => 'checkbox', 
      'text' => 'Show dividers', 
      'enable' => '$match(menu_type, \'nav|subnav\') && $match(type, \'menu\')'
    ], 
    'menu_style' => [
      'label' => 'Style', 
      'description' => 'Select the nav style.', 
      'type' => 'select', 
      'options' => [
        'Default' => 'default', 
        'Primary' => 'primary', 
        'Secondary' => 'secondary'
      ], 
      'enable' => '$match(menu_type, \'nav\') && $match(type, \'menu\')'
    ], 
    'menu_image_width' => [
      'attrs' => [
        'placeholder' => 'auto'
      ], 
      'enable' => '$match(type, \'menu\')'
    ], 
    'menu_image_height' => [
      'attrs' => [
        'placeholder' => 'auto'
      ], 
      'enable' => '$match(type, \'menu\')'
    ], 
    'menu_image_svg_inline' => [
      'label' => 'Inline SVG', 
      'description' => 'Inject SVG images into the markup so they adopt the text color automatically.', 
      'type' => 'checkbox', 
      'text' => 'Make SVG stylable with CSS', 
      'enable' => '$match(type, \'menu\')'
    ], 
    'menu_icon_width' => [
      'label' => 'Icon Width', 
      'description' => 'Set the icon width.', 
      'enable' => '$match(type, \'menu\')'
    ], 
    'menu_image_margin' => [
      'label' => 'Image and Title', 
      'type' => 'checkbox', 
      'text' => 'Add margin between', 
      'enable' => '$match(type, \'menu\')'
    ], 
    'menu_image_align' => [
      'label' => 'Image Align', 
      'type' => 'select', 
      'options' => [
        'Top' => 'top', 
        'Center' => 'center'
      ], 
      'enable' => '$match(type, \'menu\')'
    ], 
    'position' => $config->get('builder.position'), 
    'position_left' => $config->get('builder.position_left'), 
    'position_right' => $config->get('builder.position_right'), 
    'position_top' => $config->get('builder.position_top'), 
    'position_bottom' => $config->get('builder.position_bottom'), 
    'position_z_index' => $config->get('builder.position_z_index'), 
    'margin' => $config->get('builder.margin'), 
    'margin_remove_top' => $config->get('builder.margin_remove_top'), 
    'margin_remove_bottom' => $config->get('builder.margin_remove_bottom'), 
    'maxwidth' => $config->get('builder.maxwidth'), 
    'maxwidth_breakpoint' => $config->get('builder.maxwidth_breakpoint'), 
    'block_align' => $config->get('builder.block_align'), 
    'block_align_breakpoint' => $config->get('builder.block_align_breakpoint'), 
    'block_align_fallback' => $config->get('builder.block_align_fallback'), 
    'text_align' => $config->get('builder.text_align_justify'), 
    'text_align_breakpoint' => $config->get('builder.text_align_breakpoint'), 
    'text_align_fallback' => $config->get('builder.text_align_justify_fallback'), 
    'animation' => $config->get('builder.animation'), 
    '_parallax_button' => $config->get('builder._parallax_button'), 
    'visibility' => $config->get('builder.visibility'), 
    'name' => $config->get('builder.name'), 
    'status' => $config->get('builder.status'), 
    'id' => $config->get('builder.id'), 
    'class' => $config->get('builder.cls'), 
    'attributes' => $config->get('builder.attrs'), 
    'css' => [
      'label' => 'CSS', 
      'description' => 'Enter your own custom CSS. The following selectors will be prefixed automatically for this element: <code>.el-element</code>, <code>.el-title</code>', 
      'type' => 'editor', 
      'editor' => 'code', 
      'mode' => 'css', 
      'attrs' => [
        'debounce' => 500
      ]
    ]
  ], 
  'fieldset' => [
    'default' => [
      'type' => 'tabs', 
      'fields' => [[
          'title' => 'Content', 
          'fields' => ['module']
        ], [
          'title' => 'Settings', 
          'fields' => [[
              'label' => 'Panel', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['style']
            ], [
              'label' => 'Title', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['title_style', 'title_decoration', 'title_font_family', 'title_color']
            ], [
              'label' => 'List', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['list_style', 'link_style']
            ], [
              'label' => 'Menu', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['menu_type', 'menu_divider', 'menu_style', [
                  'label' => 'Image Width/Height', 
                  'description' => 'Setting just one value preserves the original proportions. The image will be resized and cropped automatically, and where possible, high resolution images will be auto-generated.', 
                  'type' => 'grid', 
                  'width' => '1-2', 
                  'fields' => ['menu_image_width', 'menu_image_height']
                ], 'menu_image_svg_inline', 'menu_icon_width', 'menu_image_margin', 'menu_image_align']
            ], [
              'label' => 'General', 
              'type' => 'group', 
              'fields' => ['position', 'position_left', 'position_right', 'position_top', 'position_bottom', 'position_z_index', 'margin', 'margin_remove_top', 'margin_remove_bottom', 'maxwidth', 'maxwidth_breakpoint', 'block_align', 'block_align_breakpoint', 'block_align_fallback', 'text_align', 'text_align_breakpoint', 'text_align_fallback', 'animation', '_parallax_button', 'visibility']
            ]]
        ], $config->get('builder.advanced')]
    ]
  ]
];
