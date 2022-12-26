<?php // $file = C:/xampp/htdocs/joomla/joomla4/plugins/system/protostore_offlinepay/modules/offlinepay/elements/protostore_offlinepay/element.json

return [
  '@import' => $filter->apply('path', './element.php', $file), 
  'name' => 'protostore_offlinepay', 
  'title' => 'Offline Pay', 
  'icon' => $filter->apply('url', 'images/protostore_offlinepay.svg', $file), 
  'iconSmall' => $filter->apply('url', 'images/protostore_offlinepay_small.svg', $file), 
  'element' => true, 
  'container' => true, 
  'group' => 'Pro2Store Payment', 
  'defaults' => [
    'button_text' => 'Complete Purchase', 
    'button_processing_text' => 'Processing', 
    'button_complete_text' => 'Complete', 
    'button_style' => 'default', 
    'icon_align' => 'left'
  ], 
  'placeholder' => [
    'props' => [
      'content' => 'Complete Purchase', 
      'icon' => ''
    ]
  ], 
  'templates' => [
    'render' => $filter->apply('path', './templates/template.php', $file), 
    'content' => $filter->apply('path', './templates/content.php', $file)
  ], 
  'fields' => [
    'button_text' => [
      'label' => 'Button Text', 
      'type' => 'text'
    ], 
    'button_processing_text' => [
      'label' => 'Button Processing Text', 
      'type' => 'text'
    ], 
    'button_complete_text' => [
      'label' => 'Button Complete Text', 
      'type' => 'text'
    ], 
    'icon' => [
      'label' => 'Icon', 
      'description' => 'Pick an optional icon.', 
      'type' => 'icon'
    ], 
    'icon_width' => [
      'label' => 'Icon Width', 
      'description' => 'Enter width value in pixels', 
      'type' => 'text'
    ], 
    'icon_align' => [
      'label' => 'Icon Alignment', 
      'description' => 'Choose the icon position.', 
      'type' => 'select', 
      'options' => [
        'Left' => 'left', 
        'Right' => 'right'
      ], 
      'enable' => 'icon'
    ], 
    'complete_icon' => [
      'label' => 'Show Check Icon', 
      'description' => 'Choose if you want to see a \'check\' icon when the order completes.', 
      'type' => 'checkbox', 
      'text' => 'Show Check icon on completion'
    ], 
    'button_style' => [
      'label' => 'Style', 
      'description' => 'Set the button style.', 
      'type' => 'select', 
      'options' => [
        'Default' => 'default', 
        'Primary' => 'primary', 
        'Secondary' => 'secondary', 
        'Danger' => 'danger', 
        'Text' => 'text', 
        'Link' => '', 
        'Link Muted' => 'link-muted', 
        'Link Text' => 'link-text'
      ]
    ], 
    'button_size' => [
      'label' => 'Size', 
      'type' => 'select', 
      'options' => [
        'Small' => 'small', 
        'Default' => '', 
        'Large' => 'large'
      ]
    ], 
    'fullwidth' => [
      'type' => 'checkbox', 
      'text' => 'Full width button'
    ], 
    'margin' => $config->get('builder.margin'), 
    'margin_remove_top' => $config->get('builder.margin_remove_top'), 
    'margin_remove_bottom' => $config->get('builder.margin_remove_bottom'), 
    'maxwidth' => $config->get('builder.maxwidth'), 
    'maxwidth_breakpoint' => $config->get('builder.maxwidth_breakpoint'), 
    'block_align' => $config->get('builder.block_align'), 
    'block_align_breakpoint' => $config->get('builder.block_align_breakpoint'), 
    'block_align_fallback' => $config->get('builder.block_align_fallback'), 
    'text_align' => $config->get('builder.text_align'), 
    'text_align_breakpoint' => $config->get('builder.text_align_breakpoint'), 
    'text_align_fallback' => $config->get('builder.text_align_fallback'), 
    'animation' => $config->get('builder.animation'), 
    'id' => $config->get('builder.id'), 
    'class' => $config->get('builder.cls'), 
    'attributes' => $config->get('builder.attrs'), 
    'css' => [
      'label' => 'CSS', 
      'description' => 'Enter your own custom CSS. The following selectors will be prefixed automatically for this element: <code>.el-element</code>, <code>.el-item</code>, <code>.el-content</code>', 
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
          'title' => 'Layout', 
          'fields' => ['button_text', 'button_processing_text', 'button_complete_text', 'icon', 'icon_width', 'icon_align', 'complete_icon', 'button_style', 'button_size', 'fullwidth', 'margin', 'margin_remove_top', 'margin_remove_bottom', 'maxwidth', 'maxwidth_breakpoint', 'block_align', 'block_align_breakpoint', 'block_align_fallback', 'text_align', 'text_align_breakpoint', 'text_align_fallback', 'animation']
        ], [
          'title' => 'Advanced', 
          'fields' => ['id', 'class', 'attributes', 'css']
        ]]
    ]
  ]
];
