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
 * Routing class from com_code
 *
 * @since  4.0
 */
class CodeRouter extends JComponentRouterBase
{
	/**
	 * Build the route for the com_code component
	 *
	 * @param   array  &$query  An array of URL arguments
	 *
	 * @return  array  The URL arguments to use to assemble the subsequent URL.
	 *
	 * @since   4.0
	 */
	public function build(&$query)
	{
		// Initialize variables.
		$segments = array();

		// We need a menu item.  Either the one specified in the query, or the current active one if none specified
		if (empty($query['Itemid']))
		{
			$menuItem = $this->menu->getActive();
			$menuItemGiven = false;
		}
		else
		{
			$menuItem = $this->menu->getItem($query['Itemid']);
			$menuItemGiven = true;
		}

		// Check again
		if ($menuItemGiven && isset($menuItem) && $menuItem->component != 'com_code')
		{
			$menuItemGiven = false;
			unset($query['Itemid']);
		}

		if (!isset($query['view']))
		{
			// We need to have a view in the query or it is an invalid URL
			return $segments;
		}

		$view = $query['view'];
		unset($query['view']);

		// Only one project for now.
		$segments[] = 'cms';

		switch ($view)
		{
			case 'issue':
				$segments[] = 'trackers';
				$segments[] = @$query['tracker_alias'];
				$segments[] = @$query['issue_id'];
				unset($query['tracker_alias']);
				unset($query['tracker_id']);
				unset($query['issue_id']);

				break;

			case 'tracker':
				$segments[] = 'trackers';
				$segments[] = @$query['tracker_alias'];
				unset($query['tracker_alias']);
				unset($query['tracker_id']);

				break;

			case 'trackers':
			default:
				$segments[] = 'trackers';

				break;
		}

		return $segments;
	}

	/**
	 * Parse the segments of a URL.
	 *
	 * @param   array &$segments The segments of the URL to parse.
	 *
	 * @return  array  The URL attributes to be used by the application.
	 *
	 * @since   4.0
	 */
	public function parse(&$segments)
	{
		// Initialize variables.
		$vars = array();

		// If no segments exist then there is no defined project and we do not support that at this time.
		if (empty($segments))
		{
			JError::raiseError(404, 'Resource not found.');
		}

		// Get the project from the first segment.
		$projectAlias = array_shift($segments);

		// The only supported project for now is the Joomla! CMS.
		if ($projectAlias != 'cms')
		{
			JError::raiseError(404, 'Resource not found.');
		}

		// Get the view/task definition from the next segment.
		switch (array_shift($segments))
		{
			// View trackers and issues.
			case 'trackers':
				// If there is no given tracker name we default to viewing all trackers and return.
				if (empty($segments))
				{
					$vars['view'] = 'trackers';

					return $vars;
				}

				// Get the tracker alias from the next segment.
				$trackerAlias = str_replace(':', '-', array_shift($segments));

				// Search the database for the appropriate tracker.
				$db = JFactory::getDbo();
				$db->setQuery(
					$db->getQuery(true)
						->select('tracker_id')
						->from('#__code_trackers')
						->where('alias = ' . $db->quote($trackerAlias))
				, 0, 1);
				$trackerId = (int) $db->loadResult();

				// If the tracker isn't found throw a 404.
				if (!$trackerId)
				{
					JError::raiseError(404, 'Resource not found.');
				}

				// We found a valid tracker with that alias so set the id.
				$vars['tracker_id'] = $trackerId;

				// If we have an issue id in the next segment lets set that in the request.
				if (!empty($segments) && is_numeric($segments[0]))
				{
					$vars['view'] = 'issue';
					$vars['issue_id'] = (int) array_shift($segments);
				}
				// No issue id so we are looking at the tracker itself.
				else
				{
					$vars['view'] = 'tracker';
				}

				break;
		}

		return $vars;
	}
}
