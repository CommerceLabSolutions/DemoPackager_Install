<?php // $file = C:/xampp/htdocs/joomla/joomla4/plugins/system/protostore/modules/core/elements/protostore_price/element.json

return [
  '@import' => $filter->apply('path', './element.php', $file), 
  'name' => 'protostore_price', 
  'title' => 'Static Price', 
  'icon' => $filter->apply('url', 'images/protostore_price.svg', $file), 
  'iconSmall' => $filter->apply('url', 'images/protostore_price_small.svg', $file), 
  'element' => true, 
  'container' => true, 
  'group' => 'Pro2Store', 
  'defaults' => [
    'title_element' => 'h3', 
    'price_type' => 'base_price'
  ], 
  'templates' => [
    'render' => $filter->apply('path', './templates/template.php', $file), 
    'content' => $filter->apply('path', './templates/content.php', $file)
  ], 
  'fields' => [
    'price_type' => [
      'label' => 'Type', 
      'description' => 'Choose what proce information to show', 
      'type' => 'select', 
      'default' => '', 
      'options' => [
        'Base Price' => 'base_price', 
        'Discount Amount' => 'discount_amount', 
        'Discount Percentage' => 'discount_percentage', 
        'Price After Discount' => 'price_after_discount', 
        'Saving' => 'saving'
      ]
    ], 
    'content_before' => [
      'type' => 'text', 
      'label' => 'Content Before'
    ], 
    'content_after' => [
      'type' => 'text', 
      'label' => 'Content After'
    ], 
    'title_style' => [
      'label' => 'Style', 
      'description' => 'Choose the style of the Price information', 
      'type' => 'select', 
      'default' => '', 
      'options' => [
        'None' => '', 
        '2Xlarge' => 'heading-2xlarge', 
        'XLarge' => 'heading-xlarge', 
        'Large' => 'heading-large', 
        'Medium' => 'heading-medium', 
        'Small' => 'heading-small', 
        'H1' => 'h1', 
        'H2' => 'h2', 
        'H3' => 'h3', 
        'H4' => 'h4', 
        'H5' => 'h5', 
        'H6' => 'h6'
      ]
    ], 
    'strikethru' => [
      'type' => 'checkbox', 
      'text' => 'Strike Through?'
    ], 
    'title_decoration' => [
      'label' => 'Decoration', 
      'description' => 'Choose the decoration of the Price information', 
      'type' => 'select', 
      'default' => '', 
      'options' => [
        'None' => '', 
        'Divider' => 'divider', 
        'Bullet' => 'bullet', 
        'Line' => 'line'
      ]
    ], 
    'title_font_family' => [
      'label' => 'Font Family', 
      'description' => 'Select an alternative font family for the Price information.', 
      'type' => 'select', 
      'default' => '', 
      'options' => [
        'None' => '', 
        'Default' => 'default', 
        'Primary' => 'primary', 
        'Secondary' => 'secondary', 
        'Tertiary' => 'tertiary'
      ]
    ], 
    'title_color' => [
      'label' => 'Color', 
      'description' => 'Select the text color. If the Background option is selected, styles that don\'t apply a background image use the primary color instead.', 
      'type' => 'select', 
      'default' => '', 
      'options' => [
        'None' => '', 
        'Muted' => 'muted', 
        'Emphasis' => 'emphasis', 
        'Primary' => 'primary', 
        'Success' => 'success', 
        'Warning' => 'warning', 
        'Danger' => 'danger', 
        'Background' => 'background'
      ]
    ], 
    'title_element' => [
      'label' => 'HTML Element', 
      'description' => 'Choose one of the elements to fit your semantic structure.', 
      'type' => 'select', 
      'options' => [
        'H1' => 'h1', 
        'H2' => 'h2', 
        'H3' => 'h3', 
        'H4' => 'h4', 
        'H5' => 'h5', 
        'H6' => 'h6', 
        'div' => 'div'
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
    'css' => [
      'label' => 'CSS', 
      'description' => 'Enter your own custom CSS. The following selectors will be prefixed automatically for this element: <code>.el-element</code>', 
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
          'title' => 'Price', 
          'fields' => [[
              'label' => 'Price', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['price_type', 'content_before', 'content_after', 'title_style', 'strikethru', 'title_decoration', 'title_font_family', 'title_color', 'title_element']
            ]]
        ], [
          'title' => 'Settings', 
          'fields' => [[
              'label' => 'General', 
              'type' => 'group', 
              'fields' => ['position', 'position_left', 'position_right', 'position_top', 'position_bottom', 'position_z_index', 'margin', 'margin_remove_top', 'margin_remove_bottom', 'maxwidth', 'maxwidth_breakpoint', 'block_align', 'block_align_breakpoint', 'block_align_fallback', 'text_align', 'text_align_breakpoint', 'text_align_fallback', 'animation', '_parallax_button', 'visibility']
            ]]
        ], $config->get('builder.advanced')]
    ]
  ]
];
