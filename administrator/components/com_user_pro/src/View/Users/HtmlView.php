<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_User_pro
 * @author     demowebflow webflow <demowebflowwebflow@gmail.com>
 * @copyright  2022 demowebflow webflow
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Userpro\Component\User_pro\Administrator\View\Users;
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
 * View class for a list of Userpros.
 *
 * @since  1.0.0
 */
class HtmlView extends BaseHtmlView
{
	protected $items;

	protected $pagination;

	protected $state;

	/**
     * An array with active filters.
     *
     * @var    array
     * @since  3.6.3
     */
    public $activeFilters;

    /**
     * An ACL object to verify user rights.
     *
     * @var    CMSObject
     * @since  3.6.3
     */
    protected $canDo;

    /**
     * An instance of DatabaseDriver.
     *
     * @var    DatabaseDriver
     * @since  3.6.3
     *
     * @deprecated 5.0 Will be removed without replacement
     */
    protected $db;

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
    		$this->state = $this->get('State');
    		$this->items = $this->get('Items');
    		$this->pagination = $this->get('Pagination');
    		$this->filterForm = $this->get('FilterForm');
    		$this->activeFilters = $this->get('ActiveFilters');
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
            $this->state = $this->get('State');
    		$this->items = $this->get('Items');
    		$this->pagination = $this->get('Pagination');
            $this->items = $this->get('LogItems');
            $this->item  = $this->get('UserItem'); 
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
        $canDo = $this->canDo;        

        // Get the toolbar object instance
        $toolbar = Toolbar::getInstance('toolbar');

        ToolbarHelper::title(Text::_('COM_USER_PRO_USERS_VIEW_USERS_TITLE'), 'users user');

        // if ($canDo->get('core.create')) {
        //     $toolbar->addNew('user.add');
        // }

        // if ($canDo->get('core.edit.state') || $canDo->get('core.admin')) {
        //     $dropdown = $toolbar->dropdownButton('status-group')
        //         ->text('JTOOLBAR_CHANGE_STATUS')
        //         ->toggleSplit(false)
        //         ->icon('icon-ellipsis-h')
        //         ->buttonClass('btn btn-action')
        //         ->listCheck(true);

        //     $childBar = $dropdown->getChildToolbar();

        //     $childBar->publish('users.activate', 'COM_USER_PRO_USERS_TOOLBAR_ACTIVATE', true);
        //     $childBar->unpublish('users.block', 'COM_USER_PRO_USERS_TOOLBAR_BLOCK', true);
        //     $childBar->standardButton('unblock')
        //         ->text('COM_USER_PRO_USERS_TOOLBAR_UNBLOCK')
        //         ->task('users.unblock')
        //         ->listCheck(true);

        //     // Add a batch button
        //     if (
        //         $user->authorise('core.create', 'com_users')
        //         && $user->authorise('core.edit', 'com_users')
        //         && $user->authorise('core.edit.state', 'com_users')
        //     ) {
        //         $childBar->popupButton('batch')
        //             ->text('JTOOLBAR_BATCH')
        //             ->selector('collapseModal')
        //             ->listCheck(true);
        //     }

        //     if ($canDo->get('core.delete')) {
        //         $childBar->delete('users.delete')
        //             ->text('JTOOLBAR_DELETE')
        //             ->message('JGLOBAL_CONFIRM_DELETE')
        //             ->listCheck(true);
        //     }
        // }

        if ($canDo->get('core.admin') || $canDo->get('core.options')) {
            //$toolbar->preferences('com_users');
        }

        //$toolbar->help('Users');
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
        ToolbarHelper::title(Text::_('COM_USER_PRO_USERS_LAYOUT_PROFILE_TITLE'), 'users profile');
    }
}
