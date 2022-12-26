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
use Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\FormController;

/**
 * Dashboard controller class.
 *
 * @since  1.0.0
 */
class SettingController extends FormController
{
	protected $view_list = 'setting';

    public function saveEmailContent(){
        $input = Factory::getApplication()->input;
        $db = Factory::getDbo();
        $query = $db->getQuery(true);
        // Fields to update.
        $fields = array(
            $db->quoteName('title') . ' = ' . $db->quote($input->get('title')),
            $db->quoteName('subject') . ' = ' . $db->quote($input->get('subject')),
            $db->quoteName('message') . ' = ' . $db->quote($input->getHtml('message'))
        );
        // Conditions for which records should be updated.
        $conditions = array(
            $db->quoteName('id') . ' = ' . $db->quote($input->get('id'))
        );
        $query->update($db->quoteName('#__user_pro_email_setting'))->set($fields)->where($conditions);
        $db->setQuery($query);
        $db->execute();
        $application = Factory::getApplication();
        $application->enqueueMessage(Text::_('Item saved'), 'Success');
        $application->redirect("index.php?option=com_user_pro&view=setting&layout=edit&id=".$input->get('id'));
        exit(0);
    }
}
