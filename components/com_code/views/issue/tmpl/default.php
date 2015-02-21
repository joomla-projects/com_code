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

<pre class="description" style="white-space: pre-line;">
	<?php echo $this->item->description; ?>
</pre>
<div class="clr"></div>

<?php if (!empty($this->tags)) : ?>
<span>Filed Under:</span>
<ul>
<?php foreach ($this->tags as $tag) : ?>
	<li><?php echo $tag->tag; ?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>

<div class="clr"></div>
