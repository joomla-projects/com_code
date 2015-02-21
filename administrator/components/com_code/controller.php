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
 * Code component base controller.
 */
class CodeController extends JControllerLegacy
{
	/**
	 * The default view.
	 *
	 * @var  string
	 */
	protected $default_view = 'about';

	/**
	 * Typical view method for MVC based architecture
	 *
	 * This function is provide as a default implementation, in most cases
	 * you will need to override it in your own controllers.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   array    $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  CodeController  This object to support chaining.
	 */
	public function display($cachable = false, $urlparams = array())
	{
		require_once JPATH_COMPONENT . '/helpers/code.php';

		$view   = $this->input->getWord('view', $this->default_view);
		$layout = $this->input->getWord('layout', 'default');
		$id     = $this->input->getInt('id');

		// Check for edit form.
		if ($view == 'project' && $layout == 'edit' && !$this->checkEditId('com_code.edit.project', $id))
		{
			// Somehow the person just went to the form - we don't allow that.
			$this->setMessage(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_code&view=projects', false));

			return false;
		}

		return parent::display($cachable, $urlparams);
	}
}
