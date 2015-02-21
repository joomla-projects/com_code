<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_code
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<div id="trackers">
	<h1>
		Issue Trackers
	</h1>

	<?php foreach ($this->items as $tracker) : ?>
		<div class="trackers branch-<?php echo $tracker->tracker_id; ?>">
			<h3>
				<a href="<?php echo JRoute::_('index.php?option=com_code&view=tracker&tracker_id=' . $tracker->tracker_id); ?>" title="View <?php echo $tracker->title; ?>.">
					<?php echo $tracker->title; ?></a>
			</h3>
		</div>
	<?php endforeach; ?>
</div>
