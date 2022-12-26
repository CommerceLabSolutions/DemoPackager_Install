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
$loggeduser = Factory::getUser();
$field_data = !empty($this->item->field_data)?json_decode($this->item->field_data):'';
$image_data = !empty($this->item->image_data)?json_decode($this->item->image_data):'';
$yootheme_data = !empty($this->item->yoo_theme_data)?json_decode($this->item->yoo_theme_data):'';
$coreForm =  Form::getInstance("com_users.user", JPATH_ROOT.'/components/com_users/forms/registration.xml', array());
$profileForm =  Form::getInstance("user.profile", JPATH_ROOT.'/plugins/user/profile/forms/profile.xml', array());
$extra =  FieldsHelper::getFields('com_users.user', Factory::getUser(), true);
?>
<style type="text/css">
.file-drop-area{
    position: relative;
    max-width: 100%;
    padding: 25px;
    border: 1px dashed rgb(133 130 130 / 40%);
    border-radius: 3px;
    transition: .2s;
    text-align: center;
}
.choose-file-button{
    font-size: 15px;
    text-transform: uppercase;
    font-weight: 500;
    letter-spacing: 2px;
    color: #263c59;
    display: block;
    width: 100%;
}
.file-message{
    font-size: 14px;
    font-weight: 300;
    line-height: 1.4;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    color: #a79f9f;
}
.file-input{
  position: absolute;
  left: 0;
  top:0;
  height: 100%;
  width: 100%;
  cursor: pointer;
  opacity: 0;
}
#divImageMediaPreview img {
    min-width: 230px;
    height: 310px;
    max-width: 500px;
}
#divImageMediaPreview {
    text-align: center;
    background: #f9f9f9;
    height: 350px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
