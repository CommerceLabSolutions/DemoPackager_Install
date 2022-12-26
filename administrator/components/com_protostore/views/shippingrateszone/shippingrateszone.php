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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\LayoutHelper;

HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('behavior.formvalidator');

/** @var array $vars */
$item = $vars['item'];

?>

<div id="p2s_shippingrateszone_form">
    <form @submit.prevent="saveItem">
        <div class="uk-margin-left">
            <div class="uk-grid" uk-grid="">
                <div class="uk-width-1-1">

                    <div>

                        <nav class="uk-navbar-container uk-padding-small" uk-navbar style="border-radius: 8px">

                            <div class="uk-navbar-left">

                                <span class="uk-navbar-item uk-logo">

	                                    <?= Text::_('COM_PROTOSTORE_ADD_DISCOUNTS_MODAL_EDITING'); ?>  

                                </span>

                            </div>

                            <div class="uk-navbar-right">

                                <button type="submit" @click="andClose = false"
                                        class="uk-button uk-button-default button-success uk-button-small uk-margin-right">
		                            <?= Text::_('JTOOLBAR_APPLY'); ?>
                                </button>
                                <button type="submit" @click="andClose = true"
                                        class="uk-button uk-button-default button-success uk-button-small uk-margin-right">
		                            <?= Text::_('JTOOLBAR_SAVE'); ?>
                                </button>
                                <a class="uk-button uk-button-default uk-button-small "
                                   href="index.php?option=com_protostore&view=shippingratescountries"><?= Text::_('JTOOLBAR_CANCEL'); ?></a>

                            </div>

                        </nav>
                    </div>

                </div>
                <div class="uk-width-2-3">
                    
					<?= LayoutHelper::render('card', array(
						'form'      => $vars['form'],
						'cardStyle' => 'default',
						'cardTitle' => 'COM_PROTOSTORE_ADD_SHIPPING_RATE_MODAL_ZONE_SHIPPING_RATE_DETAILS',
						'cardId'    => 'details',
						'fields'    => array('zone_id', 'weight_from', 'weight_to', 'cost', 'handling_cost', 'published')
					)); ?>


                </div>


                <div class="uk-width-1-3">


                </div>
            </div>

        </div>
    </form>
</div>

