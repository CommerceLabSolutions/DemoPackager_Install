<?php
use \Joomla\CMS\Factory;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Form\Form;

$extra =  FieldsHelper::getFields('com_users.user', Factory::getUser(), true);
$getUserFieldData = plgSystemUniversal_elements::getUserFieldData();
if(!empty($getUserFieldData->field_data)){
    $custom_fied_data  = !empty(json_decode($getUserFieldData->field_data)->com_fields)?json_decode($getUserFieldData->field_data)->com_fields:'';
}else{
    $custom_fied_data  = '';
}
$custom_field_image_data  = !empty($getUserFieldData->image_data)?json_decode($getUserFieldData->image_data):'';

foreach ($extra as $extra_fieldset) { 
    if($child->props['field_type'] == $extra_fieldset->name){
        $field_value = !empty($custom_fied_data->{$extra_fieldset->name})?$custom_fied_data->{$extra_fieldset->name}:'';
        $type = strtolower($extra_fieldset->type);
        echo '<div class="'.$width.'">';
        if($label == "global"){
        echo '<label class="uk-form-label" for="form-stacked-text">'.$extra_fieldset->label;
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
            $placeholder = 'Please enter your '.strtolower(trim(str_replace("&#160;*","",strip_tags(Text::_($extra_fieldset->label)))));
        }else if(!empty($placeholder)){
            $placeholder = $placeholder;
        }
        $approved_status = 0;
        $disabled = "";
        if(!empty($field_value) && !empty($child->props["field_approval_needed"]) && $approved_status == 1){
            $field_value = "Waiting for admin approval";
            $disabled = "disabled";
        }
        echo '<div class="uk-form-controls uk-inline uk-display-block">';
        if($type == "text" || $type == "textarea" || $type == "password" || $type == "email" || $type == "url" || $type == "tel"){
            $approved_status = plgSystemUniversal_elements::checkApprovalField($extra_fieldset->name);
            $disabled = "";
            if(!empty($field_value) && !empty($child->props["field_approval_needed"]) && $approved_status == 0){
                $placeholder = "Waiting for admin approval";
                $disabled = "disabled";
            }
            if($type == "textarea"){
                if(empty($field_value) && !empty($child->props["field_approval_needed"])){
                    echo '<input type="hidden" name="cust_f['.$extra_fieldset->name.']" value="">';
                    echo '<textarea class="uk-textarea" rows="5" placeholder="'.$placeholder.'" '.$required.' aria-label="Textarea" id="'.$extra_fieldset->name.'" name="jform[com_fields]['.$extra_fieldset->name.']" '.$disabled.'></textarea>';
                }else if(!empty($field_value) && !empty($child->props["field_approval_needed"])){
                    echo '<input type="hidden" name="cust_f['.$extra_fieldset->name.']" value="'.$field_value.'">';
                    echo '<textarea class="uk-textarea" rows="5" placeholder="'.$placeholder.'" '.$required.' aria-label="Textarea" id="'.$extra_fieldset->name.'" name="jform[com_fields]['.$extra_fieldset->name.']" '.$disabled.'></textarea>';
                }else{
                    echo '<textarea class="uk-textarea" rows="5" placeholder="'.$placeholder.'" '.$required.' aria-label="Textarea" id="'.$extra_fieldset->name.'" name="jform[com_fields]['.$extra_fieldset->name.']" '.$disabled.'>'.$field_value.'</textarea>';
                }
            }else{
                if($child->props['invalid_field_icon_custom']){
                    echo '<span class="uk-form-icon '.$child->props['invalid_field_color'].'" uk-icon="icon: '.$child->props['invalid_field_icon_custom'].'"></span>';
                }
                if(empty($field_value) && !empty($child->props["field_approval_needed"])){
                    echo '<input type="hidden" name="cust_f['.$extra_fieldset->name.']" value="">';
                    echo '<input class="uk-input" id="'.$extra_fieldset->name.'" '.$required.' type="'.$type.'" name="jform[com_fields]['.$extra_fieldset->name.']" placeholder="'.$placeholder.'" '.$disabled.'>'; 
                }else if(!empty($field_value) && !empty($child->props["field_approval_needed"])){
                    echo '<input type="hidden" name="cust_f['.$extra_fieldset->name.']" value="'.$field_value.'">';
                    echo '<input class="uk-input" id="'.$extra_fieldset->name.'" '.$required.' type="'.$type.'" name="jform[com_fields]['.$extra_fieldset->name.']" placeholder="'.$placeholder.'" '.$disabled.'>'; 
                }else{
                    echo '<input class="uk-input" id="'.$extra_fieldset->name.'" '.$required.' type="'.$type.'" name="jform[com_fields]['.$extra_fieldset->name.']" placeholder="'.$placeholder.'" value="'.$field_value.'" '.$disabled.'>'; 
                }
            }
        }else if($type == "radio"){
            $radios = $extra_fieldset->fieldparams->get('options');
            $i=1;
            foreach($radios as $radio){
            $check = '';
            if($i==1){
                $check = "checked";
            }
            echo '<label><input class="uk-radio" type="radio" name="jform[com_fields]['.$extra_fieldset->name.']" value="'.$radio->value.'" '.$check.'>&nbsp;'.$radio->name.'</label><br>';
            $i++;
            }
        }else if($type == "checkboxes"){
            $checkboxs = $extra_fieldset->fieldparams->get('options');
            foreach($checkboxs as $checkbox){
            echo '<label><input class="uk-checkbox" type="checkbox" '.$required.' name="jform[com_fields]['.$extra_fieldset->name.'][]" value="'.$checkbox->value.'">&nbsp;'.$checkbox->name.'</label><br>';
            }
        }else if($type == "list"){
            $lists = $extra_fieldset->fieldparams->get('options');                    
            echo '<select class="uk-select" name="jform[com_fields]['.$extra_fieldset->name.']" id="form-horizontal-select" '.$required.'>';
            echo '<option value="">Select '.$extra_fieldset->label.'</option>';
                foreach($lists as $list){
                    if($list->value==$field_value){
                        $selected = "selected";
                    }else{
                        $selected = "";
                    }
                echo '<option value="'.$list->value.'"" '.$selected.'>'.$list->name.'</option>';
                }
            echo '</select>';                    
        }else if($type == "media"){
            $image = !empty($custom_field_image_data->{$extra_fieldset->name})?$custom_field_image_data->{$extra_fieldset->name}:'';
            echo '<div class="js-upload uk-placeholder uk-text-center">
                <span uk-icon="icon: cloud-upload"></span>
                <span class="uk-text-middle">Attach binaries by dropping them here or</span>
                <div uk-form-custom>
                    <input type="file" name="'.$extra_fieldset->name.'" '.$required.'>
                    <span class="uk-link">selecting one</span>';
                echo '</div>';
                if($image){
                    echo '<span class="uk-display-block uk-link" uk-toggle="target: #'.$extra_fieldset->name.'" uk-icon="icon: image; ratio: 4" uk-tooltip="Click to view uploaded image"></span>';
                    echo '<div id="'.$extra_fieldset->name.'" uk-modal>
                        <div class="uk-modal-dialog uk-width-large uk-modal-body">
                            <button class="uk-modal-close-default uk-link" type="button" uk-close></button>
                            <img src="'.JURI::base().$image.'" class="uk-width-expand"/>
                        </div>
                    </div>';
                }  
                
            echo '</div>';
        }
        echo '</div>';
        echo '</div>';
    }
}
?>