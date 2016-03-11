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
 * Code Component Route Helper.
 */
abstract class CodeHelperRoute
{
	/**
	 * Cached menu item lookup array
	 *
	 * @var  array
	 */
	private static $lookup = [];

	/**
	 * Get the issue route.
	 *
	 * @param   integer $id The ID of the issue.
	 *
	 * @return  string  The issue route.
	 */
	public static function getIssueRoute($id)
	{
		$needles = [
			'issue_id' => [(int) $id]
		];

		// Create the link
		$link = 'index.php?option=com_code&view=issue&issue_id=' . $id;

		if ($item = self::findItem($needles))
		{
			$link .= '&Itemid=' . $item;
		}

		return $link;
	}

	/**
	 * Get the tracker route.
	 *
	 * @param   integer $id The ID of the tracker.
	 *
	 * @return  string  The tracker route.
	 */
	public static function getTrackerRoute($id)
	{
		$needles = [
			'tracker_id' => [(int) $id]
		];

		// Create the link
		$link = 'index.php?option=com_code&view=tracker&tracker_id=' . $id;

		if ($item = self::_findItem($needles))
		{
			$link .= '&Itemid=' . $item;
		}

		return $link;
	}

	/**
	 * Find a menu item ID.
	 *
	 * @param   array $needles An array of lookup needles.
	 *
	 * @return  mixed  The ID found or null otherwise.
	 */
	private static function findItem(array $needles = [])
	{
		$menus    = JFactory::getApplication()->getMenu('site');
		$language = isset($needles['language']) ? $needles['language'] : '*';

		// Prepare the reverse lookup array.
		if (!isset(self::$lookup[$language]))
		{
			self::$lookup[$language] = [];

			$component = JComponentHelper::getComponent('com_content');

			$attributes = ['component_id'];
			$values     = [$component->id];

			if ($language != '*')
			{
				$attributes[] = 'language';
				$values[]     = [$needles['language'], '*'];
			}

			$items = $menus->getItems($attributes, $values);

			foreach ($items as $item)
			{
				if (isset($item->query) && isset($item->query['view']))
				{
					$view = $item->query['view'];

					if (!isset(self::$lookup[$language][$view]))
					{
						self::$lookup[$language][$view] = [];
					}

					if (isset($item->query['id']))
					{
						/**
						 * Here it will become a bit tricky
						 * language != * can override existing entries
						 * language == * cannot override existing entries
						 */
						if (!isset(self::$lookup[$language][$view][$item->query['id']]) || $item->language != '*')
						{
							self::$lookup[$language][$view][$item->query['id']] = $item->id;
						}
					}
				}
			}
		}

		if ($needles)
		{
			foreach ($needles as $view => $ids)
			{
				if (isset(self::$lookup[$language][$view]))
				{
					foreach ($ids as $id)
					{
						if (isset(self::$lookup[$language][$view][(int) $id]))
						{
							return self::$lookup[$language][$view][(int) $id];
						}
					}
				}
			}
		}

		// Check if the active menuitem matches the requested language
		$active = $menus->getActive();

		if ($active
			&& $active->component == 'com_code'
			&& ($language == '*' || in_array($active->language, ['*', $language]) || !JLanguageMultilang::isEnabled())
		)
		{
			return $active->id;
		}

		// If not found, return language specific home link
		$default = $menus->getDefault($language);

		return !empty($default->id) ? $default->id : null;
	}
}
