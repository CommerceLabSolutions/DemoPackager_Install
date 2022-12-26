<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_User_pro
 * @author      <>
 * @copyright  
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Userpro\Component\User_pro\Administrator\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Utilities\ArrayHelper;
use Joomla\CMS\User\UserHelper;
use Joomla\CMS\Form\Form;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;
/**
 * Fieldapprovals list controller class.
 *
 * @since  1.0.0
 */
class FieldapprovalsController extends AdminController
{
	/**
	 * Method to clone existing Fieldapprovals
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 */
	public function duplicate()
	{
		// Check for request forgeries
		$this->checkToken();

		// Get id(s)
		$pks = $this->input->post->get('cid', array(), 'array');

		try
		{
			if (empty($pks))
			{
				throw new \Exception(Text::_('COM_USER_PRO_NO_ELEMENT_SELECTED'));
			}

			ArrayHelper::toInteger($pks);
			$model = $this->getModel();
			$model->duplicate($pks);
			$this->setMessage(Text::_('COM_USER_PRO_ITEMS_SUCCESS_DUPLICATED'));
		}
		catch (\Exception $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'warning');
		}

		$this->setRedirect('index.php?option=com_user_pro&view=fieldapprovals');
	}

	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    Optional. Model name
	 * @param   string  $prefix  Optional. Class prefix
	 * @param   array   $config  Optional. Configuration array for model
	 *
	 * @return  object	The Model
	 *
	 * @since   1.0.0
	 */
	public function getModel($name = 'Fieldapproval', $prefix = 'Administrator', $config = array())
	{
		return parent::getModel($name, $prefix, array('ignore_request' => true));
	}

	

	/**
	 * Method to save the submitted ordering values for records via AJAX.
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 *
	 * @throws  Exception
	 */
	public function saveOrderAjax()
	{
		// Get the input
		$pks   = $this->input->post->get('cid', array(), 'array');
		$order = $this->input->post->get('order', array(), 'array');

		// Sanitize the input
		ArrayHelper::toInteger($pks);
		ArrayHelper::toInteger($order);

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->saveorder($pks, $order);

		if ($return)
		{
			echo "1";
		}

		// Close the application
		Factory::getApplication()->close();
	}

	public function changeStatus(){
		$input = Factory::getApplication()->input;
		$status_type = $input->get('status_type');
		$id = $input->get('id');
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$fields = array(
		    $db->quoteName('status') . ' = ' . $db->quote($status_type)
		);
		$conditions = array(
		    $db->quoteName('id') . ' = ' . $db->quote($id)
		);
		$query->update($db->quoteName('#__user_pro_field_approval'))->set($fields)->where($conditions);
		$db->setQuery($query);
		if($db->execute()){
			$approval_data = $this->getApprovalDataById($id);
			if($status_type == 1){
			    $user = Factory::getUser($approval_data->user_id);
			    $formdata = array();
			    $formdata["id"] = $approval_data->user_id;
			    $formdata["email1"] = $user->email;
			    $formdata["password1"] = "";
			    if($approval_data->field_type == "core_field"){
			        $formdata[$approval_data->field_name] = $approval_data->field_value;
			    }else if($approval_data->field_type == "profile_field"){
			        $formdata["profile"][$approval_data->field_name] = $approval_data->field_value;
			    }else if($approval_data->field_type == "custom_field"){
			        $formdata["com_fields"][$approval_data->field_name] = $approval_data->field_value;
			    }
		    	$content = Factory::getApplication()->bootComponent('com_users')->getMVCFactory();
	            $model = $content->createModel('Profile', 'Site',['ignore_request' => true]);
	            $result = $model->save($formdata);
				$mail =  $this->getEmailTemplate('approved_email');
				//$this->SendCustomEmail($approval_data,$mail->subject,$mail->message);
			}else{
				$mail =  $this->getEmailTemplate('denied_email');
				//$this->SendCustomEmail($approval_data,$mail->subject,$mail->message);
			}
		}
	}

	public function SendCustomEmail($approval_data,$subject,$message){
		$config = Factory::getConfig();
		$mail_from = $config->get("mailfrom");
		$sitename = $config->get("sitename");
		$user = Factory::getUser($approval_data->user_id);
		$final_array_html = !empty($approval_data)?$this->getStatusFieldHtml($approval_data):array();
		$name = $user->name;
		$mail_to = $user->email;
		$from = array($mail_from, $sitename);
		$patterns = array(
			'{{name}}',
			'{{username}}',
			'{{email}}',
			'{{old_entry}}',
			'{{new_entry}}',
			'{{account_link}}',
			'{{sitename}}' 
		);
		$replacements = array($name,'','','',$final_array_html,'','');
		$body = str_replace($patterns, $replacements, $message);
		# Invoke Mail Class
		$mailer = Factory::getMailer();
		# Set sender array so that my name will show up neatly in your inbox
		$mailer->setSender($from);
		# Add a recipient -- this can be a single address (string) or an array of addresses
		$mailer->addRecipient($mail_to);
		$mailer->setSubject($subject);
		$mailer->setBody($body);
		# If you would like to send as HTML, include this line; otherwise, leave it out
		$mailer->isHTML();
		# Send once you have set all of your options
		$mailer->send();
	}

	public function getEmailTemplate($mail_type){
		$db = Factory::getDbo();
		// Create a new query object.
		$query = $db->getQuery(true);
		// Select all records from the user profile table where key begins with "custom.".
		// Order it by the ordering field.
		$query->select('*');
		$query->from($db->quoteName('#__user_pro_email_setting'));
		$query->where($db->quoteName('mail_type') . ' = ' . $db->quote($mail_type));
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		return $results = $db->loadObject();
	}

	public function getApprovalDataById($id){
		$db = Factory::getDbo();
		// Create a new query object.
		$query = $db->getQuery(true);
		// Select all records from the user profile table where key begins with "custom.".
		// Order it by the ordering field.
		$query->select('*');
		$query->from($db->quoteName('#__user_pro_field_approval'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($id));
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		return $results = $db->loadObject();
	}

	public function openModal(){
		$lang = Factory::getLanguage();
		$lang->load('plg_user_profile', JPATH_ADMINISTRATOR);
		$lang->load('com_users', JPATH_SITE);
		$input = Factory::getApplication()->input;
		$id = $input->get('id');
		$c_fields = $this->getApprovalDataById($id);
		$user = Factory::getUser($c_fields->user_id);
		$userProfile = UserHelper::getProfile($c_fields->user_id);
		$profile_data = $userProfile->profile;
		echo '<div class="col-lg-6">';
            echo '<div class="innerview">';
            echo '<h3>Old Changes</h3>';
            $coreForm =  Form::getInstance("com_users.user", JPATH_ROOT.'/components/com_users/forms/registration.xml', array());
            if(!empty($c_fields)){
                foreach ($coreForm->getFieldsets() as $core_fieldset) {
                    $core_fields = $coreForm->getFieldset($core_fieldset->name);
                    foreach ($core_fields as $core_field) {
                        if($core_field->name == $c_fields->field_name){
                            echo '<div class="control-group d-block">';
                                echo '<div class="control-label">';
                                    echo '<label id="jform_name-lbl" for="jform_name" class="required">';
                                    echo str_replace("*","",strip_tags($core_field->label));
                                    echo '</label>';
                                echo '</div>';
                                echo '<div class="controls">';
                                    echo '<input type="text" name="'.$c_fields->field_name.'" id="'.$c_fields->field_name.'" class="form-control" value="'.$user->{$c_fields->field_name}.'" disabled/>';
                                echo '</div>';
                            echo '</div>';
                        }
                    }
                }
            }
            $profileForm =  Form::getInstance("user.profile", JPATH_ROOT.'/plugins/user/profile/forms/profile.xml', array());
            if(!empty($c_fields)){
                foreach ($profileForm->getFieldsets() as $profile_fieldset) {
                    $profile_fields = $profileForm->getFieldset($profile_fieldset->name);
                    foreach ($profile_fields as $profile_field) {
                    		$field_name = str_replace("]","",str_replace("profile[", "", $profile_field->name));
                            if($field_name == $c_fields->field_name){
                                echo '<div class="control-group d-block">';
                                    echo '<div class="control-label">';
                                        echo '<label id="jform_name-lbl" for="jform_name" class="required">';
                                        echo str_replace("*","",strip_tags($profile_field->label));
                                        echo '</label>';
                                    echo '</div>';
                                    echo '<div class="controls">';
                                    if(strtolower($profile_field->type) == "text"){
                                        echo '<input type="text" name="'.$c_fields->field_name.'" id="'.$c_fields->field_name.'" class="form-control" value="'.$profile_data[$c_fields->field_name].'" disabled/>';
                                    }else{
                                        echo '<textarea class="form-control" rows="5" aria-label="Textarea" id="'.$c_fields->field_name.'" name="'.$c_fields->field_name.'" disabled>'.$profile_data[$c_fields->field_name].'</textarea>';
                                    }
                                    echo '</div>';
                                echo '</div>';
                           }
                    }
                }
            }
            $extra =  FieldsHelper::getFields('com_users.user', Factory::getUser(), true);
            if(!empty($c_fields)){
                foreach ($extra as $extra_fieldset) { 
                        $type = strtolower($extra_fieldset->type);
                        if($extra_fieldset->name == $c_fields->field_name){
                            $customField = $this->getCustomFieldData($c_fields->user_id,$c_fields->field_name);
                            echo '<div class="control-group d-block">';
                                echo '<div class="control-label">';
                                    echo '<label id="jform_name-lbl" for="jform_name" class="required">';
                                    echo $extra_fieldset->label;
                                    echo '</label>';
                                echo '</div>';
                                echo '<div class="controls">';
                                if($type == "text" || $type == "textarea" || $type == "password" || $type == "email" || $type == "url" || $type == "tel"){
                                    if($type == "textarea"){
                                        echo '<textarea class="form-control" rows="5" aria-label="Textarea" id="'.$extra_fieldset->field_name.'" name="'.$extra_fieldset->field_name.'" disabled>'.$customField->value.'</textarea>';
                                    }else{
                                        echo '<input type="'.$type.'" name="'.$extra_fieldset->name.'" id="'.$extra_fieldset->name.'" class="form-control" value="'.$customField->value.'" disabled/>';
                                    }
                                }else if($type == "radio"){
                                    echo '<fieldset id="jform_com_fields_'.$extra_fieldset->name.'">';
                                    echo '<legend class="visually-hidden">'.$extra_fieldset->name.'</legend>';
                                    echo '<div class="btn-group radio">';
                                    $radios = $extra_fieldset->fieldparams->get('options');
                                    $i = 0;
                                    foreach($radios as $radio){
                                        $checked = $customField->value == $radio->value?"checked":'';
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
                                            $checked = $c_fields->field_value[$i] == $checkbox->value?"checked":'';
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
                                            if($list->value==$customField->value){
                                                $selected = "selected";
                                            }else{
                                                $selected = "";
                                            }
                                            echo '<option value="'.$list->value.'" '.$selected.'>'.$list->name.'</option>';
                                        }
                                    echo '</select>';
                                }else if($type == "media"){
                                    echo '<div id="divImageMediaPreview">';
                                    if($customField->value){
                                    echo '<img src="'.JURI::root().$customField->value.'">';
                                    }
                                    echo '</div>';
                                }
                                echo '</div>';
                            echo '</div>';
                        }
                    
                }
            }
        echo '</div>';
        echo '</div>';
        echo '<div class="col-lg-6">';
            echo '<div class="innerview border-0">';
            echo '<h3>New Changes</h3>';
            $coreForm =  Form::getInstance("com_users.user", JPATH_ROOT.'/components/com_users/forms/registration.xml', array());
            if(!empty($c_fields)){
                foreach ($coreForm->getFieldsets() as $core_fieldset) {
                    $core_fields = $coreForm->getFieldset($core_fieldset->name);
                    foreach ($core_fields as $core_field) {
                        if($core_field->name == $c_fields->field_name){
                            echo '<div class="control-group d-block">';
                                echo '<div class="control-label">';
                                    echo '<label id="jform_name-lbl" for="jform_name" class="required">';
                                    echo str_replace("*","",strip_tags($core_field->label));
                                    echo '</label>';
                                echo '</div>';
                                echo '<div class="controls">';
                                    echo '<input type="text" name="'.$c_fields->field_name.'" id="'.$c_fields->field_name.'" class="form-control" value="'.$c_fields->field_value.'" disabled/>';
                                echo '</div>';
                            echo '</div>';
                        }
                    }
                }
            }
            $profileForm =  Form::getInstance("user.profile", JPATH_ROOT.'/plugins/user/profile/forms/profile.xml', array());
            if(!empty($c_fields)){
                foreach ($profileForm->getFieldsets() as $profile_fieldset) {
                    $profile_fields = $profileForm->getFieldset($profile_fieldset->name);
                    foreach ($profile_fields as $profile_field) {
                    		$field_name = str_replace("]","",str_replace("profile[", "", $profile_field->name));
                            if($field_name == $c_fields->field_name){
                                echo '<div class="control-group d-block">';
                                    echo '<div class="control-label">';
                                        echo '<label id="jform_name-lbl" for="jform_name" class="required">';
                                        echo str_replace("*","",strip_tags($profile_field->label));
                                        echo '</label>';
                                    echo '</div>';
                                    echo '<div class="controls">';
                                    if(strtolower($profile_field->type) == "text"){
                                        echo '<input type="text" name="'.$c_fields->field_name.'" id="'.$c_fields->field_name.'" class="form-control" value="'.$c_fields->field_value.'" disabled/>';
                                    }else{
                                        echo '<textarea class="form-control" rows="5" aria-label="Textarea" id="'.$c_fields->field_name.'" name="'.$c_fields->field_name.'" disabled>'.$c_fields->field_value.'</textarea>';
                                    }
                                    echo '</div>';
                                echo '</div>';
                           }
                    }
                }
            }
            $extra =  FieldsHelper::getFields('com_users.user', Factory::getUser(), true);
            if(!empty($c_fields)){
                foreach ($extra as $extra_fieldset) { 
                        $type = strtolower($extra_fieldset->type);
                        if($extra_fieldset->name == $c_fields->field_name){
                            echo '<div class="control-group d-block">';
                                echo '<div class="control-label">';
                                    echo '<label id="jform_name-lbl" for="jform_name" class="required">';
                                    echo $extra_fieldset->label;
                                    echo '</label>';
                                echo '</div>';
                                echo '<div class="controls">';
                                if($type == "text" || $type == "textarea" || $type == "password" || $type == "email" || $type == "url" || $type == "tel"){
                                    if($type == "textarea"){
                                        echo '<textarea class="form-control" rows="5" aria-label="Textarea" id="'.$extra_fieldset->field_name.'" name="'.$extra_fieldset->field_name.'" disabled>'.$c_fields->field_value.'</textarea>';
                                    }else{
                                        echo '<input type="'.$type.'" name="'.$extra_fieldset->name.'" id="'.$extra_fieldset->name.'" class="form-control" value="'.$c_fields->field_value.'" disabled/>';
                                    }
                                }else if($type == "radio"){
                                    echo '<fieldset id="jform_com_fields_'.$extra_fieldset->name.'">';
                                    echo '<legend class="visually-hidden">'.$extra_fieldset->name.'</legend>';
                                    echo '<div class="btn-group radio">';
                                    $radios = $extra_fieldset->fieldparams->get('options');
                                    $i = 0;
                                    foreach($radios as $radio){
                                        $checked = $c_fields->field_value == $radio->value?"checked":'';
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
                                            $checked = $c_fields->field_value[$i] == $checkbox->value?"checked":'';
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
                                            if($list->value==$c_fields->field_value){
                                                $selected = "selected";
                                            }else{
                                                $selected = "";
                                            }
                                            echo '<option value="'.$list->value.'" '.$selected.'>'.$list->name.'</option>';
                                        }
                                    echo '</select>';
                                }else if($type == "media"){
                                    echo '<div id="divImageMediaPreview">';
                                    if($c_fields->field_value){
                                    echo '<img src="'.JURI::root().$c_fields->field_value.'">';
                                    }
                                    echo '</div>';
                                }
                                echo '</div>';
                            echo '</div>';
                        }
                    
                }
            }
        echo '</div>';
        echo '</div>';
	}

	public function getStatusFieldHtml($approval_data){
		$field_html = '<ul>';
		$field_html .= '<li>'.$approval_data->field_name.' : '.$approval_data->field_value.'</li>';
		$field_html .= '</ul>';
		return $field_html;
	}
	
	public function getCustomFieldData($user_id, $field_name){
	    $db = Factory::getDbo();
		// Create a new query object.
		$query = $db->getQuery(true);
		// Select all records from the user profile table where key begins with "custom.".
		// Order it by the ordering field.
		$query->select('*');
		$query->from($db->quoteName('#__fields', 'f'));
		$query->join('LEFT', $db->quoteName('#__fields_values', 'fv') . ' ON ' . $db->quoteName('f.id') . ' = ' . $db->quoteName('fv.field_id'));
		$query->where($db->quoteName('fv.item_id') . ' = ' . $db->quote($user_id));
		$query->where($db->quoteName('f.name') . ' = ' . $db->quote($field_name));
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		return $results = $db->loadObject();
	}
}
