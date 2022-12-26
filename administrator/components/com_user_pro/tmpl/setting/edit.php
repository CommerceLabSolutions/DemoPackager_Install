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
    <fieldset class="options-form">
    <div class="form-grid">
        <form action="<?php echo Route::_('index.php?option=com_user_pro&controller=setting&task=saveEmailContent'); ?>" method="post"
      name="adminForm" id="adminForm">
      <input type="hidden" name="id" value="<?php echo $this->template_content->id;?>">
       <?php
        echo '<div class="row">';
            echo '<div class="col-lg-6">';

                echo '<div class="control-group d-block">';
                    echo '<div class="control-label">';
                        echo '<label id="jform_name-lbl" for="jform_name" class="required">';
                        echo Text::_("COM_USER_PRO_USER_TITLE");
                        echo '</label>';
                    echo '</div>';
                    echo '<div class="controls">';
                        echo '<input type="text" name="title" id="title" class="form-control" value="'.$this->template_content->title.'" />';
                    echo '</div>';
                echo '</div>';

                echo '<div class="control-group d-block">';
                    echo '<div class="control-label">';
                        echo '<label id="subject" for="subject" class="required">';
                        echo Text::_("COM_USER_PRO_USER_SUBJECT");
                        echo '</label>';
                    echo '</div>';
                    echo '<div class="controls">';
                        echo '<input type="text" name="subject" id="subject" class="form-control" value="'. $this->template_content->subject.'" />';
                    echo '</div>';
                echo '</div>';

                echo '<div class="control-group d-block">';
                    echo '<div class="control-label">';
                        echo '<label id="jform_name-lbl" for="jform_name" class="required">';
                        echo Text::_("COM_USER_PRO_USER_MESSAGE");
                        echo '</label>';
                    echo '</div>';
                    echo '<div class="controls d-block">';
                        echo $editor->display('message', $this->template_content->message, '100%', '200', '100', '200', array('article', 'image', 'pagebreak', 'readmore'));;
                    echo '</div>';
                echo '</div>';

                echo '<div class="control-group d-block">';
                    echo '<div class="control-label">';
                        echo '<button class="btn btn-success" type="submit">'.Text::_('COM_USER_PRO_USER_UPDATE').'</button>';
                    echo '</div>';
                    echo '<div class="controls">';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
            echo '<div class="col-lg-6">';
                echo '<div class="control-group d-block">';
                    echo '<div class="control-label">';
                        echo '<label id="jform_name-lbl" for="jform_name" class="required">';
                        echo Text::_("COM_USER_PRO_USER_SHORT_CODE");
                        echo '</label>';
                    echo '</div>';
                    echo '<div class="controls d-block">';
                        echo '<ul>';
                            echo '<li>Name {{name}}</li>';
                            echo '<li>Username {{username}}</li>';
                            echo '<li>Email {{email}}</li>';
                            echo '<li>Old Entry {{old_entry}}</li>';
                            echo '<li>New Entry {{new_entry}}</li>';
                            echo '<li>Link {{account_link}}</li>';
                        echo '</ul>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
        ?>
    </form>
    </div>
    </fieldset>
</div>