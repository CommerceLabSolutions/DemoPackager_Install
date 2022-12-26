<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_User_pro
 * @author      <>
 * @copyright  
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Userpro\Component\User_pro\Administrator\Model;
// No direct access.
defined('_JEXEC') or die;

use \Joomla\CMS\MVC\Model\ListModel;
use \Joomla\Component\Fields\Administrator\Helper\FieldsHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\PluginHelper;
use \Joomla\CMS\Helper\TagsHelper;
use \Joomla\Database\ParameterType;
use \Joomla\Utilities\ArrayHelper;
use Userpro\Component\User_pro\Administrator\Helper\User_proHelper;

/**
 * Methods supporting a list of Fieldapprovals records.
 *
 * @since  1.0.0
 */
class FieldapprovalsModel extends ListModel
{
	

	

	

	

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   Elements order
	 * @param   string  $direction  Order direction
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// List state information.
		parent::populateState("a.id", "ASC");

		$context = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $context);

		// Split context into component and optional section
		$parts = FieldsHelper::extract($context);

		if ($parts)
		{
			$this->setState('filter.component', $parts[0]);
			$this->setState('filter.section', $parts[1]);
		}
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return  string A store id.
	 *
	 * @since   1.0.0
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.state');

		
		return parent::getStoreId($id);
		
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  DatabaseQuery
	 *
	 * @since   1.0.0
	 */
	protected function getListQuery()
    {
        // Create a new query object.
        $db = Factory::getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
            $this->getState(
                'list.select',
                'a.*'
            )
        );

        $query->from($db->quoteName('#__user_pro_field_approval') . ' AS a');
        // If the model is set to check item state, add to the query.
        $state = $this->getState('filter.state');

        if (is_numeric($state)) {
            $query->where($db->quoteName('a.block') . ' = :state')
                ->bind(':state', $state, ParameterType::INTEGER);
        }
        // Filter the items over the search string if set.
        $search = $this->getState('filter.search');

        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $ids = (int) substr($search, 3);
                $query->where($db->quoteName('a.id') . ' = :id');
                $query->bind(':id', $ids, ParameterType::INTEGER);
            } elseif (stripos($search, 'username:') === 0) {
                $search = '%' . substr($search, 9) . '%';
                $query->where($db->quoteName('a.username') . ' LIKE :username');
                $query->bind(':username', $search);
            } else {
                $search = '%' . trim($search) . '%';

                // Add the clauses to the query.
                $query->where(
                    '(' . $db->quoteName('a.name') . ' LIKE :name'
                    . ' OR ' . $db->quoteName('a.username') . ' LIKE :username'
                    . ' OR ' . $db->quoteName('a.email') . ' LIKE :email)'
                )
                    ->bind(':name', $search)
                    ->bind(':username', $search)
                    ->bind(':email', $search);
            }
        }
        // Add the list ordering clause.
        $query->order(
            $db->quoteName($db->escape($this->getState('list.ordering', 'a.name'))) . ' ' . $db->escape($this->getState('list.direction', 'ASC'))
        );

        return $query;
    }

	/**
	 * Get an array of data items
	 *
	 * @return mixed Array of data items on success, false on failure.
	 */
	public function getItems()
	{
		$items = parent::getItems();
		return $items;
	}

	/**
     * SQL server change
     *
     * @param   integer  $userId  User identifier
     *
     * @return  string   Groups titles imploded :$
     */
    public function getUserDisplayedGroups($userId)
    {
        $db = Factory::getDbo();
        $query = $db->getQuery(true)
            ->select($db->quoteName('title'))
            ->from($db->quoteName('#__usergroups', 'ug'))
            ->join('LEFT', $db->quoteName('#__user_usergroup_map', 'map') . ' ON (ug.id = map.group_id)')
            ->where($db->quoteName('map.user_id') . ' = :user_id')
            ->bind(':user_id', $userId, ParameterType::INTEGER);

        try {
            $result = $db->setQuery($query)->loadColumn();
        } catch (\RuntimeException $e) {
            $result = array();
        }

        return implode("\n", $result);
    }

}