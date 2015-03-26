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
	 * @param   string  $defaultOptionKey  The translation key for the default selection option
	 *
	 * @return  array
	 */
	public static function getPrioritiesOptions($defaultOptionKey = null)
	{
		$array = self::getPrioritiesRaw();
		return self::arrayToOptions($array, $defaultOptionKey);
	}

	/**
	 * Returns an array mapping status IDs to status strings
	 *
	 * @return  array
	 */
	public static function getStatusRaw()
	{
		return array(
			1 => 'Duplicate Report',
			2 => 'Not Joomla! Core',
			3 => 'Fixed in SVN/GitHub',
			4 => 'Unable to Confirm',
			5 => 'Open',
			6 => 'Closed',
			7 => 'Confirmed',
			9 => 'Pending',
			10 => 'Needs Review',
			11 => 'Information Required',
			12 => 'Known Issue',
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
			59 => 'Referred to Framework / Platform',
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
	 * @param   string  $defaultOptionKey  The translation key for the default selection option
	 *
	 * @return  array
	 */
	public static function getStatusOptions($defaultOptionKey = null)
	{
		$array = self::getStatusRaw();
		return self::arrayToOptions($array, $defaultOptionKey);
	}

	/**
	 * Returns the issue tags
	 *
	 * @return  array
	 */
	public static function getTagRaw()
	{
		return array(
			1 => 'Administration',
			2 => 'Google Chrome 2.x',
			3 => 'Administrator',
			4 => 'MySQL 5.2.x',
			5 => 'Apache 2.2.x',
			6 => 'Joomla! Libraries',
			7 => 'Developer',
			8 => 'User',
			9 => 'PHP 5.2.x',
			10 => 'MySQL 5.0.x',
			11 => 'Components',
			12 => 'Firefox 3.x',
			13 => 'Installation',
			14 => 'Firefox 2.x',
			15 => 'Other',
			16 => 'Internet Explorer 8.x',
			17 => 'Database',
			18 => 'MySQL 5.1.x',
			19 => 'Templates',
			20 => 'Plugins',
			21 => 'Media Manager',
			22 => 'Languages',
			23 => 'Modules',
			24 => 'PHP 5.3.x',
			25 => 'User Interface',
			26 => 'Apache 2.0.x',
			27 => 'Layouts',
			28 => 'PHP 5.1.x',
			29 => 'Authentication &amp; Login',
			30 => 'Apache 1.3.x',
			31 => 'Internet Explorer 5.x',
			32 => 'Internet Explorer 6.x',
			33 => 'IIS 6.x',
			34 => 'Search Engine Friendly',
			35 => 'Javascript',
			36 => 'Browsers',
			37 => 'Internet Explorer 7.x',
			38 => 'MySQL 4.1.x',
			39 => 'ACL',
			40 => 'Forms',
			41 => 'Safari 4.x',
			42 => 'PHP 5.0.x',
			43 => 'Web Services',
			44 => 'Automated Test',
			45 => 'PHP 4.3.x',
			46 => 'IIS 7.x',
			47 => 'MySQL 4.0.x',
			48 => 'Opera 9.x',
			49 => 'RTL',
			50 => 'Legacy Compatibility',
			51 => 'PHP 4.4.x',
			52 => 'IIS 5.x',
			53 => 'Other Libraries',
			54 => 'Firefox 1.x',
			55 => 'PHP 5.3',
			56 => 'WYSIWYG Editors',
			57 => 'Safari 2.x',
			58 => 'XML-RPC',
			59 => 'MySQL 3.x',
			60 => 'Opera 7.x',
			61 => 'Konqueror 3.x',
			62 => 'IIS 4.x',
			82 => 'Front End',
			83 => 'All',
			84 => 'Netscape 8.x',
			85 => 'Code Style',
			88 => 'Authentication and Login',
			89 => 'Google Chrome 10.x',
			90 => 'Firefox 4.x',
			91 => 'Extension Install',
			92 => 'Internet Explorer 9.x',
			94 => 'Platform',
			95 => 'Sample Data',
			96 => 'Pull Request',
			97 => 'Not Platform',
			98 => 'Fixed in Platform',
			99 => 'PHP 5.4.x',
			101 => 'Google Chrome 14 ',
			102 => 'Firefox Other',
			103 => 'Google Chrome 13',
			104 => 'Firefox 6.x',
			105 => 'Google Chrome Android',
			106 => 'MS SQL Srv',
			107 => 'Firefox 5.x',
			108 => 'Other New Browser',
			109 => 'Azure',
			110 => 'Pull Request Needed',
			111 => 'No Platform Implications',
			112 => 'Code Quality',
			113 => 'External Libraries',
			114 => 'SQL Files ',
			115 => 'Platform Only',
			116 => 'Pull Request Sent',
			117 => 'Opera 11.x',
			118 => 'Mixed Platform and CMS',
			119 => 'Safari 5.x',
			120 => 'In Platform Tracker',
			122 => 'First',
			123 => 'Postgres',
			124 => 'Second',
			125 => 'CMS Libraries',
			126 => 'PHP &lt; 5.2',
			127 => 'Third',
			128 => 'Fourth',
			129 => 'Other Mobile',
			130 => 'Yes',
			131 => 'Google Chrome 7.x',
			132 => 'Google Chrome 3.x',
			134 => 'Opera Mobile',
			135 => 'Safari iPhone',
			136 => 'Safari Other',
			137 => 'Safari iPad',
			138 => 'Easy',
			139 => 'Google Chrome 12',
			140 => 'MySQL 5.5.x',
			141 => 'MySql 5.3.x',
			142 => 'MySQL 5.0.x or ealier',
			143 => 'MySQL 5.4.x',
			144 => 'PHP 5.5',
			145 => 'PHP 5.6',
			146 => 'Multilanguage',
			147 => 'Google Chrome 4.x',
			148 => 'Languages and Strings',
			149 => 'Installation and Updating',
			150 => 'RTL &amp; Accessibilty',
			151 => 'Safari iPod',
			152 => 'Konqueror',
		);
	}

	public static function getTagOptions()
	{
		$array = self::getTagRaw();

		$options = array();

		$options[] = JHtml::_('select.option', -1, JText::_('None'));

		foreach ($array as $k => $v)
		{
			$options[] = JHtml::_('select.option', $k, $v);
		}

		return $options;
	}

	/**
	 * Returns the comparison operator options
	 *
	 * @return  array
	 */
	public static function getComparatorOptions()
	{
		return array(
			JHtml::_('select.option', '1', JText::_('Is')),
			JHtml::_('select.option', '0', JText::_('Is Not')),
		);
	}

	public static function getDateOptions()
	{
		return array(
			JHtml::_('select.option', 'none', JText::_('None')),
			JHtml::_('select.option', 'created', JText::_('Created')),
			JHtml::_('select.option', 'modified', JText::_('Last Modified')),
			JHtml::_('select.option', 'closed', JText::_('Closed')),
		);
	}

	/**
	 * Convert an array of options to a JHtmlSelect-compatible options array
	 *
	 * @param   string  $defaultOptionKey  The translation key for the default selection option
	 *
	 * @return  array
	 */
	protected static function arrayToOptions($array, $defaultOptionKey = null)
	{
		$options = array();

		if (empty($defaultOptionKey))
		{
			$defaultOptionKey = 'JGLOBAL_SELECT_AN_OPTION';
		}

		$options[] = JHtml::_('select.option', 0, JText::_($defaultOptionKey));

		foreach ($array as $k => $v)
		{
			$options[] = JHtml::_('select.option', $k, $v);
		}

		return $options;
	}

}
