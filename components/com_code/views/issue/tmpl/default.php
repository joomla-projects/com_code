<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_code
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Load the CSS stylesheets
JHtml::_('stylesheet', 'com_patchtester/default.css', array(), true);
?>

<h1>
	<?php echo $this->item->title; ?>
</h1>

<div id="issue-content">
	<h4>Summary</h4>
	<div class="issue-description">
		<?php echo nl2br($this->item->description); ?>
	</div>

	<?php if (!empty($this->tags)) : ?>
		<div class="issue-tags">
			<h4>Filed Under</h4>
				<ul>
					<?php foreach ($this->tags as $tag) : ?>
						<li><?php echo $tag->tag; ?></li>
					<?php endforeach; ?>
				</ul>
		</div>
	<?php endif; ?>

	<?php if (!empty($this->comments)) : ?>
		<div class="issue-comments">
			<h4>Responses</h4>
				<?php foreach ($this->comments as $comment) : ?>
					<div class="issue-comment well">
						<span class="comment-owner">
							Posted on <?php echo JHtml::_('date', $comment->created_date, 'j M Y, G:s'); ?> by <?php echo $comment->first_name . ' ' . $comment->last_name; ?>
						</span>
						<div class="issue-comment-details">
							<?php echo nl2br($comment->body); ?>
						</div>
					</div>
				<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
