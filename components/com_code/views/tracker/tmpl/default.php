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

<div id="tracker">
	<h1>
		<?php echo $this->item->title; ?>
	</h1>

	<div class="category-desc">
		<?php echo JHtml::_('content.prepare', $this->item->description, '', 'com_code.tracker'); ?>
		<div class="clr"></div>
	</div>

	<form action="<?php echo $this->formURL ?>" method="post" name="trackerForm">

	<?php echo $this->loadTemplate('filters'); ?>

	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>
					<?php echo JText::_('ID'); ?>
				</th>
				<th width="50%" class="list-title">
					<?php echo JText::_('Title'); ?>
				</th>
				<th>
					<?php echo JText::_('Priority'); ?>
				</th>
				<th>
					<?php echo JText::_('Created'); ?>
				</th>
				<th>
					<?php echo JText::_('Modified'); ?>
				</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($this->items as $i => $issue) : ?>
			<tr class="<?php echo 'row', ($i % 2); ?>" title="<?php echo $this->escape($issue->title); ?>">
				<td>
					<a href="<?php echo JRoute::_('index.php?option=com_code&view=issue&tracker_alias=' . $this->item->alias . '&issue_id=' . $issue->issue_id); ?>"
					   title="View issue <?php echo $issue->issue_id; ?> report.">
						<?php echo $issue->issue_id; ?>
					</a>
				</td>
				<td width="50%">
					<a href="<?php echo JRoute::_('index.php?option=com_code&view=issue&tracker_alias=' . $this->item->alias . '&issue_id=' . $issue->issue_id); ?>"
					   title="View issue <?php echo $issue->issue_id; ?> report.">
						<?php echo $issue->title; ?>
					</a>
				</td>
				<td>
					<span class="priority-<?php echo (int) $issue->priority ?>">
					<?php echo $this->priorities[$issue->priority]; ?>
					</span>
				</td>
				<td>
					<?php echo JHtml::_('date', $issue->created_date, 'j M Y, G:s'); ?>
					<br />
					by <?php echo $issue->created_user_name; ?>
				</td>
				<td>
					<?php echo JHtml::_('date', $issue->modified_date, 'j M Y, G:s'); ?>
					<br />
					by <?php echo $issue->modified_user_name; ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

	</form>

	<?php if (!empty($this->items)) : ?>
		<?php if (($this->params->def('show_pagination', 2) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->page->pagesTotal > 1)) : ?>
		<div class="pagination">
			<?php if ($this->params->def('show_pagination_results', 1)) : ?>
				<p class="counter pull-right">
					<?php echo $this->page->getPagesCounter(); ?>
				</p>
			<?php endif; ?>

			<?php echo $this->page->getPagesLinks(); ?>
		</div>
		<?php endif; ?>
	<?php endif; ?>
</div>
