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
 * The HTML Joomla Code tracker view.
 */
class CodeViewTracker extends JViewLegacy
{
	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise a Error object.
	 */
	public function display($tpl = null)
	{
		// Load the necessary helper class
		$this->loadHelper('Select');

		// Enable Chosen
		JHtml::_('formbehavior.chosen');

		// Populate basic variables
		$this->state  = $this->get('State');
		$this->item   = $this->get('Item');
		$this->items  = $this->get('Items');
		$this->page   = $this->get('Pagination');
		$this->user   = JFactory::getUser();
		$this->params = JFactory::getApplication()->getParams('com_code');

		// Priorities map, from integer to string
		$this->priorities = CodeHelperSelect::getPrioritiesRaw();

		// URL to submit the form to
		$id = JFactory::getApplication()->input->getInt('Itemid', 0);
		$itemid = $id ? '&Itemid=' . (int) $id : '';

		$this->formURL = JRoute::_(
			'index.php?option=com_code&view=tracker&tracker_id=' . $this->item->jc_tracker_id . $itemid
		);

		// Ordering
		$this->order = $this->getModel()->getState('list.ordering', 'issue_id');
		$this->order_Dir = $this->getModel()->getState('list.direction', 'desc');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));

			return false;
		}

		$app     = JFactory::getApplication();
		$pathway = $app->getPathway();
		$pathway->addItem($this->item->title, JRoute::_('index.php?option=com_code&view=tracker&tracker_id=' . $this->item->tracker_id));

		$this->page->setAdditionalUrlParam('tracker_alias', $this->item->alias);

		return parent::display($tpl);
	}
}
