<?php
use Joomla\CMS\Language\Text;
use Joomla\CMS\Form\Form;

$profileForm =  Form::getInstance("user.profile", JPATH_ROOT.'/plugins/user/profile/forms/profile.xml', array());
$getUserFieldData = plgSystemUniversal_elements::getUserFieldData();
if(!empty($getUserFieldData->field_data)){
    $profile_fied_data  = !empty(json_decode($getUserFieldData->field_data)->profile)?json_decode($getUserFieldData->field_data)->profile:"";
}else{
    $profile_fied_data  = "";
}

foreach ($profileForm->getFieldsets() as $profile_fieldset) {
    $profile_fields = $profileForm->getFieldset($profile_fieldset->name);
    foreach ($profile_fields as $profile_field) {
        $field_name = str_replace("]","",str_replace("profile[", "", $profile_field->name));
        if($child->props['field_type'] == $field_name){ 
            $field_value = !empty($profile_fied_data->{$field_name})?$profile_fied_data->{$field_name}:'';
            echo '<div class="'.$width.'">';
            if($label == "global"){
            echo '<label class="uk-form-label" for="form-stacked-text">'.str_replace("*","",strip_tags($profile_field->label));
            if($required){
            echo '<span class="uk-text-danger"> *</span>';
            }
            echo '</label>';
            }else if(!empty($label)){
            echo '<label class="uk-form-label" for="form-stacked-text">'.$label;
            if($required){
            echo '<span class="uk-text-danger"> *</span>';
            }
            echo '</label>';
            }
            if($placeholder == "global"){ 
                $placeholder = 'Please enter your '.strtolower(trim(str_replace("&#160;*","",strip_tags(Text::_($profile_field->label)))));
            }else if(!empty($placeholder)){
                $placeholder = $placeholder;
            }
            $approved_status = plgSystemUniversal_elements::checkApprovalField($field_name);
            $disabled = "";
            if(!empty($field_value) && !empty($child->props["field_approval_needed"]) && $approved_status == 0){
                $placeholder = "Waiting for admin approval";
                $disabled = "disabled";
            }
            echo '<div class="uk-form-controls uk-inline uk-display-block">';
            if(strtolower($profile_field->type) == "textarea"){
                if(empty($field_value) && !empty($child->props["field_approval_needed"])){
                    echo '<input type="hidden" name="profile_f['.$field_name.']" value="">';
                    echo '<textarea class="uk-textarea" rows="5" placeholder="'.$placeholder.'" '.$required.' aria-label="Textarea" id="'.$profile_field->name.'" name="'.str_replace("profile","jform[profile]",$profile_field->name).'" '.$disabled.'></textarea>';
                }else if(!empty($field_value) && !empty($child->props["field_approval_needed"]) && $approved_status == 1){
                    echo '<textarea class="uk-textarea" rows="5" placeholder="'.$placeholder.'" '.$required.' aria-label="Textarea" id="'.$profile_field->name.'" name="'.str_replace("profile","jform[profile]",$profile_field->name).'" '.$disabled.'>'.$field_value.'</textarea>';
                }else if(!empty($field_value) && !empty($child->props["field_approval_needed"])){
                    echo '<input type="hidden" name="profile_f['.$field_name.']" value="'.$field_value.'">';
                    echo '<textarea class="uk-textarea" rows="5" placeholder="'.$placeholder.'" '.$required.' aria-label="Textarea" id="'.$profile_field->name.'" name="'.str_replace("profile","jform[profile]",$profile_field->name).'"></textarea>';
                }else{
                    echo '<textarea class="uk-textarea" rows="5" placeholder="'.$placeholder.'" '.$required.' aria-label="Textarea" id="'.$profile_field->name.'" name="'.str_replace("profile","jform[profile]",$profile_field->name).'" '.$disabled.'>'.$field_value.'</textarea>';
                }
            }else{
                if($child->props['invalid_field_icon_custom']){
                echo '<span class="uk-form-icon '.$child->props['invalid_field_color'].'" uk-icon="icon: '.$child->props['invalid_field_icon_custom'].'"></span>';
                }
                if(empty($field_value) && !empty($child->props["field_approval_needed"])){
                    echo '<input type="hidden" name="profile_f['.$field_name.']" value="">';
                    echo '<input class="uk-input" id="'.$profile_field->name.'" '.$required.' name="'.str_replace("profile","jform[profile]",$profile_field->name).'" type="'.strtolower($profile_field->type).'" placeholder="'.$placeholder.'" '.$disabled.'>';
                }else if(!empty($field_value) && !empty($child->props["field_approval_needed"]) && $approved_status == 1){
                    echo '<input class="uk-input" id="'.$profile_field->name.'" '.$required.' name="'.str_replace("profile","jform[profile]",$profile_field->name).'" type="'.strtolower($profile_field->type).'" placeholder="'.$placeholder.'" value="'.$field_value.'" '.$disabled.'>';
                }else if(!empty($field_value) && !empty($child->props["field_approval_needed"])){
                    echo '<input type="hidden" name="profile_f['.$field_name.']" value="'.$field_value.'">';
                    echo '<input class="uk-input" id="'.$profile_field->name.'" '.$required.' name="'.str_replace("profile","jform[profile]",$profile_field->name).'" type="'.strtolower($profile_field->type).'" placeholder="'.$placeholder.'">';
                }else{
                    echo '<input class="uk-input" id="'.$profile_field->name.'" '.$required.' name="'.str_replace("profile","jform[profile]",$profile_field->name).'" type="'.strtolower($profile_field->type).'" placeholder="'.$placeholder.'" value="'.$field_value.'" '.$disabled.'>';
                }
            }
            echo '</div>';
            echo '</div>';
        }
    }
}
?>