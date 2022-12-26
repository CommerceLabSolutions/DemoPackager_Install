<?php


defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Language\Text;

use Joomla\CMS\Categories\Categories;
use Joomla\CMS\Categories\CategoryNode;
use Joomla\CMS\Table\Table;

use Joomla\CMS\Helper\TagsHelper;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\Module\Menu\Site\Helper\MenuHelper;

$language = Factory::getLanguage();
$language->load('com_commercelab_shop', JPATH_ADMINISTRATOR);

$id = uniqid('yps_grid_filter');

$el = $this->el('div');
$grid = $this->el('div', [

    'class' => [
        'yps_reorder' . $id,
        'uk-child-width-[1-{@!grid_default: auto}]{grid_default}',
        'uk-child-width-[1-{@!grid_small: auto}]{grid_small}@s',
        'uk-child-width-[1-{@!grid_medium: auto}]{grid_medium}@m',
        'uk-child-width-[1-{@!grid_large: auto}]{grid_large}@l',
        'uk-child-width-[1-{@!grid_xlarge: auto}]{grid_xlarge}@xl',
        'uk-flex-center {@grid_column_align}',
        'uk-flex-middle {@grid_row_align}',
    ],

    'uk-grid' => $this->expr([
        'masonry: {grid_masonry};',
        'parallax: {grid_parallax};',
    ], $props) ?: true,

]);
$test = '';
foreach ($props as $key => $value) {
    $test .= " ".$value;
}

?>

<?= $el($props, $attrs) ?>

<style type="text/css">
    

li.active a,
li.pre-page a{
    background: transparent !important;
    color: black !important;
}

ul.uk-pagination li a {
    background: gray;
    color: white;
}

ul.uk-pagination li a {
    color: white;
    width: 33px;
    height: 33px;
    line-height: 33px;
}

ul.uk-pagination li a:hover {
    background: #cfcfcf;
}
 

</style>



