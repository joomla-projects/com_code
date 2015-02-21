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

<h1>
	<?php echo $this->item->title; ?>
</h1>

<?php echo nl2br($this->item->description); ?>

<?php if (!empty($this->tags)) : ?>
<span>Filed Under:</span>
<ul>
<?php foreach ($this->tags as $tag) : ?>
	<li><?php echo $tag->tag; ?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>

<?php if (!empty($this->comments)) : ?>
<span>Responses:</span>
<?php foreach ($this->comments as $comment) : ?>
	<div class="well">
		Posted on <?php echo JHtml::_('date', $comment->created_date, 'j M Y, G:s'); ?> by <?php echo $comment->first_name . ' ' . $comment->last_name; ?>:<br />
		<?php echo nl2br($comment->body); ?>
	</div>
<?php endforeach; ?>
<?php endif; ?>
