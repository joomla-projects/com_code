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
 * A helper class for selection lists in com_code's front-end
 */
class CodeHelperSelect
{
	/**
	 * Get a numeric priority to string map
	 *
	 * @return  array
	 */
	public static function getPrioritiesRaw()
	{
		return array(
			'1' => JText::_('High'),
			'2' => JText::_('Medium High'),
			'3' => JText::_('Medium'),
			'4' => JText::_('Low'),
			'5' => JText::_('Very Low'),
		);
	}

	/**
	 * Return the priorities as JHtml select options
	 *
	 * @return  array
	 */
	public static function getPrioritiesOptions()
	{
		$array = self::getPrioritiesRaw();
		return self::arrayToOptions($array);
	}

	/**
	 * Returns an array mapping status IDs to status strings
	 *
	 * @return  array
	 */
	public static function getStatusRaw()
	{
		return array(
			0 => '',
			1 => 'Duplicate Report',
			2 => 'Not Joomla! Core',
			3 => 'Fixed in SVN',
			3 => 'Fixed in SVN/GitHub',
			4 => 'Unable to Confirm',
			5 => 'Open',
			6 => 'Closed',
			7 => 'Confirmed',
			9 => 'Pending',
			10 => 'Needs Review',
			11 => 'Information Required',
			12 => 'Known Issue',
			13 => 'Fixed in SVN/GitHub',
			13 => 'Ready to commit',
			14 => 'Not a bug',
			16 => 'Not accepted',
			17 => 'Accepted',
			18 => 'Implemented in trunk',
			19 => 'Open',
			20 => 'Closed',
			21 => 'Duplicate Report',
			22 => 'Not Joomla! Core',
			23 => 'Fixed in SVN',
			24 => 'Unable to Confirm',
			25 => 'Open',
			26 => 'Closed',
			27 => 'Confirmed',
			28 => 'In Progress',
			29 => 'Pending',
			30 => 'Incomplete',
			31 => 'Information Required',
			32 => 'Known Issue',
			33 => 'Ready to commit',
			34 => 'Not a bug',
			35 => 'Open',
			51 => 'Pending',
			55 => 'Expected Behavior',
			56 => 'Closed-No Reply',
			59 => 'Referred to Frmework',
			59 => 'Referred to Platform',
			61 => 'Confirmed',
			62 => 'Pending',
			66 => 'Fixed in SVN/GitHub',
			67 => 'Information Required',
			68 => 'Needs Review',
			70 => 'Known Issue',
			71 => 'Not a bug',
			72 => 'Duplicate Report',
			73 => 'Not Joomla! Core',
			74 => 'Unable to Confirm',
			76 => 'Expected Behavior',
			77 => 'Closed-No Reply',
			78 => 'Closed',
			82 => 'Information Required',
			83 => 'Open',
			85 => 'Pending',
			87 => 'Ready to commit',
			88 => 'In Progress',
			89 => 'Fixed in SVN/GitHub',
			90 => 'Information Required',
			91 => 'Needs Review',
			93 => 'Known Issue',
			94 => 'Not a bug',
			95 => 'Duplicate Report',
			96 => 'Not Joomla! Core',
			97 => 'Unable to Confirm',
			99 => 'Expected Behavior',
			101 => 'Closed',
		);
	}

	/**
	 * Returns the statuses as JHtml select options
	 *
	 * @return  array
	 */
	public static function getStatusOptions()
	{
		$array = self::getStatusRaw();
		return self::arrayToOptions($array);
	}

	protected static function arrayToOptions($array)
	{
		$options = array();

		foreach ($array as $k => $v)
		{
			$options = JHtml::_('select.option', $k, $v);
		}

		return $options;
	}

}
