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
 * Issue Model for Joomla Code
 */
class CodeModelIssue extends JModelLegacy
{
	/**
	 * Fetch the comments for a specified issue
	 *
	 * @param   integer  $issueId  JoomlaCode Issue ID to search by, uses the issue from the request if one is not specified
	 *
	 * @return  stdClass
	 */
	public function getComments($issueId = null)
	{
		$issueId = empty($issueId) ? $this->getState('issue.id') : $issueId;

		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select('a.*, ' . $query->concatenate(array('cu.first_name', $db->quote(' '), 'cu.last_name')) . ' AS commenter_name')
			->from('#__code_tracker_issue_responses AS a')
			->join('LEFT', '#__code_users AS cu ON cu.user_id = a.created_by')
			->where('a.jc_issue_id = ' . (int) $issueId)
			->order('a.created_date ASC');

		$db->setQuery($query);

		try
		{
			return $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			$this->setError(JText::sprintf('COM_CODE_ERROR_FETCHING_COMMENTS', $issueId));
		}
	}

	/**
	 * Fetch the commits for a specified issue
	 *
	 * @param   integer  $issueId  JoomlaCode Issue ID to search by, uses the issue from the request if one is not specified
	 *
	 * @return  stdClass
	 */
	public function getCommits($issueId = null)
	{
		$issueId = empty($issueId) ? $this->getState('issue.id') : $issueId;

		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select('a.*, ' . $query->concatenate(array('cu.first_name', $db->quote(' '), 'cu.last_name')) . ' AS committer_name')
			->from('#__code_tracker_issue_commits AS a')
			->join('LEFT', '#__code_users AS cu ON cu.user_id = a.created_by')
			->where('a.jc_issue_id = ' . (int) $issueId)
			->order('a.created_date ASC');

		$db->setQuery($query);

		try
		{
			return $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			$this->setError(JText::sprintf('COM_CODE_ERROR_FETCHING_COMMITS', $issueId));
		}
	}

	/**
	 * Fetch the specified issue
	 *
	 * @param   integer  $issueId  JoomlaCode Issue ID to search by, uses the issue from the request if one is not specified
	 *
	 * @return  stdClass
	 */
	public function getItem($issueId = null)
	{
		$issueId = empty($issueId) ? $this->getState('issue.id') : $issueId;

		$db = $this->getDbo();

		$db->setQuery(
			$db->getQuery(true)
				->select('a.*, s.state_id AS state, s.title AS status_name')
				->from('#__code_tracker_issues AS a')
				->join('LEFT', '#__code_tracker_status AS s on s.jc_status_id = a.status')
				->where('a.jc_issue_id = ' . (int) $issueId)
		);

		try
		{
			return $db->loadObject();
		}
		catch (RuntimeException $e)
		{
			$this->setError(JText::sprintf('COM_CODE_ERROR_FETCHING_ISSUE', $issueId));
		}
	}

	/**
	 * Fetch the tags for a specified issue
	 *
	 * @param   integer  $issueId  JoomlaCode Issue ID to search by, uses the issue from the request if one is not specified
	 *
	 * @return  stdClass
	 */
	public function getTags($issueId = null)
	{
		$issueId = empty($issueId) ? $this->getState('issue.id') : $issueId;

		$db = $this->getDbo();

		$subQuery = $db->getQuery(true)
			->select('tag_id')
			->from('#__code_tracker_issue_tag_map')
			->where('issue_id = ' . $issueId);

		$db->setQuery(
		   $db->getQuery(true)
				->select('tag')
				->from('#__code_tags')
				->where('tag_id IN (' . (string) $subQuery . ')')
				->order('tag ASC')
		);

		try
		{
			return $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			$this->setError(JText::sprintf('COM_CODE_ERROR_FETCHING_TAGS', $issueId));
		}
	}

	public function getTracker($issueId = null)
	{
		$issueId = empty($issueId) ? $this->getState('issue.id') : $issueId;

		$item = $this->getItem($issueId);

		$db = $this->getDbo();
		$db->setQuery(
		   $db->getQuery(true)
				->select('*')
				->from($db->quoteName('#__code_trackers'))
				->where($db->quoteName('jc_tracker_id') . ' = ' . (int) $item->tracker_id)
		);

		try
		{
			return $db->loadObject();
		}
		catch (RuntimeException $e)
		{
			JError::raiseError(500, 'Unable to access resource: ' . $e->getMessage());
		}
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * @return  void
	 *
	 * @note    Calling getState in this method will result in recursion.
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication();

		// Set the JoomlaCode issue ID from the request.
		$pk = $app->input->getInt('issue_id');
		$this->setState('issue.id', $pk);
	}
}
