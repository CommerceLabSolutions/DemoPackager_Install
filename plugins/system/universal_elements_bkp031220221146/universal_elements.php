<?php
/**
 * @package   Universal Elements 
 * @author    Cloud Chief - CommerceLab.solutions
 * @copyright Copyright (C) 2022 CommerceLab  - CommerceLab.solutions
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Input\Input;
use YOOtheme\Application;
use YOOtheme\Path;

class plgSystemUniversal_elements extends CMSPlugin
{

	public function onAfterInitialise()
	{
		if (class_exists(Application::class, false))
		{

			$app = Application::getInstance();

			$root    = __DIR__;
			$rootUrl = Uri::root(true);

			$themeroot = Path::get('~theme');
			$loader    = require "{$themeroot}/vendor/autoload.php";
			$loader->setPsr4("YpsApp_userform\\", __DIR__ . "/modules/userform");

			// set alias
			Path::setAlias('~universal_elements', $root);
			Path::setAlias('~universal_elements:rel', $rootUrl . '/plugins/system/universal_elements');

			// bootstrap modules
			$app->load('~universal_elements/modules/userform/bootstrap.php');

		}

	}

	public function onAjaxUniversal_elements(){
		$user = Factory::getUser();
		$input = Factory::getApplication()->input;
		$approval_core_fields = $input->get('c_f', '', 'array');
		$approval_profile_fields = $input->get('profile_f', '', 'array');
		$approval_custom_fields = $input->get('cust_f', '', 'array');
		$formdata = $input->get('jform', '', 'array');
		if(!empty($approval_core_fields) || !empty($approval_profile_fields) || !empty($approval_custom_fields)){
		    $newformdata = $this->checkApprovalDataWithFormData($formdata, $approval_core_fields, $approval_profile_fields, $approval_custom_fields);
		}
		if(!empty($approval_core_fields)){
			$core_field_array = $this->CheckInsertApprovalFields($approval_core_fields,$formdata,"core_field")?$this->CheckInsertApprovalFields($approval_core_fields,$formdata,"core_field"):array();
		}else{
		    $core_field_array = array();
		}
		if(!empty($approval_profile_fields)){
			$profile_field_array = $this->CheckInsertApprovalFields($approval_profile_fields,$formdata,"profile_field")?$this->CheckInsertApprovalFields($approval_profile_fields,$formdata,"profile_field"):array();
		}else{
		    $profile_field_array = array();
		}
		if(!empty($approval_custom_fields)){
			$custom_field_array = $this->CheckInsertApprovalFields($approval_custom_fields,$formdata,"custom_field")?$this->CheckInsertApprovalFields($approval_custom_fields,$formdata,"custom_field"):array();
		}else{
		    $custom_field_array = array();
		}
		$final_array = array_merge($core_field_array, $profile_field_array, $custom_field_array);
		if(!empty($final_array)){
			$mail_type = "admin_approval";
			$mail = $this->getEmailTemplate($mail_type);
			$this->SendEmail($final_array,$mail->subject,$mail->message);
		}
		$yoo_theme = base64_decode($input->get('yoo_theme'));
		$file_data = !empty($_FILES)?$_FILES:"";
		$content = Factory::getApplication()->bootComponent('com_users')->getMVCFactory();
		$model = $content->createModel('Profile', 'Site',['ignore_request' => true]);
		if(!empty($approval_core_fields) || !empty($approval_profile_fields) || !empty($approval_custom_fields)){
		$result = $model->save($newformdata);
		}else{
		$result = $model->save($formdata);    
		}
		if(!empty($formdata)){
			$field_data = json_encode($formdata);
			$f_data = $this->uploadMediaField($file_data);
			$check_field = $this->checkUserField();
			if(!$check_field){
				// Get a db connection.
				$db = Factory::getDbo();
				// Create a new query object.
				$query = $db->getQuery(true);
				// Insert columns.
				$columns = array('user_id', 'field_data', 'image_data', 'yoo_theme_data', 'created_time');
				// Insert values.
				$values = array($db->quote($user->id), $db->quote($field_data), $db->quote($f_data), $db->quote($yoo_theme), $db->quote(date("Y-m-d H:i:s")));
				// Prepare the insert query.
				$query
				    ->insert($db->quoteName('#__user_pro_custom_user'))
				    ->columns($db->quoteName($columns))
				    ->values(implode(',', $values));
				// Set the query using our newly populated query object and execute it.
				$db->setQuery($query);

				if ($db->execute())
				{
					$log_message = "{{name}} added records on profile";
					$query = $db->getQuery(true);
					// Insert columns.
					$columns = array('user_id', 'log_message', 'created_time');
					// Insert values.
					$values = array($db->quote($user->id), $db->quote($log_message), $db->quote(date("Y-m-d H:i:s")));
					// Prepare the insert query.
					$query
					    ->insert($db->quoteName('#__user_pro_activity_log'))
					    ->columns($db->quoteName($columns))
					    ->values(implode(',', $values));
					// Set the query using our newly populated query object and execute it.
					$db->setQuery($query);
					$db->execute();
					echo "true";die;
				}else{
					echo "false";die;
				}
			}else{
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				// Fields to update.
				if(!empty($field_data) && !empty($f_data)){
					$fields = array(
					    $db->quoteName('field_data') . ' = ' . $db->quote($field_data),
					    $db->quoteName('image_data') . ' = ' . $db->quote($f_data),
					    $db->quoteName('yoo_theme_data') . ' = ' . $db->quote($yoo_theme)
					);
				}else if(!empty($field_data)){
					$fields = array(
					    $db->quoteName('field_data') . ' = ' . $db->quote($field_data),
					    $db->quoteName('yoo_theme_data') . ' = ' . $db->quote($yoo_theme)
					);
				}else if(!empty($f_data)){
					$fields = array(
					    $db->quoteName('image_data') . ' = ' . $db->quote($f_data),
					    $db->quoteName('yoo_theme_data') . ' = ' . $db->quote($yoo_theme)
					);
				}
				
				// Conditions for which records should be updated.
				$conditions = array(
				    $db->quoteName('user_id') . ' = ' . $db->quote($user->id)
				);
				$query->update($db->quoteName('#__user_pro_custom_user'))->set($fields)->where($conditions);
				$db->setQuery($query);
				if ($db->execute())
				{
					$old_data = (array) json_decode($check_field->field_data);
					$new_data = (array) json_decode($field_data);
					$old_array = array();
					$new_array = array();
					foreach($old_data as $key => $o_d){
						if($key == "com_fields" || $key == "profile"){
							if($key == "com_fields"){
								foreach ($o_d as $k => $value) {
									$old_array[$k] = $value;
								}
							}else{
								foreach ($o_d as $kk => $value) {
									$old_array[$kk] = $value;
								}
							}							
						}else{
							$old_array[$key] = $o_d;
						}
					}

					foreach($new_data as $key => $o_d){
						if($key == "com_fields" || $key == "profile"){
							if($key == "com_fields"){
								foreach ($o_d as $k => $value) {
									$new_array[$k] = $value;
								}
							}else{
								foreach ($o_d as $kk => $value) {
									$new_array[$kk] = $value;
								}
							}							
						}else{
							$new_array[$key] = $o_d;
						}
					}
					
					$result_array = array_diff($new_array,$old_array);
					$final_array = preg_replace('/[0-9]+/', '', implode(",",array_keys($result_array)));
					$log_message = "{{name}} updated records for these fields (".$final_array.") on profile";
					$query = $db->getQuery(true);
					// Insert columns.
					$columns = array('user_id', 'log_message', 'created_time');
					// Insert values.
					$values = array($db->quote($user->id), $db->quote($log_message), $db->quote(date("Y-m-d H:i:s")));
					// Prepare the insert query.
					$query
					    ->insert($db->quoteName('#__user_pro_activity_log'))
					    ->columns($db->quoteName($columns))
					    ->values(implode(',', $values));
					// Set the query using our newly populated query object and execute it.
					$db->setQuery($query);
					$db->execute();
					echo "true";die;
				}else{
					echo "false";die;
				}
			}
		}
	}
	
	public function checkApprovalDataWithFormData($formdata,$approval_core_fields,$approval_profile_fields,$approval_custom_fields){
	    return $this->filterFormData($formdata,$approval_core_fields,$approval_profile_fields,$approval_custom_fields);
	}
	
	public function filterFormData($formdata,$approval_core_fields,$approval_profile_fields,$approval_custom_fields){
	    if(!empty($approval_core_fields)){
    	    foreach($formdata as $key => $value){
    	        foreach($approval_core_fields as $key1 => $value1){
    	            if($key == $key1){
    	                unset($formdata[$key]);
                        break;
    	            }
    	        }
    	    }
	    }
	    if(!empty($approval_profile_fields)){
    	    foreach($formdata["profile"] as $key => $value){
    	        foreach($approval_profile_fields as $key1 => $value1){
    	            if($key == $key1){
    	                unset($formdata["profile"][$key]);
                        break;
    	            }
    	        }
    	    }
	    }
	    if(!empty($approval_custom_fields)){
    	    foreach($formdata["com_fields"] as $key => $value){
    	        foreach($approval_custom_fields as $key1 => $value1){
    	            if($key == $key1){
    	                unset($formdata["com_fields"][$key]);
                        break;
    	            }
    	        }
    	    }
	    }
	    return $formdata;
	}
	
	public function checkUserField(){
		$user = Factory::getUser();	
		// Get a db connection.
		$db = JFactory::getDbo();
		// Create a new query object.
		$query = $db->getQuery(true);
		// Select all records from the user profile table where key begins with "custom.".
		// Order it by the ordering field.
		$query->select('*');
		$query->from($db->quoteName('#__user_pro_custom_user'));
		$query->where($db->quoteName('user_id') . ' = ' . $db->quote($user->id));
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		return $results = $db->loadObject();
	}

	public function uploadMediaField($files){
		if($files){
			$images = array();
			foreach($files as $key => $value){
				if($value['name']){
					$filename = JFile::makeSafe($value['name']); 
					$src  = $value['tmp_name'];
					//Create the uploads folder if not exists in /images folder
					if ( !JFolder::exists( JPATH_SITE . "/images/" ) ) {
					       JFolder::create( JPATH_SITE . "/images/" );
					}
					$dest = JPATH_BASE . '/images/' . $filename;
					$dest_db = '/images/' . $filename;
					$images[$key] = $dest_db;
					JFile::upload($src, $dest);
				}
			}
			if(!empty($images)){
				return json_encode($images);
			}else{
				return;
			}
			
		}
	}

	public static function getUserFieldData(){
		$user = Factory::getUser();	
		// Get a db connection.
		$db = Factory::getDbo();
		// Create a new query object.
		$query = $db->getQuery(true);
		// Select all records from the user profile table where key begins with "custom.".
		// Order it by the ordering field.
		$query->select('*');
		$query->from($db->quoteName('#__user_pro_custom_user'));
		$query->where($db->quoteName('user_id') . ' = ' . $db->quote($user->id));
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		return $results = $db->loadObject();
	}

	public function insertApprovalFields($key,$value,$field_type){
		$user = Factory::getUser();
		$check_entry = $this->checkUserApprovalField($key,$value,$field_type);
		if(empty($check_entry)){
			// Get a db connection.
			$db = Factory::getDbo();
			// Create a new query object.
			$query = $db->getQuery(true);
			// Insert columns.
			$columns = array('user_id', 'field_type', 'field_name', 'field_value', 'status', 'created_time');
			// Insert values.
			$values = array($db->quote($user->id), $db->quote($field_type), $db->quote($key), $db->quote($value), $db->quote(0), $db->quote(date("Y-m-d H:i:s")));
			// Prepare the insert query.
			$query
			    ->insert($db->quoteName('#__user_pro_field_approval'))
			    ->columns($db->quoteName($columns))
			    ->values(implode(',', $values));
			// Set the query using our newly populated query object and execute it.
			$db->setQuery($query);
			$db->execute();
		}
	}

	public function checkUserApprovalField($key,$value,$field_type){
		$user = Factory::getUser();	
		// Get a db connection.
		$db = Factory::getDbo();
		// Create a new query object.
		$query = $db->getQuery(true);
		// Select all records from the user profile table where key begins with "custom.".
		// Order it by the ordering field.
		$query->select('*');
		$query->from($db->quoteName('#__user_pro_field_approval'));
		$query->where($db->quoteName('user_id') . ' = ' . $db->quote($user->id));
		$query->where($db->quoteName('field_type') . ' = ' . $db->quote($field_type));
		$query->where($db->quoteName('field_name') . ' = ' . $db->quote($key));
		$query->where($db->quoteName('field_value') . ' = ' . $db->quote($value));
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		return $results = $db->loadObject();
	}

	public static function checkApprovalField($field_name){
		$user = Factory::getUser();
		$db = Factory::getDbo();
		// Create a new query object.
		$query = $db->getQuery(true);
		// Select all records from the user profile table where key begins with "custom.".
		// Order it by the ordering field.
		$query->select('*');
		$query->from($db->quoteName('#__user_pro_field_approval'));
		$query->where($db->quoteName('user_id') . ' = ' . $db->quote($user->id));
		$query->where($db->quoteName('field_name') . ' = ' . $db->quote($field_name));
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		$results = $db->loadObject();
		return !empty($results->status)?$results->status:'';
	}

	public function CheckInsertApprovalFields($fields,$formdata,$field_type){
		foreach ($fields as $key => $value) {
			if(empty($value)){
				if($field_type == "core_field"){
					$fields[$key] = $formdata[$key];
				}else if($field_type == "profile_field"){
					$fields[$key] = $formdata["profile"][$key];
				}else{
					$fields[$key] = $formdata["com_fields"][$key];
				}
			}
		}
		$final_array = array();
		foreach ($fields as $key => $value) {
			$final_array[$key] = $value;
			$this->insertApprovalFields($key,$value,$field_type);
		}
		return $final_array;
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

	public function SendEmail($final_array,$subject,$message){
		$config = Factory::getConfig();
		$mail_to = $config->get("mailfrom");
		$sitename = $config->get("sitename");
		$user = Factory::getUser();
		$final_array_html = !empty($final_array)?$this->getNewFieldHtml($final_array):array();
		$name = "Admin";
		$to = $user->email;
		$from = array($user->email, $user->name);
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

	public function getNewFieldHtml($final_array){
		$field_html ='<ul>';
		foreach ($final_array as $key => $value) {
			$field_html .='<li>'.$key.' : '.$value.'</li>';
		}
		$field_html .='</ul>';
		return $field_html;
	}

	public function getStatusFieldHtml($approval_data){
		$field_html = '<ul>';
		$field_html .= '<li>'.$approval_data->field_name.' : '.$approval_data->field_value.'</li>';
		$field_html .= '</ul>';
		return $field_html;
	}

	public function getNewUserFieldData($type){
		$user = Factory::getUser();
		$db = Factory::getDbo();
		// Create a new query object.
		$query = $db->getQuery(true);
		// Select all records from the user profile table where key begins with "custom.".
		// Order it by the ordering field.
		$query->select('*');
		$query->from($db->quoteName('#__user_pro_field_approval'));
		$query->where($db->quoteName('user_id') . ' = ' . $db->quote($user->id));
		$query->where($db->quoteName('field_type') . ' = ' . $db->quote($type));
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		return $results = $db->loadObjectList();
	}

	public function SendCustomEmail($approval_data,$subject,$message){
		$config = Factory::getConfig();
		$mail_from = $config->get("mailfrom");
		$sitename = $config->get("sitename");
		$user = Factory::getUser($approval_data->user_id);
		$final_array_html = !empty($approval_data)?$this->getStatusFieldHtml($approval_data):array();
		$name = "Admin";
		$to = $user->email;
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
}