<div class="main-card">
        <?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => 'user_fields', 'recall' => true, 'breakpoint' => 768]); ?>
        
        <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'user_fields', Text::_('COM_USER_PRO_USER_FIELDS')); ?>
            <fieldset class="options-form">
                <legend><?php echo Text::_('COM_USER_PRO_USER_FIELDS'); ?></legend>
                <div class="form-grid">
                    <?php 
                    if(!empty($yootheme_data)){
                    foreach ($yootheme_data as $yoo){ 
                    //Field required setting
                    $required = $yoo->field_required?"required":'';

                    //Field label setting
                    if($yoo->field_label_show_use_global){
                       $label = "global"; 
                    }else{
                        if($yoo->field_label && $yoo->field_label_show){
                           $label = $yoo->field_label;  
                        }else{
                           $label = "";
                        }
                    }

                    //Field placeholder setting
                    if($yoo->field_placeholder_show_use_global){
                       $placeholder = "global"; 
                    }else{
                        $placeholder = $yoo->field_placeholder;  
                    }
                    if(!empty($field_data)){ 
                    foreach ($coreForm->getFieldsets() as $core_fieldset) {
                    $core_fields = $coreForm->getFieldset($core_fieldset->name);
                    foreach ($core_fields as $core_field) {
                        $core_field_label = str_replace("*","",strip_tags($core_field->label));
                    if(!empty($field_data->{$core_field->name}) && $core_field->name == $yoo->field_type){ ?>
                    <div class="control-group">
                        <div class="control-label">
                            <label id="jform_<?php echo $core_field->name;?>-lbl" for="jform_<?php echo $core_field->name;?>" class="required">
                                <?php if($label == "global"){echo $core_field_label;}else{echo $label;}?>
                            <?php if($required){ ?>
                            <span class="star" aria-hidden="true">&nbsp;*</span>
                            <?php } ?>
                            </label>
                        </div>
                        <div class="controls">                        
                            <input type="<?php echo strtolower($core_field->type);?>" name="jform[<?php echo $core_field->name;?>]" id="jform_<?php echo $core_field->name;?>" value="<?php echo $field_data->{$core_field->name};?>" class="form-control <?php echo $required;?>" <?php echo $required;?>>
                        </div>
                    </div>
                    <?php } } } 
                    foreach ($profileForm->getFieldsets() as $profile_fieldset) {
                    $profile_fields = $profileForm->getFieldset($profile_fieldset->name);
                    foreach ($profile_fields as $profile_field) {
                        $profile_field_name = str_replace("]","",str_replace("profile[", "", $profile_field->name));
                        $profile_field_label = str_replace("*","",strip_tags($profile_field->label));
                    if(!empty($field_data->profile->{$profile_field_name}) && $profile_field_name == $yoo->field_type){ ?>
                    <div class="control-group">
                        <div class="control-label">
                            <label id="jform_<?php echo $profile_field_name;?>-lbl" for="jform_<?php echo $profile_field_name;?>" class="required">
                                <?php if($label == "global"){echo $profile_field_label;}else{echo $label;}?>
                            <?php if($required){ ?>
                            <span class="star" aria-hidden="true">&nbsp;*</span>
                            <?php } ?>
                            </label>
                        </div>
                        <div class="controls">
                            <?php if(strtolower($profile_field->type) == "textarea"){ ?>
                                <textarea name="jform[profile][<?php echo $profile_field_name;?>]" id="jform_profile_<?php echo $profile_field_name;?>" cols="30" rows="5" class="form-control valid form-control-success" aria-invalid="false" <?php echo $required;?>><?php echo $field_data->profile->{$profile_field_name};?></textarea>
                            <?php }else{ ?>
                                <input type="<?php echo strtolower($profile_field->type);?>" name="jform[profile][<?php echo $profile_field_name;?>]" id="jform_<?php echo $profile_field_name;?>" value="<?php echo $field_data->profile->{$profile_field_name};?>" class="form-control <?php echo $required;?>" <?php echo $required;?>>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } } } } } } ?>
                </div>
            </fieldset>
        <?php echo HTMLHelper::_('uitab.endTab'); ?>
        <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'custom_fields', Text::_('COM_USER_PRO_USER_CUSTOM_FIELDS')); ?>
            <fieldset class="options-form">
                <legend><?php echo Text::_('COM_USER_PRO_USER_CUSTOM_FIELDS'); ?></legend>
                <div class="form-grid">
                   <?php 
                    if(!empty($yootheme_data)){
                    foreach ($yootheme_data as $yoo){ 
                        //Field required setting
                        $required = $yoo->field_required?"required":'';

                        //Field label setting
                        if($yoo->field_label_show_use_global){
                           $label = "global"; 
                        }else{
                            if($yoo->field_label && $yoo->field_label_show){
                               $label = $yoo->field_label;  
                            }else{
                               $label = "";
                            }
                        }

                        //Field placeholder setting
                        if($yoo->field_placeholder_show_use_global){
                           $placeholder = "global"; 
                        }else{
                            $placeholder = $yoo->field_placeholder;  
                        }
                        if(!empty($field_data)){ 
                            foreach ($extra as $extra_fieldset) {
                                $type = strtolower($extra_fieldset->type);
                                $extra_field_label = str_replace("*","",strip_tags($extra_fieldset->label));
                                if($extra_fieldset->name == $yoo->field_type){
                                    echo '<div class="control-group">';
                                        echo '<div class="control-label">';
                                            echo '<label id="jform_'.$extra_fieldset->name.'-lbl" for="jform_'.$extra_fieldset->name.'" class="'.$required.'">';
                                                if($label == "global"){echo $extra_field_label;}else{echo $label;}
                                                if($required){
                                                    echo '<span class="star" aria-hidden="true">&nbsp;*</span>';
                                                } 
                                            echo '</label>';
                                        echo '</div>';
                                        echo '<div class="controls">';
                                            if($type == "text" || $type == "textarea" || $type == "password" || $type == "email" || $type == "url" || $type == "tel"){
                                                if($type == "textarea"){ 
                                                    echo '<textarea class="form-control" rows="5" placeholder="'.$placeholder.'" '.$required.' aria-label="Textarea" id="jform_'.$extra_fieldset->name.'" name="jform[com_fields]['.$extra_fieldset->name.']">'.$field_data->com_fields->{$extra_fieldset->name}.'</textarea>';
                                                }else{ 
                                                    echo '<input type="'.$type.'" name="jform[com_fields]['.$extra_fieldset->name.']" id="jform_'.$extra_fieldset->name.'" value="'.$field_data->com_fields->{$extra_fieldset->name}.'" class="form-control '.$required.'" '.$required.'>';
                                                }
                                            }else if($type == "radio"){
                                                echo '<fieldset id="jform_com_fields_'.$extra_fieldset->name.'">';
                                                echo '<legend class="visually-hidden">'.$extra_fieldset->name.'</legend>';
                                                echo '<div class="btn-group radio">';
                                                $radios = $extra_fieldset->fieldparams->get('options');
                                                $i=0;
                                                foreach($radios as $radio){
                                                    $checked = $field_data->com_fields->{$extra_fieldset->name} == $radio->value?"checked":'';
                                                    echo '<input class="btn-check" type="radio" id="jform_com_fields_'.$extra_fieldset->name.$i.'" name="jform[com_fields]['.$extra_fieldset->name.']" value="'.$radio->value.'" '.$checked.'>';
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
                                                        $checked = $field_data->com_fields->{$extra_fieldset->name}[$i] == $checkbox->value?"checked":'';
                                                    }else{
                                                        $checked = "";
                                                    }
                                                    echo '<legend class="visually-hidden">'.$checkbox->name.'</legend>';
                                                    echo '<div class="form-check form-check-inline">';
                                                        echo '<input type="checkbox" id="jform_com_fields_'.$extra_fieldset->name.$i.'" name="jform[com_fields]['.$extra_fieldset->name.'][]" value="'.$checkbox->value.'" class="form-check-input valid form-control-success" aria-invalid="false" '.$checked.'>';
                                                        echo '<label for="jform_com_fields_'.$extra_fieldset->name.$i.'" class="form-check-label">'.$checkbox->name.'</label>';
                                                    echo '</div>';
                                                    $i++;
                                                }
                                                echo '</fieldset>';
                                            }else if($type == "list"){
                                                $lists = $extra_fieldset->fieldparams->get('options');
                                                echo '<select id="jform_com_fields_'.$extra_fieldset->name.'" name="jform[com_fields]['.$extra_fieldset->name.']" class="form-select valid form-control-success" aria-invalid="false">';
                                                    foreach($lists as $list){                                                        
                                                        if($list->value==$field_data->com_fields->{$extra_fieldset->name}){
                                                            $selected = "selected";
                                                        }else{
                                                            $selected = "";
                                                        }
                                                        echo '<option value="'.$list->value.'" '.$selected.'>'.$list->name.'</option>';
                                                    }
                                                echo '</select>';
                                            }else if($type == "media"){
                                                echo '<div class="file-drop-area">';
                                                    echo '<span class="choose-file-button">Choose Files</span>';
                                                    echo '<span class="file-message">or drag and drop files here</span>';
                                                    echo '<input type="file" class="file-input" accept=".jfif,.jpg,.jpeg,.png,.gif" multiple>';
                                                echo '</div>';
                                                echo '<div id="divImageMediaPreview">';
                                                if($image_data->{$extra_fieldset->name}){
                                                echo '<img src="'.JURI::root().$image_data->{$extra_fieldset->name}.'">';
                                                }
                                                echo '</div>';
                                            }
                                        echo '</div>';
                                    echo '</div>';
                                }
                            }
                        }
                    }
                    }
                    ?>
                </div>
            </fieldset>

        <?php echo HTMLHelper::_('uitab.endTab'); ?>
        
        <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'user_activity_log', Text::_('COM_USER_PRO_USER_ACTIVITY_LOG')); ?>
            <fieldset class="options-form">
                <legend><?php echo Text::_('COM_USER_PRO_USER_ACTIVITY_LOG'); ?></legend>
                <div class="form-grid">
                    <form action="<?php echo Route::_('index.php?option=com_user_pro&view=dashboard'); ?>" method="post"
      name="adminForm" id="adminForm">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="j-main-container" class="j-main-container">
                                <?php //echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>

                                    <div class="clearfix"></div>
                                    <table class="table table-striped" id="userproList">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="w-12 d-none d-md-table-cell">
                                                    <?php echo Text::_('COM_USER_PRO_USERS_NAME'); ?>
                                                </th>
                                                <th scope="col" class="w-12 d-none d-md-table-cell">
                                                    <?php echo Text::_('COM_USER_PRO_USERS_LOG_MESSAGE'); ?>
                                                </th>
                                                <th scope="col" class="w-12 d-none d-md-table-cell">
                                                    <?php echo Text::_('COM_USER_PRO_USERS_LOG_DATE'); ?>
                                                </th>                                                                      
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($this->items as $i => $item) : ?>
                                                <tr class="row<?php echo $i % 2; ?>">
                                                    <td width="20%" class="break-word d-none d-md-table-cell">
                                                        <?php echo $this->escape($item->name); ?>
                                                    </td>
                                                    <td width="60%" class="break-word d-none d-md-table-cell">
                                                        <?php echo $this->escape(str_replace("{{name}}",$item->name,$item->log_message)); ?>
                                                    </td>
                                                    <td width="20%" class="break-word d-none d-md-table-cell">
                                                        <?php echo $this->escape($item->created_time); ?>
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
                </div>
            </fieldset>

        <?php echo HTMLHelper::_('uitab.endTab'); ?>
        <?php echo HTMLHelper::_('uitab.endTabSet'); ?>
</div>
<script type="text/javascript">
$(document).on('change', '.file-input', function() {
    var filesCount = $(this)[0].files.length;
    var textbox = $(this).prev();
    if (filesCount === 1) {
        var fileName = $(this).val().split('\\').pop();
        textbox.text(fileName);
    } else {
        textbox.text(filesCount + ' files selected');
    }
    if (typeof (FileReader) != "undefined") {
        var dvPreview = $("#divImageMediaPreview");
        dvPreview.html("");            
        $($(this)[0].files).each(function () {
            var file = $(this);                
            var reader = new FileReader();
            reader.onload = function (e) {
                var img = $("<img />");
                // img.attr("style", "width: 400px; height:250px; padding: 10px");
                img.attr("src", e.target.result);
                dvPreview.append(img);
            }
            reader.readAsDataURL(file[0]);                
        });
    } else {
    alert("This browser does not support HTML5 FileReader.");
    }
});
</script>