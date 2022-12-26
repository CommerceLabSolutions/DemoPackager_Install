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
use \Joomla\CMS\Helper\TagsHelper;
use \Joomla\Database\ParameterType;
use Joomla\CMS\Plugin\PluginHelper;
use \Joomla\Utilities\ArrayHelper;
use Userpro\Component\User_pro\Administrator\Helper\User_proHelper;

/**
 * Methods supporting a list of Users records.
 *
 * @since  1.0.0
 */
class UsersModel extends ListModel
{
	/**
	* Constructor.
	*
	* @param   array  $config  An optional associative array of configuration settings.
	*
	* @see        JController
	* @since      1.6
	*/
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'name', 'a.name',
                'username', 'a.username',
                'email', 'a.email',
                'block', 'a.block',
                'sendEmail', 'a.sendEmail',
                'registerDate', 'a.registerDate',
                'lastvisitDate', 'a.lastvisitDate',
                'activation', 'a.activation',
                'active',
                'group_id',
                'range',
                'lastvisitrange',
                'state',
                'mfa'
            );
        }

		parent::__construct($config);
	}


	

	

	

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

        $query->from($db->quoteName('#__users') . ' AS a');

        // Include MFA information
        if (PluginHelper::isEnabled('multifactorauth')) {
            $subQuery = $db->getQuery(true)
                ->select(
                    [
                        'MIN(' . $db->quoteName('user_id') . ') AS ' . $db->quoteName('uid'),
                        'COUNT(*) AS ' . $db->quoteName('mfaRecords')
                    ]
                )
                ->from($db->quoteName('#__user_mfa'))
                ->group($db->quoteName('user_id'));
            $query->select($db->quoteName('mfa.mfaRecords'))
                ->join(
                    'left',
                    '(' . $subQuery . ') AS ' . $db->quoteName('mfa'),
                    $db->quoteName('mfa.uid') . ' = ' . $db->quoteName('a.id')
                );

            $mfaState = $this->getState('filter.mfa');

            if (is_numeric($mfaState)) {
                $mfaState = (int) $mfaState;

                if ($mfaState === 1) {
                    $query->where(
                        '((' . $db->quoteName('mfa.mfaRecords') . ' > 0) OR (' .
                        $db->quoteName('a.otpKey') . ' IS NOT NULL AND ' .
                        $db->quoteName('a.otpKey') . ' != ' . $db->quote('') . '))'
                    );
                } else {
                    $query->where(
                        '((' . $db->quoteName('mfa.mfaRecords') . ' = 0 OR ' .
                        $db->quoteName('mfa.mfaRecords') . ' IS NULL) AND (' .
                        $db->quoteName('a.otpKey') . ' IS NULL OR ' .
                        $db->quoteName('a.otpKey') . ' = ' . $db->quote('') . '))'
                    );
                }
            }
        }

        // If the model is set to check item state, add to the query.
        $state = $this->getState('filter.state');

        if (is_numeric($state)) {
            $query->where($db->quoteName('a.block') . ' = :state')
                ->bind(':state', $state, ParameterType::INTEGER);
        }

        // If the model is set to check the activated state, add to the query.
        $active = $this->getState('filter.active');

        if (is_numeric($active)) {
            if ($active == '0') {
                $query->whereIn($db->quoteName('a.activation'), ['', '0']);
            } elseif ($active == '1') {
                $query->where($query->length($db->quoteName('a.activation')) . ' > 1');
            }
        }

        // Filter the items over the group id if set.
        $groupId = $this->getState('filter.group_id');
        $groups  = $this->getState('filter.groups');

        if ($groupId || isset($groups)) {
            $query->join('LEFT', '#__user_usergroup_map AS map2 ON map2.user_id = a.id')
                ->group(
                    $db->quoteName(
                        array(
                            'a.id',
                            'a.name',
                            'a.username',
                            'a.password',
                            'a.block',
                            'a.sendEmail',
                            'a.registerDate',
                            'a.lastvisitDate',
                            'a.activation',
                            'a.params',
                            'a.email',
                            'a.lastResetTime',
                            'a.resetCount',
                            'a.otpKey',
                            'a.otep',
                            'a.requireReset'
                        )
                    )
                );

            if ($groupId) {
                $groupId = (int) $groupId;
                $query->where($db->quoteName('map2.group_id') . ' = :group_id')
                    ->bind(':group_id', $groupId, ParameterType::INTEGER);
            }

            if (isset($groups)) {
                $query->whereIn($db->quoteName('map2.group_id'), $groups);
            }
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

        // Add filter for registration time ranges select list. UI Visitors get a range of predefined
        // values. API users can do a full range based on ISO8601
        $range = $this->getState('filter.range');
        $registrationStart = $this->getState('filter.registrationDateStart');
        $registrationEnd = $this->getState('filter.registrationDateEnd');

        // Apply the range filter.
        if ($range || ($registrationStart && $registrationEnd)) {
            if ($range) {
                $dates = $this->buildDateRange($range);
            } else {
                $dates = [
                    'dNow'   => $registrationEnd,
                    'dStart' => $registrationStart,
                ];
            }

            if ($dates['dStart'] !== false) {
                $dStart = $dates['dStart']->format('Y-m-d H:i:s');

                if ($dates['dNow'] === false) {
                    $query->where($db->quoteName('a.registerDate') . ' < :registerDate');
                    $query->bind(':registerDate', $dStart);
                } else {
                    $dNow = $dates['dNow']->format('Y-m-d H:i:s');

                    $query->where($db->quoteName('a.registerDate') . ' BETWEEN :registerDate1 AND :registerDate2');
                    $query->bind(':registerDate1', $dStart);
                    $query->bind(':registerDate2', $dNow);
                }
            }
        }

        // Add filter for last visit time ranges select list. UI Visitors get a range of predefined
        // values. API users can do a full range based on ISO8601
        $lastvisitrange = $this->getState('filter.lastvisitrange');
        $lastVisitStart = $this->getState('filter.lastVisitStart');
        $lastVisitEnd = $this->getState('filter.lastVisitEnd');

        // Apply the range filter.
        if ($lastvisitrange || ($lastVisitStart && $lastVisitEnd)) {
            if ($lastvisitrange) {
                $dates = $this->buildDateRange($lastvisitrange);
            } else {
                $dates = [
                    'dNow'   => $lastVisitEnd,
                    'dStart' => $lastVisitStart,
                ];
            }

            if ($dates['dStart'] === false) {
                $query->where($db->quoteName('a.lastvisitDate') . ' IS NULL');
            } else {
                $query->where($db->quoteName('a.lastvisitDate') . ' IS NOT NULL');

                $dStart = $dates['dStart']->format('Y-m-d H:i:s');

                if ($dates['dNow'] === false) {
                    $query->where($db->quoteName('a.lastvisitDate') . ' < :lastvisitDate');
                    $query->bind(':lastvisitDate', $dStart);
                } else {
                    $dNow   = $dates['dNow']->format('Y-m-d H:i:s');

                    $query->where($db->quoteName('a.lastvisitDate') . ' BETWEEN :lastvisitDate1 AND :lastvisitDate2');
                    $query->bind(':lastvisitDate1', $dStart);
                    $query->bind(':lastvisitDate2', $dNow);
                }
            }
        }

        // Filter by excluded users
        $excluded = $this->getState('filter.excluded');

        if (!empty($excluded)) {
            $query->whereNotIn($db->quoteName('id'), $excluded);
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
        // Get a storage key.
        $store = $this->getStoreId();

        // Try to load the data from internal storage.
        if (empty($this->cache[$store])) {
            $groups  = $this->getState('filter.groups');
            $groupId = $this->getState('filter.group_id');

            if (isset($groups) && (empty($groups) || $groupId && !in_array($groupId, $groups))) {
                $items = array();
            } else {
                $items = parent::getItems();
            }

            // Bail out on an error or empty list.
            if (empty($items)) {
                $this->cache[$store] = $items;

                return $items;
            }

            // Joining the groups with the main query is a performance hog.
            // Find the information only on the result set.

            // First pass: get list of the user ids and reset the counts.
            $userIds = array();

            foreach ($items as $item) {
                $userIds[] = (int) $item->id;

                $item->group_count = 0;
                $item->group_names = '';
                $item->note_count = 0;
            }

            // Get the counts from the database only for the users in the list.
            $db = Factory::getDbo();
            $query = $db->getQuery(true);

            // Join over the group mapping table.
            $query->select('map.user_id, COUNT(map.group_id) AS group_count')
                ->from('#__user_usergroup_map AS map')
                ->whereIn($db->quoteName('map.user_id'), $userIds)
                ->group('map.user_id')
                // Join over the user groups table.
                ->join('LEFT', '#__usergroups AS g2 ON g2.id = map.group_id');

            $db->setQuery($query);

            // Load the counts into an array indexed on the user id field.
            try {
                $userGroups = $db->loadObjectList('user_id');
            } catch (\RuntimeException $e) {
                $this->setError($e->getMessage());

                return false;
            }

            $query->clear()
                ->select('n.user_id, COUNT(n.id) As note_count')
                ->from('#__user_notes AS n')
                ->whereIn($db->quoteName('n.user_id'), $userIds)
                ->where('n.state >= 0')
                ->group('n.user_id');

            $db->setQuery($query);

            // Load the counts into an array indexed on the aro.value field (the user id).
            try {
                $userNotes = $db->loadObjectList('user_id');
            } catch (\RuntimeException $e) {
                $this->setError($e->getMessage());

                return false;
            }

            // Second pass: collect the group counts into the master items array.
            foreach ($items as &$item) {
                if (isset($userGroups[$item->id])) {
                    $item->group_count = $userGroups[$item->id]->group_count;

                    // Group_concat in other databases is not supported
                    $item->group_names = $this->getUserDisplayedGroups($item->id);
                }

                if (isset($userNotes[$item->id])) {
                    $item->note_count = $userNotes[$item->id]->note_count;
                }
            }

            // Add the items to the internal cache.
            $this->cache[$store] = $items;
        }

        return $this->cache[$store];
    }

    /**
     * Get an array of data items
     *
     * @return mixed Array of data items on success, false on failure.
     */
    public function getLogItems()
    {
        $app  = Factory::getApplication();
        $user_id = $app->input->post->get("id", '');
        $db = Factory::getDbo();
        $query = $db->getQuery(true)
            ->select("*")
            ->from($db->quoteName('#__user_pro_activity_log', 'upal'))
            ->join('LEFT', $db->quoteName('#__users', 'u') . ' ON (u.id = upal.user_id)')
            ->where($db->quoteName('upal.user_id') . ' = :user_id')
            ->bind(':user_id', $user_id, ParameterType::INTEGER);

        try {
            $result = $db->setQuery($query)->loadObjectList();
        } catch (\RuntimeException $e) {
            $result = array();
        }

        return $result;
    }

    /**
     * SQL server change
     *
     * @param   integer  $userId  User identifier
     *
     * @return  string   Groups titles imploded :$
     */
    protected function getUserDisplayedGroups($userId)
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

    /**
     * Method to get an object.
     *
     * @param   integer $id The id of the object to get.
     *
     * @return  mixed    Object on success, false on failure.
     *
     * @throws Exception
     */
    public function getUserItem($id = null)
    {
        $app  = Factory::getApplication();
        $user_id   = $app->input->getCmd('id', '');
        $db = Factory::getDbo();
        $query = $db->getQuery(true)
            ->select(array('u.field_data,u.image_data,u.yoo_theme_data'))
            ->from($db->quoteName('#__user_pro_custom_user', 'u'))
            ->where($db->quoteName('u.user_id') . ' = :user_id')
            ->bind(':user_id', $user_id, ParameterType::INTEGER);
        try { 
            $result = $db->setQuery($query)->loadObject();

        } catch (\RuntimeException $e) {
            $result = array();
        }

        return $result;
        
    }
}
