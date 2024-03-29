<?php // $file = C:/wamp64/www/yfest/templates/yootheme/vendor/yootheme/builder/elements/nav_item/element.json

return [
  '@import' => $filter->apply('path', './element.php', $file), 
  'name' => 'nav_item', 
  'title' => 'Item', 
  'width' => 500, 
  'placeholder' => [
    'props' => [
      'content' => 'Item'
    ]
  ], 
  'templates' => [
    'render' => $filter->apply('path', './templates/template.php', $file), 
    'content' => $filter->apply('path', './templates/content.php', $file)
  ], 
  'fields' => [
    'content' => [
      'label' => 'Content', 
      'source' => true
    ], 
    'meta' => [
      'label' => 'Subtitle', 
      'source' => true
    ], 
    'image' => [
      'label' => 'Image', 
      'type' => 'image', 
      'source' => true, 
      'enable' => '!icon', 
      'altRef' => '%name%_alt'
    ], 
    'image_alt' => [
      'label' => 'Image Alt', 
      'source' => true, 
      'enable' => 'image'
    ], 
    'icon' => [
      'label' => 'Icon', 
      'description' => 'Instead of using a custom image, you can click on the pencil to pick an icon from the icon library.', 
      'type' => 'icon', 
      'source' => true, 
      'enable' => '!image'
    ], 
    'link' => [
      'label' => 'Link', 
      'type' => 'link', 
      'description' => 'Enter or pick a link, an image or a video file.', 
      'attrs' => [
        'placeholder' => 'http://'
      ], 
      'source' => true
    ], 
    'link_target' => [
      'type' => 'checkbox', 
      'text' => 'Open the link in a new window'
    ], 
    'type' => [
      'label' => 'Type', 
      'description' => 'Select the item type.', 
      'type' => 'select', 
      'options' => [
        'Item' => '', 
        'Heading' => 'heading', 
        'Divider' => 'divider'
      ], 
      'source' => true
    ], 
    'active' => [
      'label' => 'Active', 
      'description' => 'Highlight the item as the active item.', 
      'type' => 'checkbox', 
      'text' => 'Enable active state', 
      'source' => true, 
      'enable' => '!$match(type, \'divider|header\')'
    ], 
    'status' => $config->get('builder.statusItem'), 
    'source' => $config->get('builder.source')
  ], 
  'fieldset' => [
    'default' => [
      'type' => 'tabs', 
      'fields' => [[
          'title' => 'Content', 
          'fields' => ['content', 'meta', 'image', 'image_alt', 'icon', 'link', 'link_target']
        ], [
          'title' => 'Settings', 
          'fields' => [[
              'label' => 'Item', 
              'type' => 'group', 
              'fields' => ['type', 'active']
            ]]
        ], $config->get('builder.advancedItem')]
    ]
  ]
];
