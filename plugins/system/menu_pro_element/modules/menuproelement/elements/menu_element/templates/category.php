<?php


defined('_JEXEC') or die;

foreach ($list as $item) : ?>
    <li>
        <label>
            <input @change="filter" :value="<?php echo $item->id; ?>" v-model="selectedCategories"
                   class="uk-checkbox" type="checkbox">
            <?php echo $item->title; ?> (<?php echo $item->numitems; ?>)</label>
        <?php if (count($item->getChildren())) : ?>
            <ul class="uk-list <?= $props['subcategory_list_style']; ?>">
                <?php $temp = $list; ?>
                <?php $list = $item->getChildren(); ?>
                <?php echo $this->render("{$__dir}/category", compact(array('list', 'props'))); ?>
                <?php $list = $temp; ?>
            </ul>
        <?php endif; ?>
    </li>

<?php endforeach; ?>


