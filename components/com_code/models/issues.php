<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_code
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Tracker Model for Joomla Code
 */
class CodeModelIssues extends JModelList
{
	/**
	 * Context string for the model type.
	 *
	 * This is used to handle uniqueness when dealing with the getStoreId() method and caching data structures.
	 *
	 * @var  string
	 */
	protected $context = 'com_code.issues';

	/**
	 * Method to get a JDatabaseQuery object for retrieving the data set from a database.
	 *
	 * @return  JDatabaseQuery  A JDatabaseQuery object to retrieve the data set.
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select($this->getState('item.select', 'a.*'));
		$query->from('#__code_tracker_issues AS a');

		// Join on the tracker table.
		$query->select('t.title AS tracker_title, t.alias AS tracker_alias');
		$query->join('LEFT', '#__code_trackers AS t on t.tracker_id = a.tracker_id');

		// Join on user table for created by information.
		$query->select($query->concatenate(array('cu.first_name', $db->quote(' '), 'cu.last_name')) . ' AS created_user_name');
		$query->join('LEFT', '#__code_users AS cu on cu.user_id = a.created_by');

		// Join on user table for modified by information.
		$query->select($query->concatenate(array('mu.first_name', $db->quote(' '), 'mu.last_name')) . ' AS modified_user_name');
		$query->join('LEFT', '#__code_users AS mu on mu.user_id = a.modified_by');

		// Filter by state.
		$stateFilter = $this->getState('filter.state');

		if (is_numeric($stateFilter))
		{
			$query->where('a.state = ' . (int) $stateFilter);
		}
		elseif (is_array($stateFilter))
		{
			JArrayHelper::toInteger($stateFilter);
			$query->where('a.state IN (' . implode(',', $stateFilter) . ')');
		}

		// Filter by a single or group of trackers.
		$trackerId = $this->getState('filter.tracker_id');

		if (is_numeric($trackerId))
		{
			$op = $this->getState('filter.tracker_id_include', true) ? ' = ' : ' <> ';
			$query->where('a.tracker_id' . $op . (int) $trackerId);
		}
		elseif (is_array($trackerId))
		{
			JArrayHelper::toInteger($trackerId);
			$op = $this->getState('filter.tracker_id_include', true) ? ' IN ' : ' NOT IN ';
			$query->where('a.tracker_id' . $op . '(' . implode(',', $trackerId) . ')');
		}

		// Filter by a single or group of status.
		$status = $this->getState('filter.status_id');

		if (is_numeric($status) && !empty($status))
		{
			$op = $this->getState('filter.status_id_include', true) ? ' = ' : ' <> ';
			$query->where('a.status' . $op . (int) $status);
		}
		elseif (is_array($status))
		{
			JArrayHelper::toInteger($status);
			$op = $this->getState('filter.status_id_include', true) ? ' IN ' : ' NOT IN ';
			$query->where('a.status' . $op . '(' . implode(',', $status) . ')');
		}

		// Filter by a single or group of tags.
		$tagId = $this->getState('filter.tag_id');

		if (is_numeric($tagId))
		{
			$op = $this->getState('filter.tag_id_include', true) ? ' = ' : ' <> ';
			$query->where('tags.tag_id' . $op . (int) $tagId);
			$query->join('LEFT', '#__code_tracker_issue_tag_map AS tags on tags.issue_id = a.issue_id');
			$query->group('a.issue_id');
		}
		elseif (is_array($tagId))
		{
			if (!in_array(-1, $tagId))
			{
				JArrayHelper::toInteger($tagId);
				$tagId = array_map(array($db, 'q'), $tagId);
				$op = $this->getState('filter.tag_id_include', true) ? ' IN ' : ' NOT IN ';
				$query->where('tags.tag_id' . $op . '(' . implode(',', $tagId) . ')');
				$query->join('LEFT', '#__code_tracker_issue_tag_map AS tags on tags.issue_id = a.issue_id');
				$query->group('a.issue_id');
			}
		}

		// Filter by a single or group of submitters.
		$submitterId = $this->getState('filter.submitter_id');
		$submitterName = $this->getState('filter.submitter_name');

		// If there is no user ID but we have a user name use a separate query to find the user IDs. The separate query
		// is much faster than joining against the users array and using LIKE to search it.
		if (empty($submitterId) && !empty($submitterName))
		{
			$submitterName = '%' . trim($submitterName) . '%';

			$nameQuery = $db->getQuery(true)
				->select($db->qn('user_id'))
				->from($db->qn('#__code_users'))
				->where(
					'(' . $db->qn('first_name') . ' LIKE ' . $db->q($submitterName) . ') OR' .
					'(' . $db->qn('last_name') . ' LIKE ' . $db->q($submitterName) . ')'
				);
			$db->setQuery($nameQuery);
			$submitterId = $db->loadColumn(0);

			if (empty($submitterId))
			{
				$submitterId = -1;
			}
		}

		if (is_numeric($submitterId))
		{
			$op = $this->getState('filter.submitter_id_include', true) ? ' = ' : ' <> ';
			$query->where('a.created_by' . $op . (int) $submitterId);
		}
		elseif (is_array($submitterId))
		{
			JArrayHelper::toInteger($submitterId);
			$op = $this->getState('filter.submitter_id_include', true) ? ' IN ' : ' NOT IN ';
			$query->where('a.created_by' . $op . '(' . implode(',', $submitterId) . ')');
		}

		// Filter by a single or group of closers.
		$closerId = $this->getState('filter.closer_id');
		$closerName = $this->getState('filter.closer_name');

		// If there is no user ID but we have a user name use a separate query to find the user IDs. The separate query
		// is much faster than joining against the users array and using LIKE to search it.
		if (empty($closerId) && !empty($closerName))
		{
			$closerName = '%' . trim($closerName) . '%';

			$nameQuery = $db->getQuery(true)
				->select($db->qn('user_id'))
				->from($db->qn('#__code_users'))
				->where(
					'(' . $db->qn('first_name') . ' LIKE ' . $db->q($closerName) . ') OR' .
					'(' . $db->qn('last_name') . ' LIKE ' . $db->q($closerName) . ')'
				);
			$db->setQuery($nameQuery);
			$closerId = $db->loadColumn(0);

			if (empty($closerId))
			{
				$closerId = -1;
			}
		}

		if (is_numeric($closerId))
		{
			$op = $this->getState('filter.closer_id_include', true) ? ' = ' : ' <> ';
			$query->where('a.close_by' . $op . (int) $closerId);
		}
		elseif (is_array($closerId))
		{
			JArrayHelper::toInteger($closerId);
			$op = $this->getState('filter.closer_id_include', true) ? ' IN ' : ' NOT IN ';
			$query->where('a.close_by' . $op . '(' . implode(',', $closerId) . ')');
		}

		/*
		 * Filter by date range or relative date.
		 */

