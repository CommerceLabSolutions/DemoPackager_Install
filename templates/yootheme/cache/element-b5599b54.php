<?php // $file = C:/xampp/htdocs/joomla/joomla4/plugins/system/universal_elements/modules/userform/elements/userform/element.json

return [
  '@import' => $filter->apply('path', './element.php', $file), 
  'name' => 'universal_elements_userform', 
  'title' => 'User Details Forms', 
  'group' => 'Universal Elements', 
  'icon' => $filter->apply('url', 'images/universal_elements_userform.svg', $file), 
  'iconSmall' => $filter->apply('url', 'images/universal_elements_userform_small.svg', $file), 
  'element' => true, 
  'container' => true, 
  'width' => 500, 
  'defaults' => [], 
  'placeholder' => [
    'children' => [[
        'type' => 'universal_elements_userform_field', 
        'props' => []
      ], [
        'type' => 'universal_elements_userform_field', 
        'props' => []
      ], [
        'type' => 'universal_elements_userform_field', 
        'props' => []
      ]]
  ], 
  'templates' => [
    'render' => $filter->apply('path', './templates/template.php', $file), 
    'content' => $filter->apply('path', './templates/content.php', $file)
  ], 
  'fields' => [
    'panel_background' => [
      'label' => 'Color', 
      'type' => 'select', 
      'options' => [
        'None' => '', 
        'Default' => 'uk-background-default', 
        'Primary' => 'uk-background-primary', 
        'Secondary' => 'uk-background-secondary', 
        'Muted' => 'uk-background-muted'
      ]
    ], 
    'panel_color_inverse' => [
      'label' => 'Color Inverse', 
      'type' => 'radio', 
      'default' => '', 
      'options' => [
        'None' => '', 
        'Light' => 'uk-light', 
        'Dark' => 'uk-dark'
      ], 
      'description' => 'This can be used to force the inner content to be Light or Dark, depending on the background'
    ], 
    'content' => [
      'type' => 'content-items', 
      'title' => 'field_type', 
      'item' => 'universal_elements_userform_field'
    ], 
    'user_form_title' => [
      'default' => 'User Form', 
      'label' => 'User form Title'
    ], 
    'inline_editing_approval' => [
      'label' => 'Inline Editing', 
      'type' => 'radio', 
      'default' => 0, 
      'options' => [
        'Yes' => 1, 
        'No' => 0
      ]
    ], 
    'user_submit_button_text' => [
      'label' => 'Text'
    ], 
    'user_submit_button_vertical_alignment' => [
      'label' => 'Vertical Placement', 
      'type' => 'select', 
      'default' => 'uk-vertical-align-top', 
      'options' => [
        'Align Top' => 'uk-vertical-align-top', 
        'Align Bottom' => 'uk-vertical-align-bottom'
      ]
    ], 
    'user_submit_button_horizontal_placement' => [
      'label' => 'Horizontal Placement', 
      'type' => 'select', 
      'default' => 'uk-align-left', 
      'options' => [
        'Align Left' => 'uk-align-left', 
        'Align Right' => 'uk-align-right', 
        'Align Middle' => 'uk-align-center'
      ]
    ], 
    'user_submit_button_size' => [
      'label' => 'Size', 
      'type' => 'select', 
      'default' => '', 
      'options' => [
        'Small' => 'uk-button-small', 
        'Default' => '', 
        'Large' => 'uk-button-large'
      ]
    ], 
    'user_submit_button_type' => [
      'label' => 'Style', 
      'type' => 'select', 
      'default' => 'uk-button-primary', 
      'options' => [
        'Default' => 'uk-button-default', 
        'Primary' => 'uk-button-primary', 
        'Secondary' => 'uk-button-secondary', 
        'Success' => 'uk-button-success', 
        'Danger' => 'uk-button-danger', 
        'Warning' => 'uk-button-warning'
      ]
    ], 
    'user_edit_button_text' => [
      'label' => 'Text', 
      'default' => 'Save'
    ], 
    'user_edit_button_vertical_alignment' => [
      'label' => 'Vertical Placement', 
      'type' => 'select', 
      'default' => 'uk-vertical-align-top', 
      'options' => [
        'Align Top' => 'uk-vertical-align-top', 
        'Align Bottom' => 'uk-vertical-align-bottom'
      ]
    ], 
    'user_edit_button_horizontal_placement' => [
      'label' => 'Horizontal Placement', 
      'type' => 'select', 
      'default' => 'uk-horizontal-align-left', 
      'options' => [
        'Align Left' => 'uk-horizontal-align-left', 
        'Align Right' => 'uk-horizontal-align-right', 
        'Align Middle' => 'uk-horizontal-align-middle'
      ]
    ], 
    'user_edit_button_size' => [
      'label' => 'Size', 
      'type' => 'select', 
      'default' => '', 
      'options' => [
        'Small' => 'uk-button-small', 
        'Default' => '', 
        'Large' => 'uk-button-large'
      ]
    ], 
    'user_edit_button_type' => [
      'label' => 'Style', 
      'type' => 'select', 
      'default' => 'uk-button-primary', 
      'options' => [
        'Default' => 'uk-button-default', 
        'Primary' => 'uk-button-primary', 
        'Secondary' => 'uk-button-secondary', 
        'Success' => 'uk-button-success', 
        'Danger' => 'uk-button-danger', 
        'Warning' => 'uk-button-warning'
      ]
    ], 
    'user_pending_notification' => [
      'label' => 'Pending Notification Message', 
      'type' => 'textarea'
    ], 
    'user_denied_notification' => [
      'label' => 'Denied Notification Message', 
      'type' => 'textarea'
    ], 
    'user_approved_notification' => [
      'label' => 'Approved Notification Message', 
      'type' => 'textarea'
    ], 
    'forms_layout' => [
      'label' => 'Forms Layout', 
      'type' => 'select', 
      'default' => 'accordion', 
      'options' => [
        'Accordion' => 'accordion', 
        'Tabs' => 'tabs', 
        'Side by side' => 'side_by_side'
      ], 
      'description' => 'Set the, forms with additional layouts to improve user experience', 
      'enable' => 'billing_address_required'
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
    ',text_align' => $config->get('builder.text_align_justify'), 
    ',text_align_breakpoint' => $config->get('builder.text_align_breakpoint'), 
    ',text_align_fallback' => $config->get('builder.text_align_justify_fallback'), 
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
      'description' => 'Enter your own custom CSS. The, following selectors will be prefixed automatically for this element: <code>.el-element</code> <code>.el-item</code> <code>.el-content</code> <code>.el-image</code> <code>.el-link</code>', 
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
          'fields' => ['content', [
              'label' => 'Forms', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['user_form_title', 'inline_editing_approval']
            ], [
              'label' => 'Save Button Setting', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['user_submit_button_text', 'user_submit_button_vertical_alignment', 'user_submit_button_horizontal_placement', 'user_submit_button_size', 'user_submit_button_type']
            ], [
              'label' => 'Edit Button Setting', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['user_edit_button_text', 'user_edit_button_vertical_alignment', 'user_edit_button_horizontal_placement', 'user_edit_button_size', 'user_edit_button_type']
            ], [
              'label' => 'Notification Setting', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['user_pending_notification', 'user_denied_notification', 'user_approved_notification']
            ]]
        ], [
          'title' => 'Settings', 
          'fields' => [[
              'label' => 'Panel', 
              'type' => 'group', 
              'fields' => ['panel_background', 'panel_color_inverse', 'position', 'position_left', 'position_right', 'position_top', 'position_bottom', 'position_z_index', 'margin', 'margin_remove_top', 'margin_remove_bottom', 'maxwidth', 'maxwidth_breakpoint', 'block_align', 'block_align_breakpoint', 'block_align_fallback', ',text_align', ',text_align_breakpoint', ',text_align_fallback', 'animation', '_parallax_button', 'visibility']
            ]]
        ], $config->get('builder.advanced')]
    ]
  ]
];
