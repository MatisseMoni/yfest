<?php // $file = C:/wamp64/www/yfest/templates/yootheme/vendor/yootheme/builder/elements/subnav_item/element.json

return [
  '@import' => $filter->apply('path', './element.php', $file), 
  'name' => 'subnav_item', 
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
    'link' => $config->get('builder.link'), 
    'link_target' => $config->get('builder.link_target'), 
    'active' => [
      'label' => 'Active', 
      'type' => 'checkbox', 
      'text' => 'Active item', 
      'source' => true
    ], 
    'status' => $config->get('builder.statusItem'), 
    'source' => $config->get('builder.source')
  ], 
  'fieldset' => [
    'default' => [
      'type' => 'tabs', 
      'fields' => [[
          'title' => 'Content', 
          'fields' => ['content', 'link', 'link_target', 'active']
        ], $config->get('builder.advancedItem')]
    ]
  ]
];
