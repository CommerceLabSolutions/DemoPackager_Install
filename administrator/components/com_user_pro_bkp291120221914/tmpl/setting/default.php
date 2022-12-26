<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_User_pro
 * @author      <>
 * @copyright  
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

$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')
    ->useScript('form.validate');
HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.multiselect');
jimport('joomla.html.editor');
$editor = JEditor::getInstance(Factory::getConfig()->get('editor'));
?>
<div class="main-card">
        <?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => 'general_setting', 'recall' => true, 'breakpoint' => 768]); ?>
        
        <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'general_setting', Text::_('COM_USER_PRO_USER_GENERAL_SETTING')); ?>
            <fieldset class="options-form">
                <legend><?php echo Text::_('COM_USER_PRO_USER_GENERAL_SETTING'); ?></legend>
                <div class="form-grid">
                    <div class="control-group">
                        <div class="control-label">
                            <label id="jform_address-lbl" for="jform_address" class="required">Name
                            </label>
                        </div>
                        <div class="controls">                        
                            <input type="text" name="address" id="jform_address" value="" class="form-control">
                        </div>
                    </div>
                </div>
            </fieldset>
        <?php echo HTMLHelper::_('uitab.endTab'); ?>
        <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'custom_fields', Text::_('COM_USER_PRO_USER_EMAILS')); ?>
            <fieldset class="options-form">
                <legend><?php echo Text::_('COM_USER_PRO_USER_EMAILS'); ?></legend>
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
                                                    <?php echo Text::_('COM_USER_PRO_USERS_EMAIL_TITLE'); ?>
                                                </th>
                                                <th scope="col" class="w-12 d-none d-md-table-cell">
                                                    <?php echo Text::_('COM_USER_PRO_USERS_EMAIL_SUBJECT'); ?>
                                                </th>
                                                <th scope="col" class="w-12 d-none d-md-table-cell">
                                                    <?php echo Text::_('COM_USER_PRO_USERS_EMAIL_ACTION'); ?>
                                                </th>                                                             
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($this->items as $i => $item) : ?>
                                                <tr class="row<?php echo $i % 2; ?>">
                                                    <td width="50%" class="break-word d-none d-md-table-cell">
                                                        <?php echo $this->escape($item->title); ?>
                                                    </td>
                                                    <td width="50%" class="break-word d-none d-md-table-cell">
                                                        <?php echo $this->escape($item->subject); ?>
                                                    </td>
                                                    <td width="50%" class="break-word d-none d-md-table-cell">
                                                        <a href="index.php?option=com_user_pro&view=setting&layout=edit&id=<?php echo $item->id;?>">Edit</a>
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
                <div class="form-grid">
                   <?php
                    // echo '<div class="row">';
                    //     echo '<div class="col-lg-6">';

                    //         echo '<div class="control-group d-block">';
                    //             echo '<div class="control-label">';
                    //                 echo '<label id="jform_name-lbl" for="jform_name" class="required">';
                    //                 echo Text::_("COM_USER_PRO_USER_TITLE");
                    //                 echo '</label>';
                    //             echo '</div>';
                    //             echo '<div class="controls">';
                    //                 echo '<input type="text" name="title" id="title" class="form-control" value="'.$this->template_content->title.'" />';
                    //             echo '</div>';
                    //         echo '</div>';

                    //         echo '<div class="control-group d-block">';
                    //             echo '<div class="control-label">';
                    //                 echo '<label id="subject" for="subject" class="required">';
                    //                 echo Text::_("COM_USER_PRO_USER_SUBJECT");
                    //                 echo '</label>';
                    //             echo '</div>';
                    //             echo '<div class="controls">';
                    //                 echo '<input type="text" name="subject" id="subject" class="form-control" value="'. $this->template_content->subject.'" />';
                    //             echo '</div>';
                    //         echo '</div>';

                    //         echo '<div class="control-group d-block">';
                    //             echo '<div class="control-label">';
                    //                 echo '<label id="jform_name-lbl" for="jform_name" class="required">';
                    //                 echo Text::_("COM_USER_PRO_USER_MESSAGE");
                    //                 echo '</label>';
                    //             echo '</div>';
                    //             echo '<div class="controls d-block">';
                    //                 echo $editor->display('mailbody', $this->template_content->message, '100%', '200', '100', '200', array('article', 'image', 'pagebreak', 'readmore'));;
                    //             echo '</div>';
                    //         echo '</div>';

                    //         echo '<div class="control-group d-block">';
                    //             echo '<div class="control-label">';
                    //                 echo '<button class="btn btn-success" type="button">'.Text::_('COM_USER_PRO_USER_SEND').'</button>';
                    //             echo '</div>';
                    //             echo '<div class="controls">';
                    //             echo '</div>';
                    //         echo '</div>';
                    //     echo '</div>';
                    //     echo '<div class="col-lg-6">';
                    //         echo '<div class="control-group d-block">';
                    //             echo '<div class="control-label">';
                    //                 echo '<label id="jform_name-lbl" for="jform_name" class="required">';
                    //                 echo Text::_("COM_USER_PRO_USER_SHORT_CODE");
                    //                 echo '</label>';
                    //             echo '</div>';
                    //             echo '<div class="controls d-block">';
                    //                 echo '<ul>';
                    //                     echo '<li>Name {{name}}</li>';
                    //                     echo '<li>Username {{username}}</li>';
                    //                     echo '<li>Email {{email}}</li>';
                    //                     echo '<li>Old Entry {{old_entry}}</li>';
                    //                     echo '<li>New Entry {{new_entry}}</li>';
                    //                     echo '<li>Link {{account_link}}</li>';
                    //                 echo '</ul>';
                    //             echo '</div>';
                    //         echo '</div>';
                    //     echo '</div>';
                    // echo '</div>';
                    ?>
                </div>
            </fieldset>

        <?php echo HTMLHelper::_('uitab.endTab'); ?>
        <?php echo HTMLHelper::_('uitab.endTabSet'); ?>
</div>