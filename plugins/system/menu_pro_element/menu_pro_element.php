<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Response\JsonResponse;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Cache\CacheControllerFactoryInterface;
use Joomla\CMS\Router\Route;
use YOOtheme\Application;
use YOOtheme\Path;

class plgSystemMenu_pro_element extends CMSPlugin
{

	public function onAfterInitialise()
	{


		if (class_exists(Application::class, false))
		{

			$app = Application::getInstance();

			$root    = __DIR__;
			$rootUrl = Uri::root(true);

			$themeroot = Path::get('~theme');
			$loader    = require "{$themeroot}/vendor/autoload.php";
			$loader->setPsr4("YpsApp_menuproelement\\", __DIR__ . "/modules/menuproelement");

			// set alias
			Path::setAlias('~menu_pro_element', $root);
			Path::setAlias('~menu_pro_element:rel', $rootUrl . '/plugins/system/menu_pro_element');
			
			// bootstrap modules
		$app->load('~menu_pro_element/modules/menuproelement/bootstrap.php');
		
		}

	}


	/**
	 * @return JsonResponse
	 * @throws Exception
	 * @since 2.0
	 */
	public function onAjaxAdvance_menu_element(): JsonResponse
	{

		$input = Factory::getApplication()->input;

		try
		{

			$response   = array();
			$categories = $input->json->get('categories', null, 'ARRAY');
			$tags       = $input->json->get('tags', null, 'ARRAY');
			$text       = $input->json->get('text', '', 'string');
			$priceFrom  = $input->json->get('price_from', 0, 'float');
			$priceTo    = $input->json->get('price_to', 999999999, 'float');

			$products = \CommerceLabShop\Product\ProductFactory::filterList($text, $categories, $tags, $priceFrom, $priceTo);

			$response['products'] = $products;

			return new JsonResponse($response);

		}
		catch (Exception $e)
		{
			return new JsonResponse('ko', $e->getMessage(), true);
		}

	}

