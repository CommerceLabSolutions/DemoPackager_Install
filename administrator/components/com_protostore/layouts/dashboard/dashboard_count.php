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
use Protostore\Order\Order;

$data = $displayData;

?>
<div class="uk-grid" uk-grid>

      <div class="uk-width-1-3@s uk-width-1-3@m uk-width-1-3@l">
         <div class="total-sales-main">
            <div class="customer-content"><?= $data["total_sales"];?></div>
            <div class="customericon uk-flex uk-flex-top"><i class="fal fa-analytics"></i><p>Total Sales</p></div>
         </div>
      </div>

      <div class="uk-width-1-3@s uk-width-1-3@m uk-width-1-3@l">
         <div class="total-order-main">
            <div class="customer-content"><?= $data["total_orders"];?></div>
            <div class="customericon uk-flex uk-flex-top"><i class="fal fa-bags-shopping"></i><p>Total Orders</p></div>
         </div>
      </div>



      <div class="uk-width-1-3@s uk-width-1-3@m uk-width-1-3@l">
         <div class="total-customers">
            <div class="customer-content"><?= $data["total_customers"];?></div>
            <div class="customericon uk-flex uk-flex-top"> <i class="fal fa-user"></i><p> Total Customers</p></div>
         </div>
      </div>



   </div>