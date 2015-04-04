<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_code
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once __DIR__ . '/table.php';

/**
 * Code tracker user table object.
 */
class CodeTableUser extends CodeTable
{
	/**
	 * {@inheritdoc}
	 */
	protected $_legacyLookup = 'jc_user_id';

	/**
	 * Constructor.
	 *
	 * @param   JDatabaseDriver  $db  A database connector object.
	 */
	public function __construct($db)
	{
		parent::__construct('#__code_users', 'user_id', $db);
	}

	/**
	 * Method to bind an associative array or object to the JTable instance.
	 *
	 * @param   mixed  $source  An associative array or object to bind to the JTable instance.
	 * @param   mixed  $ignore  An optional array or space separated list of properties to ignore while binding.
	 *
	 * @return  boolean  True on success.
	 */
	public function bind($source, $ignore = '')
	{
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
}
