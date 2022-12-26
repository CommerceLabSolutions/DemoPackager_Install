<?php


// Item
$el = $this->el('div', [
    'class' => [
        'el-item uk-panel'
    ]
]);

// Content
$content = $this->el('div', [
    'class' => [
        'el-content uk-panel',
    ],
]);
?>
<?php //echo "<pre>";print_r($props)?>
<?= $el($element, $attrs) ?>
    <?php if ($props['field_type']) : ?>
    <?= $content($element, $props['field_type']) ?>
    <?php endif ?>
<?= $el->end() ?>
