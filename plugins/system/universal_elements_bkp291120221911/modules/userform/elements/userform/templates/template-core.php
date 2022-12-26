<?php
use Joomla\CMS\Language\Text;
use Joomla\CMS\Form\Form;
$coreForm =  Form::getInstance("com_users.user", JPATH_ROOT.'/components/com_users/forms/registration.xml', array("control" => "jform"));
$getUserFieldData = plgSystemUniversal_elements::getUserFieldData();
$core_fied_data  = !empty($getUserFieldData->field_data)?json_decode($getUserFieldData->field_data):'';
foreach ($coreForm->getFieldsets() as $core_fieldset) {
    $core_fields = $coreForm->getFieldset($core_fieldset->name);
    foreach ($core_fields as $core_field) {
        if($child->props['field_type'] == $core_field->name){ 
            $field_value = !empty($core_fied_data->{$core_field->name})?$core_fied_data->{$core_field->name}:'';
            echo '<div class="'.$width.'">';
            if($label == "global"){
            echo '<label class="uk-form-label" for="form-stacked-text">'.str_replace("*","",strip_tags($core_field->label));
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
                $placeholder = 'Please enter your '.strtolower(trim(str_replace("&#160;*","",strip_tags(Text::_($core_field->label)))));
            }else if(!empty($placeholder)){
                $placeholder = $placeholder;
            }
            echo '<div class="uk-form-controls uk-inline uk-display-block">';
            if($child->props['invalid_field_icon_custom']){
                echo '<span class="uk-form-icon '.$child->props['invalid_field_color'].'" uk-icon="icon: '.$child->props['invalid_field_icon_custom'].'"></span>';
            }
            $approved_status = plgSystemUniversal_elements::checkApprovalField($core_field->name);
            $disabled = "";
            if(!empty($field_value) && !empty($child->props["field_approval_needed"]) && $approved_status == 0){
                $placeholder = "Waiting for admin approval";
                $disabled = "disabled";
            }
            if(empty($field_value) && !empty($child->props["field_approval_needed"])){
                echo '<input type="hidden" name="c_f['.$core_field->name.']" value="">';
                echo '<input class="uk-input" '.$required.' id="'.$core_field->name.'" type="'.strtolower($core_field->type).'" name="jform['.$core_field->name.']" placeholder="'.$placeholder.'" '.$disabled.'>';
            }else if(!empty($field_value) && !empty($child->props["field_approval_needed"])){
                echo '<input type="hidden" name="c_f['.$core_field->name.']" value="'.$field_value.'">';
                echo '<input class="uk-input" '.$required.' id="'.$core_field->name.'" type="'.strtolower($core_field->type).'" name="jform['.$core_field->name.']" placeholder="'.$placeholder.'" '.$disabled.'>';
            }else{
                echo '<input class="uk-input" '.$required.' id="'.$core_field->name.'" type="'.strtolower($core_field->type).'" name="jform['.$core_field->name.']" placeholder="'.$placeholder.'" value="'.$field_value.'" '.$disabled.'>';
            }
            echo '</div>';
            echo '</div>';
        }
    }
}
?>