	public function getMenuList($props){
		$app   = Factory::getApplication();
		$menu  = $app->getMenu();

		// Get active menu item
		$base   = self::getBase($props['menu_filter']);
		$levels = Factory::getUser()->getAuthorisedViewLevels();
		asort($levels);
		$key = 'menu_items' . $props['menu_filter'] . implode(',', $levels) . '.' . $base->id;

		/** @var OutputController $cache */
		$cache = Factory::getContainer()->get(CacheControllerFactoryInterface::class)
			->createCacheController('output', ['defaultgroup' => 'mod_menu']);

		if ($cache->contains($key))
		{
			$items = $cache->get($key);
		}
		else
		{
			$path           = $base->tree;
			$start          = (int) $props['menu_sub_level'];
			$items          = $menu->getItems('menutype', 'mainmenu');
			$hidden_parents = array();
			$lastitem       = 0;

			if ($items)
			{
				$inputVars = $app->getInput()->getArray();

				foreach ($items as $i => $item)
				{
					$item->parent = false;
					$itemParams   = $item->getParams();

					if (isset($items[$lastitem]) && $items[$lastitem]->id == $item->parent_id && $itemParams->get('menu_show', 1) == 1)
					{
						$items[$lastitem]->parent = true;
					}

					if (($start && $start > $item->level)
						|| ($start > 1 && !\in_array($item->tree[$start - 2], $path)))
					{
						unset($items[$i]);
						continue;
					}

					// Exclude item with menu item option set to exclude from menu modules
					if (($itemParams->get('menu_show', 1) == 0) || \in_array($item->parent_id, $hidden_parents))
					{
						$hidden_parents[] = $item->id;
						unset($items[$i]);
						continue;
					}

					$item->current = true;

					foreach ($item->query as $key => $value)
					{
						if (!isset($inputVars[$key]) || $inputVars[$key] !== $value)
						{
							$item->current = false;
							break;
						}
					}

					$item->deeper     = false;
					$item->shallower  = false;
					$item->level_diff = 0;

					if (isset($items[$lastitem]))
					{
						$items[$lastitem]->deeper     = ($item->level > $items[$lastitem]->level);
						$items[$lastitem]->shallower  = ($item->level < $items[$lastitem]->level);
						$items[$lastitem]->level_diff = ($items[$lastitem]->level - $item->level);
					}

					$lastitem     = $i;
					$item->active = false;
					$item->flink  = $item->link;

					// Reverted back for CMS version 2.5.6
					switch ($item->type)
					{
						case 'separator':
							break;

						case 'heading':
							// No further action needed.
							break;

						case 'url':
							if ((strpos($item->link, 'index.php?') === 0) && (strpos($item->link, 'Itemid=') === false))
							{
								// If this is an internal Joomla link, ensure the Itemid is set.
								$item->flink = $item->link . '&Itemid=' . $item->id;
							}
							break;

						case 'alias':
							$item->flink = 'index.php?Itemid=' . $itemParams->get('aliasoptions');

							// Get the language of the target menu item when site is multilingual
							if (Multilanguage::isEnabled())
							{
								$newItem = Factory::getApplication()->getMenu()->getItem((int) $itemParams->get('aliasoptions'));

								// Use language code if not set to ALL
								if ($newItem != null && $newItem->language && $newItem->language !== '*')
								{
									$item->flink .= '&lang=' . $newItem->language;
								}
							}
							break;

						default:
							$item->flink = 'index.php?Itemid=' . $item->id;
							break;
					}

					if ((strpos($item->flink, 'index.php?') !== false) && strcasecmp(substr($item->flink, 0, 4), 'http'))
					{
						$item->flink = Route::_($item->flink, true, $itemParams->get('secure'));
					}
					else
					{
						$item->flink = Route::_($item->flink);
					}

					// We prevent the double encoding because for some reason the $item is shared for menu modules and we get double encoding
					// when the cause of that is found the argument should be removed
					$item->title          = htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8', false);
					$item->menu_icon      = htmlspecialchars($itemParams->get('menu_icon_css', ''), ENT_COMPAT, 'UTF-8', false);
					$item->anchor_css     = htmlspecialchars($itemParams->get('menu-anchor_css', ''), ENT_COMPAT, 'UTF-8', false);
					$item->anchor_title   = htmlspecialchars($itemParams->get('menu-anchor_title', ''), ENT_COMPAT, 'UTF-8', false);
					$item->anchor_rel     = htmlspecialchars($itemParams->get('menu-anchor_rel', ''), ENT_COMPAT, 'UTF-8', false);
					$item->menu_image     = htmlspecialchars($itemParams->get('menu_image', ''), ENT_COMPAT, 'UTF-8', false);
					$item->menu_image_css = htmlspecialchars($itemParams->get('menu_image_css', ''), ENT_COMPAT, 'UTF-8', false);
				}

				if (isset($items[$lastitem]))
				{
					$items[$lastitem]->deeper     = (($start ?: 1) > $items[$lastitem]->level);
					$items[$lastitem]->shallower  = (($start ?: 1) < $items[$lastitem]->level);
					$items[$lastitem]->level_diff = ($items[$lastitem]->level - ($start ?: 1));
				}
			}

			$cache->store($items, $key);
		}

		return $items;
	}

	/**
	 * Get base menu item.
	 *
	 * @param   \Joomla\Registry\Registry  &$params  The module options.
	 *
	 * @return  object
	 *
	 * @since    3.0.2
	 */
	public static function getBase($menu_filter)
	{
		// Get base menu item from parameters
		if ($menu_filter)
		{
			$base = Factory::getApplication()->getMenu()->getItem($menu_filter);
		}
		else
		{
			$base = false;
		}

		// Use active menu item if no base found
		if (!$base)
		{
			$base = self::getActive();
		}

		return $base;
	}

	/**
	 * Get active menu item.
	 *
	 * @param   \Joomla\Registry\Registry  &$params  The module options.
	 *
	 * @return  object
	 *
	 * @since    3.0.2
	 */
	public static function getActive()
	{
		$menu = Factory::getApplication()->getMenu();

		return $menu->getActive() ?: self::getDefault();
	}

	/**
	 * Get default menu item (home page) for current language.
	 *
	 * @return  object
	 */
	public static function getDefault()
	{
		$menu = Factory::getApplication()->getMenu();

		// Look for the home menu
		if (Multilanguage::isEnabled())
		{
			return $menu->getDefault(Factory::getLanguage()->getTag());
		}

		return $menu->getDefault();
	}

}
