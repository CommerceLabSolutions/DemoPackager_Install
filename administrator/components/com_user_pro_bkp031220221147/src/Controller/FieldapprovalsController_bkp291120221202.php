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
			$approval_data = plgSystemUniversal_elements::getApprovalDataById($id);
			if($status_type == 1){
				$mail =  plgSystemUniversal_elements::getEmailTemplate('approved_email');
				plgSystemUniversal_elements::SendCustomEmail($approval_data,$mail->subject,$mail->message);
			}else{
				$mail =  plgSystemUniversal_elements::getEmailTemplate('denied_email');
				plgSystemUniversal_elements::SendCustomEmail($approval_data,$mail->subject,$mail->message);
			}
		}
	}
}
