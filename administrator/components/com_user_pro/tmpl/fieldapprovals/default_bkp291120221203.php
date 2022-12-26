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
                        <?php foreach ($this->items as $i => $item) :
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
                                    <input type="button" name="revision" value="Review Changes" class="btn btn-warning" id="open"> 
                                </td>
                                <td>
                                    <input type="button" name="approve" value="Approve" class="btn btn-warning" onclick="changeStatus('approve','1','<?php echo $item->id;?>');">
                                    <input type="button" name="deny" value="Deny" class="btn btn-danger" onclick="changeStatus('deny','2','<?php echo $item->id;?>');">
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

<!-- Rivision Review Modal Start-->

<div class="modal review-modal" id="b">
  <div class="modal_header">
    <h3>Review Changes</h3>
    <a href="javascript:void(0)" class="cancel"><i class="fa fa-times" aria-hidden="true"></i></a>
  </div>
  <div class="content">
        <div class="row">
        <div class="col-lg-6">
            <div class="innerview">
            <h3>Old Changes</h3>
            <?php $coreForm =  Form::getInstance("com_users.user", JPATH_ROOT.'/components/com_users/forms/registration.xml', array());
            $c_fields = plgSystemUniversal_elements::getNewUserFieldData("core_field");
            if(!empty($c_fields)){
                foreach ($coreForm->getFieldsets() as $core_fieldset) {
                    $core_fields = $coreForm->getFieldset($core_fieldset->name);
                    foreach ($core_fields as $core_field) {
                        foreach ($c_fields as $value) {
                           if($core_field->name == $value->field_name){
                                echo '<div class="control-group d-block">';
                                    echo '<div class="control-label">';
                                        echo '<label id="jform_name-lbl" for="jform_name" class="required">';
                                        echo str_replace("*","",strip_tags($core_field->label));
                                        echo '</label>';
                                    echo '</div>';
                                    echo '<div class="controls">';
                                        echo '<input type="text" name="'.$value->field_name.'" id="'.$value->field_name.'" class="form-control" value="'.$value->field_value.'" disabled/>';
                                    echo '</div>';
                                echo '</div>';
                           }
                        }
                    }
                }
            }
            $profileForm =  Form::getInstance("user.profile", JPATH_ROOT.'/plugins/user/profile/forms/profile.xml', array());
            $p_fields = plgSystemUniversal_elements::getNewUserFieldData("profile_field");
            if(!empty($p_fields)){
                foreach ($profileForm->getFieldsets() as $profile_fieldset) {
                    $profile_fields = $profileForm->getFieldset($profile_fieldset->name);
                    foreach ($profile_fields as $profile_field) {
                        foreach ($p_fields as $value) {
                            if($profile_field->name == $value->field_name){
                                echo '<div class="control-group d-block">';
                                    echo '<div class="control-label">';
                                        echo '<label id="jform_name-lbl" for="jform_name" class="required">';
                                        echo str_replace("*","",strip_tags($profile_field->label));
                                        echo '</label>';
                                    echo '</div>';
                                    echo '<div class="controls">';
                                    if($profile_field->type == "text"){
                                        echo '<input type="text" name="'.$value->field_name.'" id="'.$value->field_name.'" class="form-control" value="'.$value->field_value.'" disabled/>';
                                    }else{
                                        echo '<textarea class="form-control" rows="5" aria-label="Textarea" id="'.$value->field_name.'" name="'.$value->field_name.'" disabled>'.$value->field_value.'</textarea>';
                                    }
                                    echo '</div>';
                                echo '</div>';
                           }

                        }
                    }
                }
            }
            $extra =  FieldsHelper::getFields('com_users.user', Factory::getUser(), true);
            $cus_fields = plgSystemUniversal_elements::getNewUserFieldData("custom_field");
            if(!empty($cus_fields)){
                foreach ($extra as $extra_fieldset) { 
                    foreach ($cus_fields as $value) {
                        $type = strtolower($extra_fieldset->type);
                        if($extra_fieldset->name == $value->field_name){
                            echo '<div class="control-group d-block">';
                                echo '<div class="control-label">';
                                    echo '<label id="jform_name-lbl" for="jform_name" class="required">';
                                    echo $extra_fieldset->label;
                                    echo '</label>';
                                echo '</div>';
                                echo '<div class="controls">';
                                if($type == "text" || $type == "textarea" || $type == "password" || $type == "email" || $type == "url" || $type == "tel"){
                                    if($type == "textarea"){
                                        echo '<textarea class="form-control" rows="5" aria-label="Textarea" id="'.$extra_fieldset->field_name.'" name="'.$extra_fieldset->field_name.'" disabled>'.$value->field_value.'</textarea>';
                                    }else{
                                        echo '<input type="'.$type.'" name="'.$extra_fieldset->name.'" id="'.$extra_fieldset->name.'" class="form-control" value="'.$value->field_value.'" disabled/>';
                                    }
                                }else if($type == "radio"){
                                    echo '<fieldset id="jform_com_fields_'.$extra_fieldset->name.'">';
                                    echo '<legend class="visually-hidden">'.$extra_fieldset->name.'</legend>';
                                    echo '<div class="btn-group radio">';
                                    $radios = $extra_fieldset->fieldparams->get('options');
                                    $i = 0;
                                    foreach($radios as $radio){
                                        $checked = $value->field_value == $radio->value?"checked":'';
                                        echo '<input class="btn-check" type="radio" id="jform_com_fields_'.$extra_fieldset->name.$i.'" name="'.$extra_fieldset->name.'" value="'.$radio->value.'" '.$checked.'>';
                                        echo '<label for="jform_com_fields_'.$extra_fieldset->name.$i.'" class="btn btn-outline-secondary">'.$radio->name.'</label>';
                                        $i++;
                                    }
                                    echo '</div>';
                                    echo '</fieldset>';
                                }else if($type == "checkboxes"){
                                    echo '<fieldset id="jform_com_fields_'.$extra_fieldset->name.'" class="checkboxes">';
                                    $checkboxs = $extra_fieldset->fieldparams->get('options');
                                    $i=0;
                                    foreach($checkboxs as $checkbox){
                                        if(!empty($field_data->com_fields->{$extra_fieldset->name}[$i])){
                                            $checked = $value->field_value[$i] == $checkbox->value?"checked":'';
                                        }else{
                                            $checked = "";
                                        }
                                        echo '<legend class="visually-hidden">'.$checkbox->name.'</legend>';
                                        echo '<div class="form-check form-check-inline">';
                                            echo '<input type="checkbox" id="jform_com_fields_'.$extra_fieldset->name.$i.'" name="'.$extra_fieldset->name.'[]" value="'.$checkbox->value.'" class="form-check-input valid form-control-success" aria-invalid="false" '.$checked.'>';
                                            echo '<label for="jform_com_fields_'.$extra_fieldset->name.$i.'" class="form-check-label">'.$checkbox->name.'</label>';
                                        echo '</div>';
                                        $i++;
                                    }
                                    echo '</fieldset>';
                                }else if($type == "list"){
                                    $lists = $extra_fieldset->fieldparams->get('options');
                                    echo '<select id="jform_com_fields_'.$extra_fieldset->name.'" name="'.$extra_fieldset->name.'" class="form-select valid form-control-success" aria-invalid="false">';
                                        foreach($lists as $list){                                                        
                                            if($list->value==$value->field_value){
                                                $selected = "selected";
                                            }else{
                                                $selected = "";
                                            }
                                            echo '<option value="'.$list->value.'" '.$selected.'>'.$list->name.'</option>';
                                        }
                                    echo '</select>';
                                }else if($type == "media"){
                                    echo '<div id="divImageMediaPreview">';
                                    if($value->field_value){
                                    echo '<img src="'.JURI::root().$value->field_value.'">';
                                    }
                                    echo '</div>';
                                }
                                echo '</div>';
                            echo '</div>';
                        }
                    }
                }
            }
            ?>
        </div>
        </div>
        <div class="col-lg-6">
            <div class="innerview border-0">
            <h3>New Changes</h3>
            <?php $coreForm =  Form::getInstance("com_users.user", JPATH_ROOT.'/components/com_users/forms/registration.xml', array());
            $c_fields = plgSystemUniversal_elements::getNewUserFieldData("core_field");
            if(!empty($c_fields)){
                foreach ($coreForm->getFieldsets() as $core_fieldset) {
                    $core_fields = $coreForm->getFieldset($core_fieldset->name);
                    foreach ($core_fields as $core_field) {
                        foreach ($c_fields as $value) {
                           if($core_field->name == $value->field_name){
                                echo '<div class="control-group d-block">';
                                    echo '<div class="control-label">';
                                        echo '<label id="jform_name-lbl" for="jform_name" class="required">';
                                        echo str_replace("*","",strip_tags($core_field->label));
                                        echo '</label>';
                                    echo '</div>';
                                    echo '<div class="controls">';
                                        echo '<input type="text" name="'.$value->field_name.'" id="'.$value->field_name.'" class="form-control" value="'.$value->field_value.'" disabled/>';
                                    echo '</div>';
                                echo '</div>';
                           }
                        }
                    }
                }
            }
            $profileForm =  Form::getInstance("user.profile", JPATH_ROOT.'/plugins/user/profile/forms/profile.xml', array());
            $p_fields = plgSystemUniversal_elements::getNewUserFieldData("profile_field");
            if(!empty($p_fields)){
                foreach ($profileForm->getFieldsets() as $profile_fieldset) {
                    $profile_fields = $profileForm->getFieldset($profile_fieldset->name);
                    foreach ($profile_fields as $profile_field) {
                        foreach ($p_fields as $value) {
                            if($profile_field->name == $value->field_name){
                                echo '<div class="control-group d-block">';
                                    echo '<div class="control-label">';
                                        echo '<label id="jform_name-lbl" for="jform_name" class="required">';
                                        echo str_replace("*","",strip_tags($profile_field->label));
                                        echo '</label>';
                                    echo '</div>';
                                    echo '<div class="controls">';
                                    if($profile_field->type == "text"){
                                        echo '<input type="text" name="'.$value->field_name.'" id="'.$value->field_name.'" class="form-control" value="'.$value->field_value.'" disabled/>';
                                    }else{
                                        echo '<textarea class="form-control" rows="5" aria-label="Textarea" id="'.$value->field_name.'" name="'.$value->field_name.'" disabled>'.$value->field_value.'</textarea>';
                                    }
                                    echo '</div>';
                                echo '</div>';
                           }

                        }
                    }
                }
            }
            $extra =  FieldsHelper::getFields('com_users.user', Factory::getUser(), true);
            $cus_fields = plgSystemUniversal_elements::getNewUserFieldData("custom_field");
            if(!empty($cus_fields)){
                foreach ($extra as $extra_fieldset) { 
                    foreach ($cus_fields as $value) {
                        $type = strtolower($extra_fieldset->type);
                        if($extra_fieldset->name == $value->field_name){
                            echo '<div class="control-group d-block">';
                                echo '<div class="control-label">';
                                    echo '<label id="jform_name-lbl" for="jform_name" class="required">';
                                    echo $extra_fieldset->label;
                                    echo '</label>';
                                echo '</div>';
                                echo '<div class="controls">';
                                if($type == "text" || $type == "textarea" || $type == "password" || $type == "email" || $type == "url" || $type == "tel"){
                                    if($type == "textarea"){
                                        echo '<textarea class="form-control" rows="5" aria-label="Textarea" id="'.$extra_fieldset->field_name.'" name="'.$extra_fieldset->field_name.'" disabled>'.$value->field_value.'</textarea>';
                                    }else{
                                        echo '<input type="'.$type.'" name="'.$extra_fieldset->name.'" id="'.$extra_fieldset->name.'" class="form-control" value="'.$value->field_value.'" disabled/>';
                                    }
                                }else if($type == "radio"){
                                    echo '<fieldset id="jform_com_fields_'.$extra_fieldset->name.'">';
                                    echo '<legend class="visually-hidden">'.$extra_fieldset->name.'</legend>';
                                    echo '<div class="btn-group radio">';
                                    $radios = $extra_fieldset->fieldparams->get('options');
                                    $i = 0;
                                    foreach($radios as $radio){
                                        $checked = $value->field_value == $radio->value?"checked":'';
                                        echo '<input class="btn-check" type="radio" id="jform_com_fields_'.$extra_fieldset->name.$i.'" name="'.$extra_fieldset->name.'" value="'.$radio->value.'" '.$checked.'>';
                                        echo '<label for="jform_com_fields_'.$extra_fieldset->name.$i.'" class="btn btn-outline-secondary">'.$radio->name.'</label>';
                                        $i++;
                                    }
                                    echo '</div>';
                                    echo '</fieldset>';
                                }else if($type == "checkboxes"){
                                    echo '<fieldset id="jform_com_fields_'.$extra_fieldset->name.'" class="checkboxes">';
                                    $checkboxs = $extra_fieldset->fieldparams->get('options');
                                    $i=0;
                                    foreach($checkboxs as $checkbox){
                                        if(!empty($field_data->com_fields->{$extra_fieldset->name}[$i])){
                                            $checked = $value->field_value[$i] == $checkbox->value?"checked":'';
                                        }else{
                                            $checked = "";
                                        }
                                        echo '<legend class="visually-hidden">'.$checkbox->name.'</legend>';
                                        echo '<div class="form-check form-check-inline">';
                                            echo '<input type="checkbox" id="jform_com_fields_'.$extra_fieldset->name.$i.'" name="'.$extra_fieldset->name.'[]" value="'.$checkbox->value.'" class="form-check-input valid form-control-success" aria-invalid="false" '.$checked.'>';
                                            echo '<label for="jform_com_fields_'.$extra_fieldset->name.$i.'" class="form-check-label">'.$checkbox->name.'</label>';
                                        echo '</div>';
                                        $i++;
                                    }
                                    echo '</fieldset>';
                                }else if($type == "list"){
                                    $lists = $extra_fieldset->fieldparams->get('options');
                                    echo '<select id="jform_com_fields_'.$extra_fieldset->name.'" name="'.$extra_fieldset->name.'" class="form-select valid form-control-success" aria-invalid="false">';
                                        foreach($lists as $list){                                                        
                                            if($list->value==$value->field_value){
                                                $selected = "selected";
                                            }else{
                                                $selected = "";
                                            }
                                            echo '<option value="'.$list->value.'" '.$selected.'>'.$list->name.'</option>';
                                        }
                                    echo '</select>';
                                }else if($type == "media"){
                                    echo '<div id="divImageMediaPreview">';
                                    if($value->field_value){
                                    echo '<img src="'.JURI::root().$value->field_value.'">';
                                    }
                                    echo '</div>';
                                }
                                echo '</div>';
                            echo '</div>';
                        }
                    }
                }
            }
            ?>
        </div>
        </div>
         </div>
    </div>
</div>
<!-- Rivision Review Modal End-->
<script type="text/javascript">
$("#open").click(function(){
    $('body').addClass("overlybg");
    $("#a").css("display","block");
    $("#b").css("display","block");
});

$(".cancel").click(function(){
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
                alert('Field Approved');
            }else{
                alert('Field Denied');
            }
            location.reload();
        }
    });
}
</script>