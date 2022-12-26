<?php // $file = C:/xampp/htdocs/joomla/joomla4/plugins/system/universal_elements/modules/userform/elements/userform_field/element.json

return [
  '@import' => $filter->apply('path', './element.php', $file), 
  'name' => 'universal_elements_userform_field', 
  'title' => 'User Detail Forms Field', 
  'width' => 500, 
  'placeholder' => [
    'props' => [
      'content' => 'Lorem ipsum dolor sit amet.', 
      'image' => '', 
      'icon' => ''
    ]
  ], 
  'templates' => [
    'render' => $filter->apply('path', './templates/template.php', $file), 
    'content' => $filter->apply('path', './templates/content.php', $file)
  ], 
  'fields' => [
    'field_type' => [
      'label' => 'Field Name', 
      'type' => 'select', 
      'default' => 'name', 
      'options' => $config->get('userFields.joomla.fields'), 
      'description' => 'Choose the Field'
    ], 
    'field_required' => [
      'label' => 'Required ?', 
      'type' => 'radio', 
      'options' => [
        'Yes' => 1, 
        'No' => 0
      ], 
      'default' => 0
    ], 
    'field_approval_needed' => [
      'label' => 'Approval Needed ?', 
      'type' => 'radio', 
      'options' => [
        'Yes' => 1, 
        'No' => 0
      ], 
      'default' => 0
    ], 
    'field_label' => [
      'label' => 'Label', 
      'attrs' => [
        'placeholder' => 'Same as Field Name'
      ], 
      'description' => 'You can define a different Label'
    ], 
    'field_label_show_use_global' => [
      'label' => 'Hide/Show', 
      'text' => 'Use Global', 
      'type' => 'checkbox', 
      'default' => true
    ], 
    'field_label_show' => [
      'text' => 'Show Label', 
      'type' => 'radio', 
      'options' => [
        'Show' => 1, 
        'Hide' => 0
      ], 
      'default' => 1, 
      'enable' => '!field_label_show_use_global'
    ], 
    'field_placeholder' => [
      'label' => 'Placeholder', 
      'attrs' => [
        'placeholder' => 'Same as Field Name'
      ], 
      'description' => 'You can define a different Placeholder'
    ], 
    'field_placeholder_show_use_global' => [
      'label' => '', 
      'text' => 'Use Global', 
      'type' => 'checkbox', 
      'default' => true
    ], 
    'field_width_use_global' => [
      'text' => 'Use GLobal', 
      'type' => 'checkbox', 
      'default' => true
    ], 
    'field_width' => [
      'label' => 'Width', 
      'type' => 'select', 
      'default' => 'uk-width-1-1@s', 
      'options' => [
        'Full' => 'uk-width-1-1@s', 
        'Half' => 'uk-width-1-2@s', 
        'Third' => 'uk-width-1-3@s', 
        'Quarter' => 'uk-width-1-4@s', 
        'Large' => 'uk-width-large@s', 
        'Medium' => 'uk-width-medium@s', 
        'Small' => 'uk-width-small@s', 
        'Custom' => 'custom'
      ], 
      'enable' => '!field_width_use_global'
    ], 
    'invalid_field_icon_custom' => [
      'label' => 'Icon', 
      'type' => 'icon'
    ], 
    'invalid_field_color' => [
      'label' => 'Icon Color', 
      'type' => 'select', 
      'default' => 'uk-text-danger', 
      'options' => [
        'Default' => '', 
        'Muted' => 'uk-text-muted', 
        'Light' => 'uk-light', 
        'Emphasis' => 'uk-text-emphasis', 
        'Primary' => 'uk-text-primary', 
        'Success' => 'uk-text-success', 
        'Warning' => 'uk-text-warning', 
        'Danger' => 'uk-text-danger', 
        'Background' => 'uk-text-background'
      ], 
      'show' => '!show_icons_default'
    ], 
    'image_border' => [
      'label' => 'Border', 
      'description' => 'Select the image border style.', 
      'type' => 'select', 
      'options' => [
        'None' => '', 
        'Rounded' => 'rounded', 
        'Circle' => 'circle', 
        'Pill' => 'pill'
      ]
    ], 
    'image_box_shadow' => [
      'label' => 'Box Shadow', 
      'description' => 'Select the image box shadow size.', 
      'type' => 'select', 
      'options' => [
        'None' => '', 
        'Small' => 'small', 
        'Medium' => 'medium', 
        'Large' => 'large', 
        'X-Large' => 'xlarge'
      ]
    ], 
    'image_hover_box_shadow' => [
      'label' => 'Hover Box Shadow', 
      'description' => 'Select the image box shadow size on hover.', 
      'type' => 'select', 
      'options' => [
        'None' => '', 
        'Small' => 'small', 
        'Medium' => 'medium', 
        'Large' => 'large', 
        'X-Large' => 'xlarge'
      ]
    ], 
    'image_box_decoration' => [
      'label' => 'Box Decoration', 
      'description' => 'Select the image box decoration style.', 
      'type' => 'select', 
      'options' => [
        'None' => '', 
        'Default' => 'default', 
        'Primary' => 'primary', 
        'Secondary' => 'secondary', 
        'Floating Shadow' => 'shadow', 
        'Mask' => 'mask'
      ]
    ], 
    'field_width_custom' => [
      'label' => 'Custom Width', 
      'attrs' => [
        'placeholder' => 'uk-width-expand@s uk-width-medium ...'
      ], 
      'enable' => 'field_width == \'custom\' && !field_width_use_global', 
      'description' => 'Choose one of the predefined width, or add a custom UIkit class. This is applied to the container of the field, you can also set a global option to style all fields, making them more or less compact'
    ]
  ], 
  'fieldset' => [
    'default' => [
      'type' => 'tabs', 
      'fields' => [[
          'title' => 'User Field', 
          'fields' => [[
              'label' => 'Field', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['field_type']
            ], [
              'label' => 'Setting per item', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['field_required', 'field_approval_needed']
            ], [
              'label' => 'Label Setting', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['field_label', 'field_label_show_use_global', 'field_label_show']
            ], [
              'label' => 'Field Placeholder Setting', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['field_placeholder', 'field_placeholder_show_use_global']
            ], [
              'label' => 'Field Width', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['field_width_use_global', 'field_width', 'field_width_custom']
            ], [
              'label' => 'Item Icon', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['invalid_field_icon_custom', 'invalid_field_color']
            ], [
              'label' => 'Image Item Setting', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['image_border', 'image_box_shadow', 'image_box_decoration', 'image_hover_box_shadow']
            ]]
        ]]
    ]
  ]
];