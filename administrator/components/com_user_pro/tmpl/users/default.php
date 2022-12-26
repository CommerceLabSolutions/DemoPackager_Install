<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_User_pro
 * @author     demowebflow webflow <demowebflowwebflow@gmail.com>
 * @copyright  2022 demowebflow webflow
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;


use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\String\PunycodeHelper;
use \Joomla\CMS\Language\Text;
use Joomla\CMS\Session\Session;

HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.multiselect');

// Import CSS
$wa =  $this->document->getWebAssetManager();
$wa->useStyle('com_user_pro.admin')
    ->useScript('com_user_pro.admin');

$user      = Factory::getApplication()->getIdentity();
$userId    = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
$canOrder  = $user->authorise('core.edit.state', 'com_user_pro');
$saveOrder = $listOrder == 'a.ordering';
$loggeduser = Factory::getUser();
$mfa        = PluginHelper::isEnabled('multifactorauth');
if ($saveOrder)
{
    $saveOrderingUrl = 'index.php?option=com_user_pro&task=userpros.saveOrderAjax&tmpl=component&' . Session::getFormToken() . '=1';
    HTMLHelper::_('draggablelist.draggable');
}

?>
<style type="text/css">
.com_user_pro .filter-search-actions.btn-group{display: none;}
.com_user_pro .ordering-select{display: none;}
</style>
<form action="<?php echo Route::_('index.php?option=com_user_pro&view=users'); ?>" method="post"
      name="adminForm" id="adminForm">
    <div class="row">
        <div class="col-md-12">
            <div id="j-main-container" class="j-main-container">
            <?php echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>

                <div class="clearfix"></div>
                <table class="table table-striped" id="userproList">
                    <thead>
                            <tr>
                                <td class="w-1 text-center">
                                    <?php echo HTMLHelper::_('grid.checkall'); ?>
                                </td>
                                <th scope="col" class="w-15 d-none d-md-table-cell">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'COM_USER_PRO_USERS_NAME', 'a.name', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col" class="w-15 d-none d-md-table-cell">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'COM_USER_PRO_USERS_USERNAME', 'a.username', $listDirn, $listOrder); ?>
                                </th>
                                <!-- <th scope="col" class="w-5 text-center d-md-table-cell">
                                    <?php //echo HTMLHelper::_('searchtools.sort', 'COM_USER_PRO_USERPROS_HEADING_ENABLED', 'a.block', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col" class="w-5 text-center d-md-table-cell">
                                    <?php //echo HTMLHelper::_('searchtools.sort', 'COM_USER_PRO_USERPROS_HEADING_ACTIVATED', 'a.activation', $listDirn, $listOrder); ?>
                                </th> -->
                                <?php if ($mfa) : ?>
                                <th scope="col" class="w-5 text-center d-none d-md-table-cell">
                                    <?php echo Text::_('COM_USER_PRO_USERPROS_HEADING_MFA'); ?>
                                </th>
                                <?php endif; ?>
                                <th scope="col" class="w-12 d-none d-md-table-cell">
                                    <?php echo Text::_('COM_USER_PRO_USERS_HEADING_GROUPS'); ?>
                                </th>
                                <th scope="col" class="w-25 d-none d-xl-table-cell">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'COM_USER_PRO_USERS_EMAIL', 'a.email', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col" class="w-12 d-none d-xl-table-cell">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'COM_USER_PRO_USERS_HEADING_LAST_VISIT_DATE', 'a.lastvisitDate', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col" class="w-12 d-none d-xl-table-cell">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'COM_USER_PRO_USERS_HEADING_REGISTRATION_DATE', 'a.registerDate', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col" class="w-5 d-none d-md-table-cell">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col" class="w-5 d-none d-md-table-cell">
                                   <?php echo Text::_("Action");?>
                                </th>
                            </tr>
                        </thead>
                    <tfoot>
                    <tr>
                        <td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
                            <?php echo $this->pagination->getListFooter(); ?>
                        </td>
                    </tr>
                    </tfoot>
                    <tbody>
                        <?php foreach ($this->items as $i => $item) :
                            $canEdit   = $this->canDo->get('core.edit');
                            $canChange = $loggeduser->authorise('core.edit.state', 'com_users');

                            // If this group is super admin and this user is not super admin, $canEdit is false
                            if ((!$loggeduser->authorise('core.admin')) && Access::check($item->id, 'core.admin')) {
                                $canEdit   = false;
                                $canChange = false;
                            }
                            ?>
                            <tr class="row<?php echo $i % 2; ?>">
                                <td class="text-center">
                                    <?php if ($canEdit || $canChange) : ?>
                                        <?php echo HTMLHelper::_('grid.id', $i, $item->id, false, 'cid', 'cb', $item->name); ?>
                                    <?php endif; ?>
                                </td>
                                <th scope="row">
                                    <div class="name break-word">
                                    <?php if ($canEdit) : ?> 
                                        <a href="<?php echo Route::_('index.php?option=com_users&task=user.edit&id='.(int) $item->id); ?>">
                                            <?php echo $this->escape($item->name); ?>
                                        </a>
                                    <?php else : ?>
                                        <?php echo $this->escape($item->name); ?>
                                    <?php endif; ?>
                                    </div>                                    
                                    <?php if ($item->requireReset == '1') : ?>
                                        <span class="badge bg-warning text-dark"><?php echo Text::_('COM_USERS_PASSWORD_RESET_REQUIRED'); ?></span>
                                    <?php endif; ?>
                                </th>
                                <td class="break-word d-none d-md-table-cell">
                                    <?php echo $this->escape($item->username); ?>
                                </td>
                                <!-- <td class="text-center d-md-table-cell">
                                    <?php //$self = $loggeduser->id == $item->id; ?>
                                    <?php //if ($canChange) : ?>
                                        <?php //echo HTMLHelper::_('jgrid.state', HTMLHelper::_('users.blockStates', $self), $item->block, $i, 'users.', !$self); ?>
                                    <?php //else : ?>
                                        <?php //echo HTMLHelper::_('jgrid.state', HTMLHelper::_('users.blockStates', $self), $item->block, $i, 'users.', false); ?>
                                    <?php //endif; ?>
                                </td>
                                <td class="text-center d-md-table-cell">
                                    <?php
                                    //$activated = empty($item->activation) ? 0 : 1;
                                    //echo HTMLHelper::_('jgrid.state', HTMLHelper::_('users.activateStates'), $activated, $i, 'users.', (bool) $activated);
                                    ?>
                                </td> -->
                                <?php if ($mfa) : ?>
                                <td class="text-center d-none d-md-table-cell">
                                    <span class="tbody-icon">
                                    <?php if ($item->mfaRecords > 0 || !empty($item->otpKey)) : ?>
                                        <span class="icon-check" aria-hidden="true" aria-describedby="tip-mfa<?php echo $i; ?>"></span>
                                        <div role="tooltip" id="tip-mfa<?php echo $i; ?>">
                                            <?php echo Text::_('COM_USERS_MFA_ACTIVE'); ?>
                                        </div>
                                    <?php else : ?>
                                        <span class="icon-times" aria-hidden="true" aria-describedby="tip-mfa<?php echo $i; ?>"></span>
                                        <div role="tooltip" id="tip-mfa<?php echo $i; ?>">
                                            <?php echo Text::_('COM_USERS_MFA_NOTACTIVE'); ?>
                                        </div>
                                    <?php endif; ?>
                                    </span>
                                </td>
                                <?php endif; ?>
                                <td class="d-none d-md-table-cell">
                                    <?php if (substr_count($item->group_names, "\n") > 1) : ?>
                                        <span tabindex="0"><?php echo Text::_('COM_USERS_USERS_MULTIPLE_GROUPS'); ?></span>
                                        <div role="tooltip" id="tip<?php echo $i; ?>">
                                            <strong><?php echo Text::_('COM_USERS_HEADING_GROUPS'); ?></strong>
                                            <ul><li><?php echo str_replace("\n", '</li><li>', $item->group_names); ?></li></ul>
                                        </div>
                                    <?php else : ?>
                                        <?php echo nl2br($item->group_names, false); ?>
                                    <?php endif; ?>
                                    <!-- <a  class="btn btn-sm btn-secondary"
                                        href="<?php //echo Route::_('index.php?option=com_users&view=debuguser&user_id=' . (int) $item->id); ?>">
                                        <?php //echo Text::_('COM_USER_PRO_USERPROS_DEBUG_PERMISSIONS'); ?>
                                    </a> -->
                                </td>
                                <td class="d-none d-xl-table-cell break-word">
                                    <?php echo PunycodeHelper::emailToUTF8($this->escape($item->email)); ?>
                                </td>
                                <td class="d-none d-xl-table-cell">
                                    <?php if ($item->lastvisitDate !== null) : ?>
                                        <?php echo HTMLHelper::_('date', $item->lastvisitDate, Text::_('DATE_FORMAT_LC6')); ?>
                                    <?php else : ?>
                                        <?php echo Text::_('JNEVER'); ?>
                                    <?php endif; ?>
                                </td>
                                <td class="d-none d-xl-table-cell">
                                    <?php echo HTMLHelper::_('date', $item->registerDate, Text::_('DATE_FORMAT_LC6')); ?>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <?php echo (int) $item->id; ?>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <a href="<?php echo Route::_('index.php?option=com_user_pro&view=users&layout=profile&id='.$item->id); ?>" class="btn btn-primary">User Activity Log</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                </table>

                <input type="hidden" name="task" value=""/>
                <input type="hidden" name="boxchecked" value="0"/>
                <input type="hidden" name="list[fullorder]" value="<?php echo $listOrder; ?> <?php echo $listDirn; ?>"/>
                <?php echo HTMLHelper::_('form.token'); ?>
            </div>
        </div>
    </div>
</form>