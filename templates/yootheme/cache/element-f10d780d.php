<?php // $file = C:/xampp/htdocs/joomla/joomla4/plugins/system/protostore/modules/core/elements/protostore_cartuser/element.json

return [
  '@import' => $filter->apply('path', './element.php', $file), 
  'name' => 'protostore_cartuser', 
  'title' => 'User (Login/Address)', 
  'icon' => $filter->apply('url', 'images/protostore_cartuser.svg', $file), 
  'iconSmall' => $filter->apply('url', 'images/protostore_cartuser_small.svg', $file), 
  'element' => true, 
  'container' => true, 
  'group' => 'Pro2Store Checkout', 
  'placeholder' => [
    'props' => [
      'content' => 'Add To Cart', 
      'icon' => '', 
      'button_style' => 'default'
    ]
  ], 
  'templates' => [
    'render' => $filter->apply('path', './templates/template.php', $file), 
    'content' => $filter->apply('path', './templates/content.php', $file)
  ], 
  'fields' => [
    'hidelogin' => [
      'type' => 'checkbox', 
      'text' => 'Hide Returning Customer Panel'
    ], 
    'hideregister' => [
      'type' => 'checkbox', 
      'text' => 'Hide New Customer Panel'
    ], 
    'hideguest' => [
      'type' => 'checkbox', 
      'text' => 'Hide Guest Checkout Panel'
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
    'text_align' => $config->get('builder.text_align'), 
    'text_align_breakpoint' => $config->get('builder.text_align_breakpoint'), 
    'text_align_fallback' => $config->get('builder.text_align_fallback'), 
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
          'title' => 'Setup', 
          'fields' => [[
              'label' => 'New Customer Panel', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['hideregister']
            ], [
              'label' => 'Returning Customer Panel', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['hidelogin']
            ], [
              'label' => 'Guest Checkout Panel', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['hideguest']
            ]]
        ], [
          'title' => 'Settings', 
          'fields' => [[
              'label' => 'General', 
              'type' => 'group', 
              'fields' => ['style', 'position', 'position_left', 'position_right', 'position_top', 'position_bottom', 'position_z_index', 'margin', 'margin_remove_top', 'margin_remove_bottom', 'maxwidth', 'maxwidth_breakpoint', 'block_align', 'block_align_breakpoint', 'block_align_fallback', 'text_align', 'text_align_breakpoint', 'text_align_fallback', 'animation', '_parallax_button', 'visibility']
            ]]
        ], $config->get('builder.advanced')]
    ]
  ]
];
