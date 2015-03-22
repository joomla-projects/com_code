<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_code
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$app = JFactory::getApplication();
$id = $app->input->getInt('Itemid', 0);
$itemId = $id ? '&Itemid=' . (int) $id : '';

$model = $this->getModel();
?>

<form
	action="<?php echo JRoute::_('index.php?option=com_code&view=tracker&tracker_id=' . $this->item->tracker_id . '&tracker_alias=' . $this->item->alias . $itemId) ?>"
	method="post" name="trackerForm">

	<div class="clearfix">
		<div class="pull-left">
			<label for="filter_search" class="element-invisible">
				<?php echo JText::_('JSEARCH_FILTER'); ?>
			</label>
			<div class="btn-wrapper input-append">
				<input type="text" class="search" name="search" id="filter_search" value="<?php echo $this->escape($model->getState('filter.search')) ?>"/>
				<button type="submit" class="btn hasTooltip"
						title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>">
					<i class="icon-search"></i>
				</button>
				<button type="button" class="btn hasTooltip"
						title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>"
						onclick="jQuery('#filter_search').val(''); document.forms.trackerForm.submit();"
					>
					<span class="icon icon-delete"></span>
				</button>
			</div>
		</div>
		<div class="hidden-phone hidden-tablet pull-right">
			<label for="limit" class="element-invisible">
				<?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC') ?>
			</label>
			<?php echo $this->page->getLimitBox(); ?>
		</div>
	</div>

</form>