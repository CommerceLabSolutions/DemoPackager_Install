<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

// init vars
/** @var array $vars */

?>
<div id="p2s_dashboard">


<!--Dashboard counting section -->
<div class="uk-section-default uk-container uk-section uk-section-xsmall uk-margin-bottom pt-0">
      <?= LayoutHelper::render('dashboard/dashboard_count', array(
         'total_sales'    => $vars['total_sales'],
         'total_orders'   => $vars['total_orders'],
         'total_customers'=> $vars['total_customers'],
      )); ?>   
</div>
<!--Dashboard counting section -->



 <div class="uk-grid uk-grid-match" uk-grid>

<!-- SALES IN PRODUCT CATEGORIES -->
        <div class="uk-width-1-1@s uk-width-1-3@m uk-width-1-4@l uk-first-column">
			<?= LayoutHelper::render('dashboard/charts/sales_in_category', array(
				'categories'    => $vars['categories'],
				'colours'       => $vars['colours'],
				'categorySales' => $vars['categorySales'],
			)); ?>

        </div>
<!-- SALES IN PRODUCT CATEGORIES -->


<!-- LATEST ORDERS -->
        <div class="uk-width-1-1@s uk-width-2-3@m uk-width-3-4@l">
			<?= LayoutHelper::render('dashboard/latest_orders', array(
				'orders' => $vars['orders']
			)); ?>
        </div>
<!-- LATEST ORDERS -->

 </div>





<div class="uk-grid uk-grid-match" uk-grid>

<!-- BEST SELLERS -->
        <div class="uk-width-1-1@s uk-width-1-3@m uk-width-1-4@l uk-first-column">
			<?= LayoutHelper::render('dashboard/bestsellers', array(
				'bestSellers' => $vars['bestSellers']
			)); ?>

        </div>
<!-- BEST SELLERS -->


<!-- ORDER STATS -->
        <div class="uk-width-1-1@s uk-width-2-3@m uk-width-3-4@l order-stats uk-first-column">
			<?= LayoutHelper::render('dashboard/order_stats', array(
				'orderStats' => $vars['orderStats']
			)); ?>
        </div>
<!-- ORDER STATS -->

</div>




<div class="uk-grid uk-grid-match" uk-grid>

<!-- SALES CHART -->
    	<div class="uk-width-1-1@s uk-width-1-1@m uk-width-1-1@l uk-first-column">
			<?= LayoutHelper::render('dashboard/charts/sales', array(
				'currencysymbol' => $vars['currencysymbol'],
				'monthsSales'    => $vars['monthsSales'],
				'months'         => $vars['months'],
			)); ?>

        </div>
<!-- SALES CHART -->

</div>

</div>
<script>


</script>
