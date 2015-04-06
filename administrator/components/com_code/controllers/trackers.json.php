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
 * Trackers Controller for Joomla Code
 */
class CodeControllerTrackers extends JControllerLegacy
{
	public function save()
	{
		$model = $this->getModel('Trackers');
		var_dump($this->input);die;

		$data = array(
			'jc_tracker_id' => $tracker->tracker_id,
			'title'         => $tracker->tracker_name,
			'description'   => $tracker->description
		);

		try
		{
			$result = $model->save();
		}
		catch (Exception $e)
		{
			$result = $e;
		}

		echo new JResponseJson($result, null, false, $this->input->get('ignoreMessages', true, 'bool'));
	}
}
