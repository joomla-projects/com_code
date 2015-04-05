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
 * The HTML Joomla Code issue view.
 */
class CodeViewIssue extends JViewLegacy
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
		/** @var CodeModelIssue $model */
		$model = $this->getModel('Issue');

		$this->state    = $model->getState();
		$this->item     = $model->getItem();
		$this->tags     = $model->getTags();
		$this->commits  = $model->getCommits();
		$this->comments = $model->getComments();
		$this->user     = JFactory::getUser();
		$this->params   = JFactory::getApplication()->getParams('com_code');

		// Check for errors.
		if (count($errors = $model->getErrors()))
		{
			JError::raiseError(500, implode("\n", $errors));

			return false;
		}

		return parent::display($tpl);
	}
}