		// Get the field to filter the date based on.
		$dateField = $this->getState('filter.date_field', 'created');

		switch ($dateField)
		{
			case 'modified':
				$dateField = 'a.modified_date';
				break;

			case 'closed':
				$dateField = 'a.closed_date';
				break;

			default:
			case 'created':
				$dateField = 'a.created_date';
				break;
		}

		// Get the date filtering type.
		$dateFiltering = $this->getState('filter.date_filtering', 'off');

		switch ($dateFiltering)
		{
			case 'range':
				$nullDate       = $db->quote($db->getNullDate());
				$startDateRange = $db->quote($this->getState('filter.start_date_range', $nullDate));
				$endDateRange   = $db->quote($this->getState('filter.end_date_range', $nullDate));
				$query->where('(' . $dateField . ' >= ' . $startDateRange . ' AND ' . $dateField . ' <= ' . $endDateRange . ')');
				break;

			case 'relative':
				$nowDate      = $db->quote(JFactory::getDate()->toSql());
				$relativeDate = (int) $this->getState('filter.relative_date', 0);
				$query->where($dateField . ' >= DATE_SUB(' . $nowDate . ', INTERVAL ' . $relativeDate . ' DAY)');
				break;

			case 'off':
			default:
				break;
		}

		/*
		 * Search Filter
		 */
		$search = $this->getState('filter.search');

