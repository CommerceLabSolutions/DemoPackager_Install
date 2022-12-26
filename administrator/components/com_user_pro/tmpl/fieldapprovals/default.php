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
use Joomla\CMS\Form\Form;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;
HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.multiselect');

// Import CSS
$wa =  $this->document->getWebAssetManager();
$wa->useStyle('com_user_pro.admin')
    ->useScript('com_user_pro.admin');
$lang = Factory::getLanguage();
$lang->load('plg_user_profile', JPATH_ADMINISTRATOR);
$lang->load('com_users', JPATH_SITE);
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
.modal.review-modal{
    left: 50%;
    top: 85px;
    transform: translate(-50%, -0%);
    width: 100%;
    position: fixed;
    z-index: 40001;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0px 0px 30px rgb(0 0 0 / 10%);
    margin: 10px;
 
}

.review-modal .modal_header h3 {
    margin: 0;
    font-size: 18px;
    color: #fff;
}

.review-modal .modal_header{
    border-bottom: 1px solid #ebebeb;
    padding: 15px 18px;
    background: #1f3047;
}

.review-modal .modal_header a{
    color: #c9c9c9;
    position: absolute;
    right: 20px;
    top: 15px;
    transition: 0.5s;
}
.review-modal .modal_header a:hover{
    color: #9b9797;
}

.review-modal .content{
    width: 100%;
    padding: 20px 20px;
    min-height: 173px;
    overflow-y: auto;
}

#first_label{
  padding-top: 30px;
}

#second_label{
  padding-top: 25px;
}
.overlybg {
    overflow: hidden;
}
.overlybg:after {
    content: "";
    width: 100%;
    height: 100vh;
    background: #000000a1;
    position: absolute;
    z-index: 9999;
    transition: 0.8s;
}
.innerview {
    border-right: solid 1px #e7e7e7;
    padding: 10px 40px 14px 0px;
}
.uk-notification-message {background: #32d296 !important;color:#fff !important;}
/****MEDIA QUERIES****/

@media screen and (min-width: 600px){

  .modal.review-modal{
    height: auto;
    left: 50%;
    top: 85px;
    transform: translate(-50%, -0%);
    width: 100%;
    max-width:750px;
  }

  .review-modal.content{
    width: 100%;
  }
}

</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.3/css/uikit.min.css">
<form action="<?php echo Route::_('index.php?option=com_user_pro&view=users'); ?>" method="post"
      name="adminForm" id="adminForm">
    <div class="row">
        <div class="col-md-12">
            <div id="j-main-container" class="j-main-container">
            <?php //echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>

                <div class="clearfix"></div>
                <table class="table table-striped" id="userproList">
                    <thead>
                            <tr>
                                <th scope="col" class="w-13 d-none d-md-table-cell">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'COM_USER_PRO_USERS_USER', 'a.user_id', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col" class="w-10 d-none d-md-table-cell">
                                    <?php echo Text::_('COM_USER_PRO_USERS_HEADING_GROUPS'); ?>
                                </th>
                                <th scope="col" class="w-15 d-none d-xl-table-cell">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'COM_USER_PRO_USERS_FIELD_CHANGE', 'a.field_name', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col" class="w-15 d-none d-xl-table-cell">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'COM_USER_PRO_USERS_CHANGE_DATE', 'a.created_time', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col" class="w-15 d-none d-xl-table-cell">
                                    <?php echo Text::_('COM_USER_PRO_USERS_STATUS'); ?>
                                </th>
                                <th scope="col" class="w-15 d-none d-xl-table-cell">
                                    <?php echo Text::_('COM_USER_PRO_USERS_REVISION'); ?>
                                </th>
                                <th scope="col" class="w-18 d-none d-xl-table-cell">
                                    <?php echo Text::_('COM_USER_PRO_USERS_QUICK_APPROVE'); ?>
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
                        <?php 
                        if($this->items){
                        foreach ($this->items as $i => $item) :
                            $group_names = $this->getUserDisplayedGroups($userId);?>
                            <tr class="row<?php echo $i % 2; ?>">
                                <td>
                                    <?php echo $this->escape($user->name); ?>
                                </td>                                
                                <td>
                                    <?php echo nl2br($group_names, false); ?>
                                </td>
                                <td>
                                    <?php echo $this->escape($item->field_name); ?>
                                </td>
                                <td>
                                   <?php echo date("m/d/y", strtotime(HTMLHelper::_('date', $item->created_time, Text::_('DATE_FORMAT_LC4')))); ?>
                                </td>
                                <td>
                                   <?php if($item->status == 1){ ?>
                                   <span class="badge bg-success rounded-pill p-2">Approved</span>
                                   <?php }else if($item->status == 2){ ?>
                                   <span class="badge bg-danger rounded-pill p-2">Denied</span>
                                   <?php }else{ ?>
                                   <span class="badge bg-warning rounded-pill p-2">Waiting for approval</span>
                                   <?php } ?>
                                </td>
                                <td>
                                    <input type="button" name="revision" value="Review Changes" class="btn btn-warning" onclick="openModal('<?php echo $item->id;?>');"> 
                                </td>
                                <td>
                                    <input type="button" name="approve" value="Approve" class="btn btn-warning" onclick="changeStatus('approve','1','<?php echo $item->id;?>');">
                                    <input type="button" name="deny" value="Deny" class="btn btn-danger" onclick="changeStatus('deny','2','<?php echo $item->id;?>');">
                                </td>
                            </tr>
                        <?php endforeach; }else{ ?>
                        <tr class="row">
                            <td colspan="7">No record found</td>
                        </tr>
                        <?php } ?>
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

<!-- Rivision Review Modal Start-->

<div class="modal review-modal" id="b">
  <div class="modal_header">
    <h3>Review Changes</h3>
    <a href="javascript:void(0)" class="cancel"><i class="fa fa-times" aria-hidden="true"></i></a>
  </div>
  <div class="content">
        <div class="row" id="ajaxdata"></div>
  </div>
</div>
<!-- Rivision Review Modal End-->

<script type="text/javascript">
$(".cancel").click(function(){
    $("#ajaxdata").html('');
    $('body').removeClass("overlybg");
    $("#a").fadeOut();
    $("#b").fadeOut();
});

function changeStatus(type,value,id){
    $.ajax({
        type:'POST',
        url:"index.php?option=com_user_pro&controller=fieldapprovals&task=changeStatus&format=raw",
        data: {status_type:value,id:id},
        success:function(response){
            if(value == 1){
                UIkit.notification({message: 'Field Approved', status: 'success'});
            }else{
                UIkit.notification({message: 'Field Denied', status: 'warning'});
            }
            setTimeout(function() {
                location.reload();
            }, 5000);
        }
    });
}

function openModal(id){
    $('body').addClass("overlybg");
    $("#a").css("display","block");
    $("#b").css("display","block");
    $.ajax({
        type:'POST',
        url:"index.php?option=com_user_pro&controller=fieldapprovals&task=openModal&format=raw",
        data: {id:id},
        success:function(response){
            $("#ajaxdata").html(response);
        }
    });
}
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.3/js/uikit.min.js"></script>