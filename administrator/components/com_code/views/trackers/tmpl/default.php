<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_code
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
?>

<script type="text/javascript" src="<?php echo JUri::root() . '/media/editors/tinymce/'; ?>tinymce.min.js"></script>
<script type="text/javascript">
tinymce.init({
    selector: "h3.editable",
    inline: true,
    toolbar: "undo redo",
    menubar: false
});

tinymce.init({
    selector: "div.editable",
    inline: true,
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
});
</script>

<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10">
	<?php if (empty($this->trackers)) : ?>
		<div class="alert alert-no-items">
			<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
		</div>
	<?php else : ?>
		<form class="adminForm">
			<div class="trackers">
				<?php foreach ($this->trackers as $tracker) : ?>
					<div class="tracker" id="tracker<?php echo $tracker->tracker_id; ?>">
						<h3 class="editable"><?php echo $tracker->title; ?></h3>
						<div class="tracker-description editable">
							<?php echo $tracker->description; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</form>
	<?php endif;?>
</div>
