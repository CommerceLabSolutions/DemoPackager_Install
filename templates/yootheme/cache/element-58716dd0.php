<?php // $file = C:/xampp/htdocs/joomla/joomla4/plugins/system/menu_pro_element/modules/menuproelement/elements/menu_element/element.json

return [
  '@import' => $filter->apply('path', './element.php', $file), 
  'name' => 'menuproelement', 
  'title' => 'Menu Pro', 
  'icon' => $filter->apply('url', 'images/advancemenu.svg', $file), 
  'iconSmall' => $filter->apply('url', 'images/advancemenusmall.svg', $file), 
  'element' => true, 
  'container' => true, 
  'group' => 'Universal Elements', 
  'placeholder' => [
    'props' => [
      'sidebar_position' => 'left', 
      'grid_layout' => 'thirds'
    ]
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
    'filter_type' => [
      'label' => 'Filter By', 
      'type' => 'select', 
      'default' => 'categories', 
      'options' => [
        'Categories' => 'categories', 
        'Tags' => 'tags', 
        'Menu Items' => 'menudata'
      ]
    ], 
    'category_filter' => [
      'label' => 'Select Root Level', 
      'type' => 'select', 
      'options' => $config->get('advancemenu.joomla.categories'), 
      'show' => 'filter_type == \'categories\''
    ], 
    'tag_filter' => [
      'label' => 'Select Root Level', 
      'type' => 'select', 
      'options' => $config->get('advancemenu.joomla.tags'), 
      'show' => 'filter_type == \'tags\''
    ], 
    'menu_filter' => [
      'label' => 'Select Root Level', 
      'type' => 'select', 
      'default' => 'mainmenu', 
      'options' => $config->get('advancemenu.joomla.menusele'), 
      'show' => 'filter_type == \'menudata\''
    ], 
    'menu_sub_level' => [
      'label' => 'Menu Sub Levels', 
      'type' => 'select', 
      'default' => 'all', 
      'options' => [
        'All' => 'all', 
        1 => '1', 
        2 => '2', 
        3 => '3', 
        4 => '4', 
        5 => '5'
      ]
    ], 
    'include_root' => [
      'label' => 'Include Root', 
      'type' => 'radio', 
      'default' => '0', 
      'options' => [
        'Y' => '1', 
        'N' => '0'
      ]
    ], 
    'show_active_root_menu_item' => [
      'label' => 'Show Active Root Menu Item', 
      'type' => 'radio', 
      'default' => '0', 
      'options' => [
        'Y' => '1', 
        'N' => '0'
      ]
    ], 
    'show_active_sub_menu_item' => [
      'label' => 'Show Active Sub Menu Item', 
      'type' => 'radio', 
      'default' => '0', 
      'options' => [
        'Y' => '1', 
        'N' => '0'
      ]
    ], 
    'show_sub_levels' => [
      'label' => 'Show Sub-Levels', 
      'type' => 'radio', 
      'default' => '1', 
      'options' => [
        'Always Show Sub-Level' => '1', 
        'Show Only When in Root Category' => '0'
      ]
    ], 
    'sub_level_accordion' => [
      'label' => 'Sub Level Accordion', 
      'type' => 'radio', 
      'default' => '0', 
      'options' => [
        'Y' => '1', 
        'N' => '0'
      ]
    ], 
    'sub_level_accordion_open_default' => [
      'label' => 'Sub Level Accordion Open By Default', 
      'type' => 'radio', 
      'default' => '0', 
      'options' => [
        'Y' => '1', 
        'N' => '0'
      ]
    ], 
    'sub_level_accordion_close_in_mobile' => [
      'label' => 'Sub Level Accordion Close In Mobile', 
      'type' => 'radio', 
      'default' => '0', 
      'options' => [
        'Y' => '1', 
        'N' => '0'
      ]
    ], 
    'show_root_item_count' => [
      'label' => 'Show Root Item Count', 
      'type' => 'radio', 
      'default' => '0', 
      'options' => [
        'Y' => '1', 
        'N' => '0'
      ]
    ], 
    'show_sub_item_count' => [
      'label' => 'Show Sub Item Count', 
      'type' => 'radio', 
      'default' => '0', 
      'options' => [
        'Y' => '1', 
        'N' => '0'
      ]
    ], 
    'link_root' => [
      'label' => 'Link Root', 
      'type' => 'radio', 
      'default' => '0', 
      'options' => [
        'Y' => '1', 
        'N' => '0'
      ]
    ], 
    'link_sub_menu_item' => [
      'label' => 'Link Sub Menu Item', 
      'type' => 'radio', 
      'default' => '0', 
      'options' => [
        'Y' => '1', 
        'N' => '0'
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
    'container_padding_remove' => $config->get('builder.container_padding_remove'), 
    'name' => $config->get('builder.name'), 
    'status' => $config->get('builder.status'), 
    'source' => $config->get('builder.source'), 
    'id' => $config->get('builder.id'), 
    'class' => $config->get('builder.cls'), 
    'attributes' => $config->get('builder.attrs'), 
    'css' => [
      'label' => 'CSS', 
      'description' => 'Enter your own custom CSS. The following selectors will be prefixed automatically for this element: <code>.el-element</code>, <code>.el-image</code>, <code>.el-title</code>, <code>.el-meta</code>, <code>.el-content</code>, <code>.el-hover-image</code>', 
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
          'title' => 'Advance Menu', 
          'fields' => [[
              'label' => 'Filter Box', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['filter_type', 'category_filter', 'tag_filter', 'menu_filter', 'menu_sub_level', 'include_root']
            ], [
              'label' => 'General Mneu Settings', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['show_active_root_menu_item', 'show_active_sub_menu_item', 'show_sub_levels', 'sub_level_accordion', 'sub_level_accordion_open_default', 'sub_level_accordion_close_in_mobile', 'show_root_item_count', 'show_sub_item_count', 'link_root', 'link_sub_menu_item']
            ]]
        ], [
          'title' => 'Settings', 
          'fields' => [[
              'label' => 'Panel', 
              'type' => 'group', 
              'fields' => ['panel_background', 'panel_color_inverse']
            ], [
              'label' => 'General', 
              'type' => 'group', 
              'fields' => ['position', 'position_left', 'position_right', 'position_top', 'position_bottom', 'position_z_index', 'margin', 'margin_remove_top', 'margin_remove_bottom', 'maxwidth', 'maxwidth_breakpoint', 'block_align', 'block_align_breakpoint', 'block_align_fallback', 'text_align', 'text_align_breakpoint', 'text_align_fallback', 'animation', '_parallax_button', 'visibility', 'container_padding_remove']
            ]]
        ]]
    ]
  ]
];