<div id="<?= $id; ?>">

    <div class="tm-grid-expand uk-margin-small uk-grid <?= $test ?>" uk-grid="">

        <?php if($props['filter_type'] == 'categories') : 
            $category_id = $props['category_filter'];
            if(!empty($category_id)){
                $categories = Categories::getInstance("content");
                $rootNode = $categories->get($category_id);   
                $categoryNodes = $rootNode->getChildren(true);
            ?>
            <div class="uk-width-1-2@s uk-width-2-5@m">
                <ul class="uk-nav-default" uk-nav>
                    <li class="uk-parent">
                        <a href="#"><?= $rootNode->title ?> <span><?php
                            if($props['show_root_item_count']){
                                echo "(".count($categoryNodes).")";
                            }
                          ?></span><?php
                            if(count($categoryNodes) > 0){ ?>
                                <span class="uk-margin-small-left" uk-icon="icon: chevron-down">
                            <?php } ?>        
                                </a>
                        <ul class="uk-nav-sub">
                    <?php foreach ($categoryNodes as $key => $categoryNode) { ?>
                            <li><a href="#"><?= $categoryNode->title ?></a></li>
                    <?php } ?>
                        </ul>
                    </li>
                </ul>
            </div>
        <?php } endif; ?>

        <?php if($props['filter_type'] == 'tags') :
            $tag_id = $props['tag_filter'];
            if(!empty($tag_id)){
                $tagTable  = Table::getInstance('Tag', 'TagsTable');
                $th = new TagsHelper();
                $result = $th->getTagTreeArray($tag_id); 
                $tagnames = $th->getTagNames($result);
            ?>
            <div class="uk-width-1-2@s uk-width-2-5@m">
                <ul class="uk-nav-default" uk-nav>
                    <li class="uk-parent">
                        <a href="#"><?= $tagnames[0] ?> <span><?php
                            if($props['show_root_item_count']){
                                echo "(".(count($tagnames)-1).")";
                            }
                          ?></span><?php
                            if((count($tagnames)-1) > 0){ ?>
                                <span class="uk-margin-small-left" uk-icon="icon: chevron-down">
                            <?php } ?>        
                                </a>
                        <ul class="uk-nav-sub">
                    <?php foreach ($tagnames as $key => $tagname) { ?>
                        <?php if($key>'0'){ ?>
                            <li><a href="#"><?= $tagname ?></a></li>
                        <?php } ?>
                    <?php } ?>
                        </ul>
                    </li>
                </ul>
            </div>
        <?php } endif; ?>

        <?php if($props['filter_type'] == 'menudata') :
            $menu_id = $props['menu_filter'];
            $mainmenuItems = plgSystemMenu_pro_element::getMenuList($props);
            $module = ModuleHelper::getModule('mod_menu');
            $params = new JRegistry($module->params);
            $base       = MenuHelper::getBase($params);
            $active     = MenuHelper::getActive($params);
            $default    = MenuHelper::getDefault();
            $active_id  = $active->id;
            $default_id = $default->id;
            $path       = $base->tree;
            $showAll    = $params->get('showAllChildren', 1);
            $class_sfx  = htmlspecialchars($params->get('class_sfx', ''), ENT_COMPAT, 'UTF-8');
            if(!empty($menu_id) && !empty($mainmenuItems)){ 
                echo '<div class="uk-width-1-2@s uk-width-2-5@m">';
                    echo '<ul class="uk-nav-default" uk-nav>';
                        echo '<li class="uk-parent">';
                            echo '<a href="#">';
                                echo !empty($mainmenuItems[0]->menutype)?$mainmenuItems[0]->menutype:"mainmenu"; 
                                echo '<span>';
                                if($props['show_root_item_count']){
                                    echo "(".count($mainmenuItems).")";
                                }
                                echo '</span>';
                                if(count($mainmenuItems) > 0){ 
                                    echo '<span class="uk-margin-small-left" uk-icon="icon: chevron-down">';
                                }     
                                echo '</a>';
                            echo '<ul class="uk-nav-sub">';
                            foreach ($mainmenuItems as $key => $item) {
                                $itemParams = $item->getParams();
                                $class      = 'nav-item item-' . $item->id;

                                if ($item->id == $default_id)
                                {
                                    $class .= ' default';
                                }

                                if ($item->id == $active_id || ($item->type === 'alias' && $itemParams->get('aliasoptions') == $active_id))
                                {
                                    $class .= ' current';
                                }

                                if (in_array($item->id, $path))
                                {
                                    $class .= ' active';
                                }
                                elseif ($item->type === 'alias')
                                {
                                    $aliasToId = $itemParams->get('aliasoptions');

                                    if (count($path) > 0 && $aliasToId == $path[count($path) - 1])
                                    {
                                        $class .= ' active';
                                    }
                                    elseif (in_array($aliasToId, $path))
                                    {
                                        $class .= ' alias-parent-active';
                                    }
                                }

                                if ($item->type === 'separator')
                                {
                                    $class .= ' divider';
                                }

                                if ($item->deeper)
                                {
                                    $class .= ' deeper';
                                }

                                if ($item->parent)
                                {
                                    $class .= ' parent';
                                }

                                echo '<li class="' . $class . '">';
                                switch ($item->type) :
                                    case 'separator':
                                    case 'component':
                                    case 'heading':
                                    case 'url':                                        
                                        echo $this->render("{$__dir}/template-{$item->type}", compact('item','itemParams','active_id','params'));
                                        break;

                                    default:
                                        echo $this->render("{$__dir}/template-url", compact('item','itemParams','active_id','params'));
                                        break;
                                endswitch;

                                // The next item is deeper.
                                if ($item->deeper)
                                {
                                    echo '<ul class="mod-menu__sub list-unstyled small">';
                                }
                                // The next item is shallower.
                                elseif ($item->shallower)
                                {
                                    echo '</li>';
                                    echo str_repeat('</ul></li>', $item->level_diff);
                                }
                                // The next item is on the same level.
                                else
                                {
                                    echo '</li>';
                                }
                            }
                            echo '</ul>';
                        echo '</li>';
                    echo '</ul>';
                echo '</div>';
            } endif;
    echo '</div>';
echo '</div>';
echo '</div>';