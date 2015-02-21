<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_code
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

/**
 * Code tracker user table object.
 */
class CodeTableUser extends JTable
{
	/**
	 * Constructor.
	 *
	 * @param   JDatabaseDriver  $db  A database connector object.
	 */
	public function __construct($db)
	{
		parent::__construct('#__users', 'id', $db);
	}

	/**
	 * Method to bind an associative array or object to the JTable instance.
	 *
	 * @param   mixed  $src     An associative array or object to bind to the JTable instance.
	 * @param   mixed  $ignore  An optional array or space separated list of properties to ignore while binding.
	 *
	 * @return  boolean  True on success.
	 */
	public function bind($source, $ignore = '')
	{
		// If the params field of the source array is an array, convert it to an INI string.
		if (is_array($source) && array_key_exists('params', $source) && is_array($source['params']))
		{
			$registry = new Registry;
			$registry->loadArray($source['params']);
			$source['params'] = $registry->toString();
		}

		// Optionally combine the first and last names into the name.
		if (!empty($source['first_name']) && !empty($source['last_name']))
		{
			$source['name'] = $source['first_name'] . ' ' . $source['last_name'];
		}

		// Special casees for the agreement flags.
		if (empty($source['agreed_tos']))
		{
			$source['agreed_tos'] = 0;
		}

		if (empty($source['signed_jca']))
		{
			$source['signed_jca'] = 0;
		}

		// Execute the parent bind method.
		return parent::bind($source, $ignore);
	}

	/**
	 * Method to perform data validation and sanitization before storage.
	 *
	 * @return   boolean  True on success.
	 *
	 * @since    1.0
	 */
	public function check()
	{
		// Ensure there is a name.
		if (trim($this->name) == '')
		{
			$this->setError(JText::_('Please enter your name.'));

			return false;
		}

		// Ensure there is a login name.
		if (trim($this->username) == '')
		{
			$this->setError(JText::_('Please enter a user name.'));

			return false;
		}

		// Ensure the login name is valid.
		if (preg_match("/[<>\"'%;()&]/", $this->username) || (strlen(utf8_decode($this->username)) < 2))
		{
			$this->setError(JText::sprintf('VALID_AZ09', JText::_('Username'), 2));

			return false;
		}

		// Ensure the email address is valid.
		if ((trim($this->email) == '') || !JMailHelper::isEmailAddress($this->email))
		{
			$this->setError(JText::_('WARNREG_MAIL'));

			return false;
		}

		// Set the registration timestamp if necessary.
		if ($this->registerDate == null)
		{
			$this->registerDate = JFactory::getDate()->toSql();
		}

		// Ensure the login name is not already being used.
		$db = $this->getDbo();

		$db->setQuery(
			$db->getQuery(true)
				->select($db->quoteName('id'))
				->from($db->quoteName('#__users'))
				->where($db->quoteName('username') . ' = ' . $db->quote($this->username))
				->where($db->quoteName('id') . ' <> ' . (int) $id)
		);

		$xid = intval($db->loadResult());

		if ($xid && $xid != intval($this->id))
		{
			$this->setError(JText::_('WARNREG_INUSE'));

			return false;
		}

		$db->setQuery(
			$db->getQuery(true)
				->select($db->quoteName('id'))
				->from($db->quoteName('#__users'))
				->where($db->quoteName('email') . ' = ' . $db->quote($this->email))
				->where($db->quoteName('id') . ' <> ' . (int) $id)
		);

		$xid = intval($db->loadResult());

		if ($xid && $xid != intval($this->id))
		{
			$this->setError(JText::_('WARNREG_EMAIL_INUSE'));

			return false;
		}

		return true;
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
		$db = $this->getDbo();

		// Look up the user id based on the legacy id.
		$db->setQuery(
			$db->getQuery(true)
				->select($db->quoteName('user_id'))
				->from($db->quoteName('#__code_users'))
				->where($db->quoteName('jc_user_id') . ' <> ' . (int) $legacyId)
		);

		$userId = (int) $db->loadResult();

		if ($userId)
		{
			return $this->legacyLoad($userId);
		}

		return false;
	}

	/**
	 * Load the object by e-mail address
	 *
	 * @param   string  $email  E-mail address to lookup
	 *
	 * @return  boolean  True on success
	 */
	public function loadByEmail($email)
	{
		$db = $this->getDbo();

		// Look up the user id based on the email.
		$db->setQuery(
			$db->getQuery(true)
				->select($db->quoteName('id'))
				->from($db->quoteName('#__users'))
				->where($db->quoteName('email') . ' = ' . $db->quote($email))
		);

		$userId = (int) $db->loadResult();

		if ($userId)
		{
			return $this->legacyLoad($userId);
		}

		return false;
	}

