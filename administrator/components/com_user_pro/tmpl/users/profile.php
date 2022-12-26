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
$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
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
        <?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => 'user_activity_log', 'recall' => true, 'breakpoint' => 768]); ?>
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
                                        <tfoot>
                                        <tr>
                                            <td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
                                                <?php echo $this->pagination->getListFooter(); ?>
                                            </td>
                                        </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php 
                                            if(!empty($this->items)){
                                            foreach ($this->items as $i => $item) : ?>
                                                <tr class="row<?php echo $i % 2; ?>">
                                                    <td width="20%" class="break-word d-none d-md-table-cell">
                                                        <?php echo $this->escape($item->name); ?>
                                                    </td>
                                                    <td width="60%" class="break-word d-none d-md-table-cell">
                                                        <?php echo str_replace("{{name}}",$item->name,$item->log_message); ?>
                                                    </td>
                                                    <td width="20%" class="break-word d-none d-md-table-cell">
                                                        <?php echo $this->escape($item->created_time); ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; }else{ ?>
                                            <tr>
                                                <td colspan="3">User has no activity</td>
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