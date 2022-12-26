<?php
use \Joomla\CMS\Factory;

$user = Factory::getUser();
if($user->id){
// Element
$el = $this->el('div', [
    'class' => [
        'universal-element'
    ]
]);
$lang = Factory::getLanguage();
$lang->load('plg_user_profile', JPATH_ADMINISTRATOR);
$lang->load('com_users', JPATH_SITE);
?>
<?= $el($props, $attrs) ?>
<h1><?php echo $props["user_form_title"]?$props["user_form_title"]:"Profile";?></h1>
<div id="profile-edit-view">
<form method="post" class="uk-form-stacked" id="universal_elements" name="universal_elements" enctype="multipart/form-data" uk-grid>
<input type="hidden" name="jform[id]" value="<?php echo $user->id;?>">
<input type="hidden" name="jform[email1]" value="<?php echo $user->email;?>">
<input type="hidden" name="jform[password1]" value="">
<?php ?>
<?php if($props['user_edit_button_vertical_alignment'] == "uk-vertical-align-top" && empty($props['inline_editing_approval'])){?>  
<p class="uk-margin uk-clearfix uk-width-1-1@s">        
    <button id="universal_elements_edit_button" class="uk-button <?php echo $props['user_edit_button_type'].' '.$props['user_edit_button_size']. ' '.$props['user_edit_button_horizontal_placement'];?>"><?php echo $props["user_edit_button_text"]?$props["user_edit_button_text"]:'Edit';?></button>
</p>
<?php }   
if($props['user_submit_button_vertical_alignment'] == "uk-vertical-align-top" && !empty($props['inline_editing_approval'])){?>  
<p class="uk-margin uk-clearfix uk-width-1-1@s">
    <button class="uk-button <?php echo $props['user_submit_button_type'].' '.$props['user_submit_button_size']. ' '.$props['user_submit_button_horizontal_placement'];?>" type="submit" id="universal_elements_submit_button"><?php echo $props["user_submit_button_text"]?$props["user_submit_button_text"]:'Save';?></button>
</p>
<?php } ?>
<?php 
if($children){
    $yoo_array = array();
    foreach ($children as $child){ 
        $yoo_array[] = $child->props;

        //Field required setting
        $required = $child->props['field_required']?"required":'';

        //Field label setting
        if($child->props['field_label_show_use_global']){
           $label = "global"; 
        }else{
            if($child->props['field_label'] && $child->props['field_label_show']){
               $label = $child->props['field_label'];  
            }else{
               $label = "";
            }
        }

        //Field placeholder setting
        if($child->props['field_placeholder_show_use_global']){
           $placeholder = "global"; 
        }else{
            $placeholder = $child->props['field_placeholder'];  
        }
        
        // Field width setting
        if($child->props['field_width_use_global']){
            $width = 'uk-width-1-1@s';
        }else{
            if($child->props['field_width'] == "custom"){
                $width = $child->props['field_width_custom'];
            }else{
                $width = $child->props['field_width'];
            }
        }
        ?>
        <!-- Core field generator template start -->
        <?= $this->render("{$__dir}/template-core", compact('props','child','required','label','placeholder','width')) ?>
        <!-- Core field generator template end -->

        <!-- Profile field generator template start -->
        <?= $this->render("{$__dir}/template-profile", compact('props','child','required','label','placeholder','width')) ?>
        <!-- Profile field generator template end -->

        <!-- Custom field generator template start -->
        <?= $this->render("{$__dir}/template-custom", compact('props','child','required','label','placeholder','width')) ?>
        <!-- Custom field generator template end -->
        
<?php 
    }
}

if($props['user_edit_button_vertical_alignment'] == "uk-vertical-align-bottom" && empty($props['inline_editing_approval'])){?>  
    <p class="uk-margin uk-clearfix uk-width-1-1@s" id="universal_elements_edit_button_section">        
        <button class="uk-button <?php echo $props['user_edit_button_type'].' '.$props['user_edit_button_size']. ' '.$props['user_edit_button_horizontal_placement'];?>" type="submit" id="universal_elements_edit_button"><?php echo $props["user_edit_button_text"]?$props["user_edit_button_text"]:'Edit';?></button>
    </p>
<?php } ?>
<?php if($props['user_submit_button_vertical_alignment'] == "uk-vertical-align-bottom" && !empty($props['inline_editing_approval'])){?>  
<p class="uk-margin uk-clearfix uk-width-1-1@s" id="universal_elements_submit_button_section">
    <button class="uk-button <?php echo $props['user_submit_button_type'].' '.$props['user_submit_button_size']. ' '.$props['user_submit_button_horizontal_placement'];?>" type="submit" id="universal_elements_submit_button"><?php echo $props["user_submit_button_text"]?$props["user_submit_button_text"]:'Save';?></button>    
</p>
<?php } ?>
<input type="hidden" name="yoo_theme" value='<?php echo base64_encode(json_encode($yoo_array));?>'>
</form> 
</div>
<?= $el->end() ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(document).ready(function(){
    var inline_editing = '<?php echo $props['inline_editing_approval']?>';
    if(inline_editing == false){
        jQuery("#universal_elements :input").prop("disabled", true);
        jQuery("#universal_elements_submit_button_section").hide();
        jQuery("#universal_elements_edit_button_section").show();
        jQuery("#universal_elements_edit_button").prop("disabled", false);

        jQuery("#universal_elements_edit_button").click(function(e){
            e.preventDefault();
            jQuery("#universal_elements :input").prop("disabled", false);
            jQuery("#universal_elements_submit_button_section").show();
            jQuery("#universal_elements_edit_button_section").hide();
        });
    }

    jQuery("#universal_elements").on('submit', function(e){
        e.preventDefault(); 
        var formData = new FormData(this); 
        var valid = $('#universal_elements')[0].checkValidity();
        if(valid){
            jQuery.ajax({
                type:'POST',
                url:"index.php?option=com_ajax&group=system&plugin=Universal_elements&format=raw",
                data: formData,
                contentType: false,
                cache: false,
                processData:false,
                success:function(response){
                    if(response == "true"){
                        alert("Data updated successfully");
                        if(inline_editing == false){
                            jQuery("#universal_elements :input").prop("disabled", true);
                            jQuery("#universal_elements_submit_button_section").hide();
                            jQuery("#universal_elements_edit_button_section").show();
                            jQuery("#universal_elements_edit_button").prop("disabled", false);
                        }
                    }
                }
            });
        }
    });
});
</script>
<?php } ?>