	/**
	 * Method to load the user data from the database and bind it to the object.
	 *
	 * @param   integer  $userId  The primary key of the user record to load.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.0
	 */
	public function legacyLoad($userId = null)
	{
		// Get the primary key.
		$k = $this->getKeyName();

		if ($userId !== null)
		{
			$this->$k = $userId;
		}

		$userId = $this->$k;

		// If no primary key is set return false.
		if ($userId === null)
		{
			return false;
		}

		// Reset the object.
		$this->reset();

		// Load the core data fields.
		$db = $this->getDbo();

		$db->setQuery(
			$db->getQuery(true)
				->select('*')
				->from($db->quoteName($this->getTableName()))
				->where($db->quoteName($this->getKeyName()) . ' = ' . (int) $userId)
		);

		try
		{
			$result = $db->loadAssoc();
		}
		catch (RuntimeException $e)
		{
			$this->setError($e->getMessage());

			return false;
		}

		if ($this->bind($result))
		{
			// Load the extended data fields.
			$db->setQuery(
				$db->getQuery(true)
					->select('*')
					->from($db->quoteName('#__code_users'))
					->where($db->quoteName('user_id') . ' = ' . (int) $userId)
			);

			try
			{
				$result = $db->loadAssoc();

				return $this->bind($result);
			}
			catch (RuntimeException $e)
			{
				$this->setError($e->getMessage());

				return false;
			}
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
		// Get the core and extended data objects.
		$core = $this->_getCoreObject();
		$extd = $this->_getExtendedObject($core);

		$k = $this->_tbl_key;
		$key = $this->$k;

		if ($key)
		{
			// Only process extended table, not #__users
			// Determine if the extended table has a row.
			$this->_db->setQuery(
				'SELECT user_id' .
				' FROM #__code_users' .
				' WHERE user_id = ' . (int) $key
			);

			// If the extended record exists update it.
			if ($this->_db->loadResult())
			{
				$ret = $this->_db->updateObject('#__code_users', $extd, 'user_id', $updateNulls);
			}
			// If the extended record does not exist insert it.
			else
			{
				$extd->user_id = 0;
				$ret = $this->_db->insertObject('#__code_users', $extd, 'user_id');
			}

		}
		else
		{
			$this->$k = 0;

			// Only process the #__code_users table
			// Set the primary key and insert the extended data record.
			$extd->user_id = 0;
			$ret = $this->_db->insertObject('#__code_users', $extd, 'user_id');
		}

		if (!$ret)
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		return true;
	}

	/**
	 * Method to delete a row from the database table by primary key value.
	 *
	 * @param   mixed  $pk  An optional primary key value to delete.  If not set the instance property value is used.
	 *
	 * @return  boolean  True on success.
	 */
	public function delete($userId = null)
	{
		// Set the primary key if passed as an argument.
		$k = $this->_tbl_key;

		if ($userId)
		{
			$this->$k = (int) $userId;
		}

		$db = $this->getDbo();

		// Remove the record from the users table.
		$db->setQuery(
			$db->getQuery(true)
				->delete($this->_tbl)
				->where($this->_tbl_key . ' = ' . (int) $this->$k)
		);

		try
		{
			$db->execute();
		}
		catch (RuntimeException $exception)
		{
			$this->setError($exception->getMessage());

			return false;
		}

		// Remove the extended user data.
		$db->setQuery(
			$db->getQuery(true)
				->delete('#__code_users')
				->where('user_id = ' . (int) $this->$k)
		);

		try
		{
			$db->execute();
		}
		catch (RuntimeException $exception)
		{
			$this->setError($exception->getMessage());

			return false;
		}

		// Remove the user group mappings.
		$db->setQuery(
			$db->getQuery(true)
				->delete('#__user_usergroup_map')
				->where('user_id = ' . (int) $userId)
		);

		try
		{
			$db->execute();
		}
		catch (RuntimeException $exception)
		{
			$this->setError($exception->getMessage());

			return false;
		}

		// Remove any message information from the database for the user.
		$db->setQuery(
			$db->getQuery(true)
				->delete('#__messages_cfg')
				->where('user_id = ' . (int) $this->$k)
		);

		try
		{
			$db->execute();
		}
		catch (RuntimeException $exception)
		{
			$this->setError($exception->getMessage());

			return false;
		}

		$db->setQuery(
			$db->getQuery(true)
				->delete('#__messages')
				->where('user_id_to = ' . (int) $this->$k)
		);

		try
		{
			$db->execute();
		}
		catch (RuntimeException $exception)
		{
			$this->setError($exception->getMessage());

			return false;
		}

		return true;
	}

	/**
	 * Method to get a data object for the core users table.
	 *
	 * @return    object    Data object for the core users table.
	 * @since    1.0
	 */
	protected function _getCoreObject()
	{
		$obj = new stdClass;
		$obj->id = $this->id;
		$obj->name = $this->name;
		$obj->username = $this->username;
		$obj->email = $this->email;
		$obj->password = $this->password;
		$obj->usertype = $this->usertype;
		$obj->block = $this->block;
		$obj->sendEmail = $this->sendEmail;
		$obj->registerDate = $this->registerDate;
		$obj->lastvisitDate = $this->lastvisitDate;
		$obj->activation = $this->activation;
		$obj->params = $this->params;

		return $obj;
	}

	/**
	 * Method to get a data object for the extended users table.
	 *
	 * @return    object    Data object for the extended users table.
	 * @since    1.0
	 */
	protected function _getExtendedObject($core)
	{
		// Get the array diff of the table object properties excluding the core table properties.
		$extended = array_diff_assoc($this->getProperties(), get_object_vars($core));

		// Set the primary key.
		$k = $this->_tbl_key;
		$extended['user_id'] = $this->$k;
		unset($extended['lastResetTime']);
		unset($extended['resetCount']);
		return (object) $extended;
	}
}
