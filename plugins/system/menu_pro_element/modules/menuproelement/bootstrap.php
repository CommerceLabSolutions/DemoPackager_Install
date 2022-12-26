<?php

namespace YpsApp_menuproelement;

use YOOtheme\Builder;
use YOOtheme\Path;

use Joomla\CMS\Categories\Categories;
use Joomla\CMS\Factory;
use YOOtheme\Config as Yooconfig;

return [

    'extend' => [

        Builder::class => function (Builder $builder) {

            $builder->addTypePath(Path::get('./elements/*/element.json'));

        },

    ],

    'config' => function (Yooconfig $yooconfig) {

     // Categories
        $extension = "content";
        $options   = [
            'access' => true,
            'published' => 1,
            'countItems' => 1
        ];
        $categories = Categories::getInstance($extension, $options);

        $cat0 = $categories->get('root');
        $cats = $cat0->getChildren(true);

        $categories_array = [];
        foreach ($cats as $key => $category) {

            // Build Subcategories indented Name
            if ($category->level > 1)
            {
                $level = $category->level;
                if($level <= 2){
                    $title = '';
                    
                    while ($level > 1) {
                        $title .= ' -';
                        $level--;
                    }
                    $title .= ' ' . $category->title;
                }

            }
            else
            {
                // Not a Subcategoru
                $title = $category->title;
            }

            $categories_array[$title] = $category->id;
        }

        // Tags
        $all_tags = [];
        $db = Factory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__tags'));
        $query->where($db->quoteName('parent_id') . ' = ' . $db->quote('1'));
        $db->setQuery($query);
        $results = $db->loadObjectList();

        foreach ($results as $key => $tag) {
            $all_tags[$tag->title] = $tag->id;
            $query2 = $db->getQuery(true);
            $query2->select('*');
            $query2->from($db->quoteName('#__tags'));
            $query2->where($db->quoteName('parent_id') . ' = ' . $db->quote($tag->id));
            $db->setQuery($query2);
            $childs = $db->loadObjectList();

            // Build Subcategories indented Name
            if (!empty($childs))
            {
                foreach ($childs as $key => $child) {
                    $title = '';
                    $title .= ' -';
                    $title .= ' ' . $child->title;
                    $all_tags[$title] = $child->id;
                }
            }
        }
        //menus
        $all_menus = [];

        $query3 = $db->getQuery(true);
        $query3->select('*');
        $query3->from($db->quoteName('#__menu_types'));
        $db->setQuery($query3);
        $menu_types = $db->loadObjectList();
        foreach ($menu_types as $key => $menu_type) {
           $all_menus[$menu_type->title] = $menu_type->menutype;
            $query4 = $db->getQuery(true);
            $query4->select('*');
            $query4->from($db->quoteName('#__menu'));
            $query4->where($db->quoteName('menutype') . ' = ' . $db->quote($menu_type->menutype));
            $query4->where($db->quoteName('level') . ' = ' . $db->quote('1'));
            $db->setQuery($query4);
            $mainmenuItems = $db->loadObjectList();
        
            foreach ($mainmenuItems as $key => $mainmenuItem) {
                $title = '';
                $title .= ' -';
                $title .= ' ' . $mainmenuItem->title;
                $all_menus[$title]= $mainmenuItem->id;
            }

        }
      
        return [
            'advancemenu' => [
                'joomla' => [
                    'categories'             => $categories_array,
                    'categories_object_list' => $cats,
                    'tags' => $all_tags,
                    'menusele' => $all_menus,
                ],
            ]
        ];
    }

];
