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
use Joomla\CMS\Version;


HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('behavior.formvalidator');

/** @var array $vars */
$item = $vars['item'];


?>

<div id="p2s_customer_form">
    <form @submit.prevent="saveItem">
            <div class="uk-grid" uk-grid="">

                <div class="uk-width-1-1">
                    <div>
                        <nav class="uk-navbar-container uk-flex-wrap uk-padding-small" uk-navbar style="border-radius: 8px">
                            <div class="uk-navbar-left">
                                <span class="uk-navbar-item uk-logo">
                                        <?= Text::_('COM_PROTOSTORE_ADD_DISCOUNTS_MODAL_EDITING'); ?>  {{form.jform_name}}
                                </span>
                            </div>

                            <div class="uk-navbar-right">
                                <button type="submit" @click="andClose = false"
                                        class="uk-button uk-button-default button-success uk-button-small uk-margin-right uk-margin-small-bottom">
                                    <?= Text::_('JTOOLBAR_APPLY'); ?>
                                </button>
                                <button type="submit" @click="andClose = true"
                                        class="uk-button uk-button-default button-success uk-button-small uk-margin-right uk-margin-small-bottom">
                                    <?= Text::_('JTOOLBAR_SAVE'); ?>
                                </button>
                                <a class="uk-button uk-button-default uk-button-small uk-margin-small-bottom"
                                   href="index.php?option=com_protostore&view=customers"><?= Text::_('JTOOLBAR_CANCEL'); ?></a>

                                <div class="uk-inline uk-margin-left uk-margin-small-bottom">
                                    <button class="uk-icon-button" uk-icon="more-vertical" type="button"></button>
                                    <div uk-dropdown="mode: click">
                                        <ul class="uk-nav uk-dropdown-nav">

                                            <li><button @click="launchDeleteDialog" class="uk-button uk-button-text" type="button">Delete This User</button></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </nav>
                    </div>
                </div>




                <div class="uk-width-1-1@m uk-width-1-1@l uk-width-1-1@xl">
                    <?= LayoutHelper::render('customer/order_grid_card', array(
                        'form'      => $vars['form'],
                        'cardStyle' => 'default',
                        'cardTitle' => 'Orders',
                        'cardId'    => 'orders'
                    )); ?>

                </div>


                <div class="uk-width-1-1@s uk-width-1-3@m uk-width-1-3@l uk-width-1-3@xl uk-animation-fade">

                    <?= LayoutHelper::render('card', array(
                        'form'      => $vars['form'],
                        'cardStyle' => 'default',
                        'cardTitle' => 'COM_PROTOSTORE_ORDER_CUSTOMER_DETAILS',
                        'cardId'    => 'details',
                        'fields'    => array('name', 'email', 'j_user_id', 'published'),
                        'field_grid_width' => 'uk-child-width-1-1 uk-child-width-1-2@s uk-child-width-1-1@m uk-child-width-1-1@l uk-child-width-1-1@xl',
                    )); ?>
                </div>

            <div class="uk-width-1-1@s uk-width-2-3@m uk-width-2-3@l uk-width-2-3@xl customer-address uk-animation-fade">

                    <?= LayoutHelper::render('customer/address_grid_card', [
                        'addresses'      => ($item->addresses ? $item->addresses : ''),
                        'cardStyle' => 'default',
                        'cardTitle' => 'Addresses',
                        'cardId'    => 'customer_addresses',
                    ]); ?>

                </div>

            
             </div>

      
    </form>
</div>


