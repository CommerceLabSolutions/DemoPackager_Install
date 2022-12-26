<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_User_pro
 * @author      <>
 * @copyright  
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Userpro\Component\User_pro\Administrator\View\Setting;
// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use \Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use \Userpro\Component\User_pro\Administrator\Helper\User_proHelper;
use \Joomla\CMS\Toolbar\Toolbar;
use \Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Helper\ContentHelper;
use \Joomla\CMS\Language\Text;
use \Joomla\Component\Content\Administrator\Extension\ContentComponent;
use \Joomla\CMS\Form\Form;
use \Joomla\CMS\HTML\Helpers\Sidebar;
/**
 * View class for a list of Dashboard.
 *
 * @since  1.0.0
 */
class HtmlView extends BaseHtmlView
{
	protected $items;

	protected $pagination;

	protected $state;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		$app  = Factory::getApplication();
        $layout   = $app->input->getCmd('layout', '');
        if(!$layout){
			$this->items = $this->get('EmailTemplateData');
			$this->canDo         = ContentHelper::getActions('com_users');
	        $this->db            = Factory::getDbo();
			// Check for errors.
			if (count($errors = $this->get('Errors')))
			{
				throw new \Exception(implode("\n", $errors));
			}
			
			$this->addToolbar();

			$this->sidebar = Sidebar::render();
		}else{
            $this->template_content = $this->get('TemplateContent');
            $this->showPageTitle(); 
        }
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	protected function addToolbar()
	{
		$state = $this->get('State');
		$canDo = User_proHelper::getActions();

		ToolbarHelper::title(Text::_('COM_USER_PRO_TITLE_SETTING'), "generic");

		$toolbar = Toolbar::getInstance('toolbar');

		// Check if the form exists before showing the add/edit buttons
		$formPath = JPATH_COMPONENT_ADMINISTRATOR . '/src/View/Setting';
		
		// Set sidebar action
		Sidebar::setAction('index.php?option=com_user_pro&view=setting');
	}
	
	
	/**
	 * Method to order fields 
	 *
	 * @return void 
	 */
	protected function getSortFields()
	{
		return array(
			'a.`id`' => Text::_('JGRID_HEADING_ID'),
			'a.`state`' => Text::_('JSTATUS'),
			'a.`ordering`' => Text::_('JGRID_HEADING_ORDERING'),
			'a.`name`' => Text::_('COM_USER_PRO_USERPROS_NAME'),
			'a.`username`' => Text::_('COM_USER_PRO_USERPROS_USERNAME'),
			'a.`email`' => Text::_('COM_USER_PRO_USERPROS_EMAIL'),
		);
	}

	/**
	 * Check if state is set
	 *
	 * @param   mixed  $state  State
	 *
	 * @return bool
	 */
	public function getState($state)
	{
		return isset($this->state->{$state}) ? $this->state->{$state} : false;
	}

	 protected function showPageTitle()
    {
        ToolbarHelper::title(Text::_('COM_USER_PRO_USERS_EDIT_EMAIL_SETTING_TITLE'), 'Edit Email');
    }
}
