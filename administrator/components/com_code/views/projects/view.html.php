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
 * View class for a list of projects.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_code
 * @since		1.6
 */
class CodeViewProjects extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * The necessary HTML to display the sidebar
	 *
	 * @var  string
	 */
	protected $sidebar;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise a Error object.
	 */
	public function display($tpl = null)
	{
		$this->items      = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->state      = $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		CodeHelper::addSubmenu('projects');

		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal')
		{
			$this->addToolbar();
			$this->sidebar = JHtmlSidebar::render();
		}

		return parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 */
	protected function addToolbar()
	{
		$canDo = CodeHelper::getActions('com_code');

		JToolBarHelper::title(JText::_('Joomla! Code Component - Projects'), 'code');

		JToolBarHelper::addNew('project.add', 'JTOOLBAR_NEW');

		JToolBarHelper::editList('project.edit', 'JTOOLBAR_EDIT');

		JToolBarHelper::divider();
		JToolBarHelper::custom('projects.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
		JToolBarHelper::custom('projects.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
		JToolBarHelper::divider();
		JToolBarHelper::archiveList('projects.archive', 'JTOOLBAR_ARCHIVE');
		JToolBarHelper::custom('projects.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);

		if ($this->state->get('filter.published') == -2)
		{
			JToolBarHelper::deleteList('', 'projects.delete', 'JTOOLBAR_EMPTY_TRASH');
			JToolBarHelper::divider();
		}
		else
		{
			JToolBarHelper::trash('projects.trash', 'JTOOLBAR_TRASH');
			JToolBarHelper::divider();
		}

		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_code');
			JToolBarHelper::divider();
		}
	}
}
