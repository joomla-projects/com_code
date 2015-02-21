<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_code
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Code tracker table object.
 */
class CodeTableTracker extends JTable
{
	/**
	 * Class constructor.
	 *
	 * @param	JDatabaseDriver  $db  A database connector object.
	 */
	public function __construct($db)
	{
		parent::__construct('#__code_trackers', 'tracker_id', $db);
	}

	/**
	 * Method to load a data object by its legacy ID
	 *
	 * @param   integer  $legacyId  The tracker ID to load
	 *
	 * @return  boolean  True on success
	 */
	public function loadByLegacyId($legacyId)
	{
		// Load the database object
		$db = $this->getDbo();

		// Look up the tracker ID based on the legacy ID.
		$db->setQuery(
			$db->getQuery(true)
				->select($this->_tbl_key)
				->from($this->_tbl)
				->where('jc_tracker_id = ' . (int) $legacyId)
		);

		$issueId = (int) $db->loadResult();

		if ($issueId)
		{
			return $this->load($issueId);
		}

		return false;
	}

	/**
	 * Method to store a row in the database from the JTable instance properties.
	 *
	 * @param   boolean  $updateNulls  True to update fields even if they are null.
	 *
	 * @return  boolean  True on success.
	 */
	public function store($updateNulls = false)
	{
		// Verify that a project ID is set, set it if able
		if ($this->project_id === null && $this->jc_project_id !== null)
		{
			$db = $this->getDbo();

			$db->setQuery(
				$db->getQuery(true)
					->select($db->quoteName('project_id'))
					->from($db->quoteName('#__code_projects'))
					->where($db->quoteName('jc_project_id') . ' = ' . $this->jc_project_id)
			);

			if ($result = $db->loadResult())
			{
				$this->project_id = (int) $result;
			}
		}

		// Finish processing
		return parent::store($updateNulls);
	}
}
