<?php // $file = C:/wamp64/www/yfest/templates/yootheme/vendor/yootheme/builder/elements/fragment/element.json

return [
  'name' => 'fragment', 
  'title' => 'Sublayout', 
  'group' => 'basic', 
  'icon' => $filter->apply('url', 'images/icon.svg', $file), 
  'iconSmall' => $filter->apply('url', 'images/iconSmall.svg', $file), 
  'element' => true, 
  'container' => true, 
  'width' => 500, 
  'defaults' => [], 
  'placeholder' => [], 
  'templates' => [
    'render' => $filter->apply('path', './templates/template.php', $file), 
    'content' => $filter->apply('path', './templates/content.php', $file)
  ], 
  'fields' => [], 
  'fieldset' => []
];