		if ($search)
		{
			$search = '%' . trim($search) . '%';
			$query->where($db->qn('a') . '.' . $db->qn('title') . ' LIKE ' . $db->q($search));
		}

		// Add the list ordering clause.
		$query->order($this->getState('list.ordering', 'a.created_date') . ' ' . $this->getState('list.direction', 'ASC'));

		return $query;
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication('site');

		// Load the component/page options from the application.
		$this->setState('options', $app->getParams('com_code'));

		// Set the state filter.
		//$this->setState('filter.state', 1);

		// Set the optional filter search string text.
		$this->setState('filter.search', $app->input->getString('search'));

		// Set the tracker filter.
		//$this->setState('filter.tracker_id', 1);
		//$this->setState('filter.tracker_id_include', 1);

		// Set the status filter.
		//$this->setState('filter.status_id', 1);
		//$this->setState('filter.status_id_include', 1);

		// Set the tag filter.
		//$this->setState('filter.tag_id', 1);
		//$this->setState('filter.tag_id_include', 1);

		// Set the submitter filter.
		//$this->setState('filter.submitter_id', 1);
		//$this->setState('filter.submitter_id_include', 1);

		// Set the closer filter.
		//$this->setState('filter.closer_id', 1);
		//$this->setState('filter.closer_id_include', 1);

		// Set the date filters.
		//$this->setState('filter.date_filtering', null);
		//$this->setState('filter.date_field', null);
		//$this->setState('filter.start_date_range', null);
		//$this->setState('filter.end_date_range', null);
		//$this->setState('filter.relative_date', null);

		// Load the list options from the request.
		$listId = $pk . ':' . $app->input->getInt('Itemid', 0);
		$this->setState('list.start', $app->input->getInt('limitstart', 0));
		$this->setState('list.ordering', $app->getUserStateFromRequest('com_code.issues.' . $listId . '.filter_order', 'filter_order', 'a.modified_date', 'string'));
		$this->setState('list.direction', $app->getUserStateFromRequest('com_code.issues.' . $listId . '.filter_order_Dir', 'filter_order_Dir', 'DESC', 'cmd'));
		$this->setState('list.limit', $app->getUserStateFromRequest('com_code.issues.' . $listId . '.limit', 'limit', $app->get('list_limit'), 'int'));
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
	 * @return  string  A store id.
	 */
	protected function getStoreId($id = '')
	{
		$tagId = $this->getState('filter.tag_id', null);

		if (is_null($tagId))
		{
			$tagId = array();
		}
		elseif (!is_array($tagId))
		{
			$tagId = array($tagId);
		}

		// Compile the store id.
		$id .= ':' . $this->getState('filter.state');
		$id .= ':' . $this->getState('filter.tracker_id');
		$id .= ':' . $this->getState('filter.tracker_id_include');
		$id .= ':' . $this->getState('filter.status_id');
		$id .= ':' . $this->getState('filter.status_id_include');
		$id .= ':' . implode(',', $tagId);
		$id .= ':' . $this->getState('filter.tag_id_include');
		$id .= ':' . $this->getState('filter.submitter_id');
		$id .= ':' . $this->getState('filter.submitter_id_include');
		$id .= ':' . $this->getState('filter.closer_id');
		$id .= ':' . $this->getState('filter.closer_id_include');
		$id .= ':' . $this->getState('filter.date_filtering');
		$id .= ':' . $this->getState('filter.date_field');
		$id .= ':' . $this->getState('filter.start_date_range');
		$id .= ':' . $this->getState('filter.end_date_range');
		$id .= ':' . $this->getState('filter.relative_date');
		$id .= ':' . $this->getState('filter.search');

		return parent::getStoreId($id);
	}
}
