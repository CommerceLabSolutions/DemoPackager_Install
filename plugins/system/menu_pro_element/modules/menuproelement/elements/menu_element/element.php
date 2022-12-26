<?php

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;

use CommerceLabShop\Utilities\Utilities;
use CommerceLabShop\Config\ConfigFactory;



return [

	// Define transforms for the element node
	'transforms' => [


		// The function is executed before the template is rendered
		'render' => function ($node, array $params) {


			// sort out how we're doing categories.
			// if the setting for "current category as root" is enabled, then...
			// if ($node->props['filter_type'] == 'categories')
			// {

			// 	// get the input
			// 	$input = Factory::getApplication()->input;

			// 	// make sure we're actually in a content category
			// 	if ($input->get('option') == 'com_content' && $input->get('view') == 'category')
			// 	{

			// 		// get the current cat id
			// 		$catid = $input->get('id');

			// 		// set a root category value, to make usre the search doesn't "break out"
			// 		$node->props['root_category'] = $catid;
			// 		// populate the category list using the current cat id.
			// 		$node->props['list'] = Utilities::getCatList($catid);

			// 		//build cat id list:
			// 		$catIdList = array();
			// 		foreach ($node->props['list'] as $cat)
			// 		{
			// 			$catIdList[] = $cat->id;
			// 		}
			// 		$catIdList[]              = $catid;
			// 		$node->props['catIdList'] = $catIdList;

			// 		// populate the initial product list using the current cat id and its children.
			// 		// $node->props['products'] = \CommerceLabShop\Product\ProductFactory::getList(0,0,$catIdList);

			// 	}
			// 	else
			// 	{

			// 		// else, populate list using the selected root category.
			// 		$node->props['list'] = Utilities::getCatList($node->props['root_category']);

			// 		//build cat id list:
			// 		$catIdList = array();
			// 		foreach ($node->props['list'] as $cat)
			// 		{
			// 			$catIdList[] = $cat->id;
			// 		}
			// 		$catIdList[]              = $node->props['root_category'];
			// 		$node->props['catIdList'] = $catIdList;

			// 		// populate the initial product list using the catid and its children.
			// 		//$node->props['products'] = Utilities::getProductsByCategories($catIdList);
			// 	}

			// 	// if we're not rendering the current category as root, then load everything
			// }
			// else
			// {
				
			// 	$node->props['list'] = Utilities::getCatList($node->props['root_category']);
				
			// 	//build cat id list:
			// 	$catIdList = array();
			// 	foreach ($node->props['list'] as $cat)
			// 	{
			// 		$catIdList[] = $cat->id;
			// 	}
			// 	$catIdList[]              = $node->props['root_category'];
			// 	$node->props['catIdList'] = $catIdList;
				
			// 	$node->props['products'] = Utilities::getProductsByCategories($catIdList);
			// }


			// populate the tags
			//$node->props['tags'] = Utilities::getTags();

			// now sort out the grid layout
			// op means opposite
			// if ($node->props['show_sidebar'])
			// {
			// 	// if we have a sidebar, the do the calculation.
			// 	switch ($node->props['grid_layout'])
			// 	{
			// 		case 'halfs':
			// 			$node->props['grid_width']    = '1-2@m';
			// 			$node->props['grid_width_op'] = '1-2@m';
			// 			break;
			// 		case 'thirds':
			// 			$node->props['grid_width']    = '2-3@m';
			// 			$node->props['grid_width_op'] = '1-3@m';
			// 			break;
			// 		case 'quarters':
			// 			$node->props['grid_width']    = '3-4@m';
			// 			$node->props['grid_width_op'] = '1-4@m';
			// 			break;
			// 		case 'fifths32':
			// 			$node->props['grid_width']    = '3-5@m';
			// 			$node->props['grid_width_op'] = '2-5@m';
			// 			break;
			// 		case 'fifths41':
			// 			$node->props['grid_width']    = '4-5@m';
			// 			$node->props['grid_width_op'] = '1-5@m';
			// 			break;
			// 		default:
			// 			$node->props['grid_width']    = '2-3@m';
			// 			$node->props['grid_width_op'] = '1-3@m';
			// 			break;
			// 	}
			// }
			// else
			// {
			// 	$node->props['grid_width'] = '1-1';
			// }

			// sanitise the page_sizes input
			// preg_match_all('/\d+/', $node->props['page_sizes'], $page_sizes);
			// $node->props['page_sizes'] = $page_sizes[0];

			// $config                      = ConfigFactory::get();

			// $node->props['checkoutlink'] = Route::_('index.php?Itemid=' . $config->get('checkout_page_url'));


		},

	]

];

?>
