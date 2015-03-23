<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_code
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/** @var CodeModelTracker $model */
$model = $this->getModel();
?>

<div class="clearfix">
	<div class="pull-left form-search">
		<label for="filter_search" class="element-invisible">
			<?php echo JText::_('JSEARCH_FILTER'); ?>
		</label>
		<div class="btn-wrapper input-append">
			<input type="text" class="search-query" name="search" id="filter_search" value="<?php echo $this->escape($model->getState('filter.search')) ?>"/>
			<button type="submit" class="btn hasTooltip"
					title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>">
				<i class="icon-search"></i>
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
<div class="clearfix">
	<div class="form-horizontal">
		<div class="control-group">
			<label class="control-label" for="filter_status_id">
				<?php echo JText::_('Status') ?>
			</label>
			<div class="controls">
				<?php echo JHtml::_('select.genericlist', CodeHelperSelect::getComparatorOptions(), 'filter_status_id_include', array(
					'onchange' => 'document.forms.trackerForm.submit();',
					'class' => 'input-small'
				), 'value', 'text', $model->getState('issue.status_id_include')) ?>
				<?php echo JHtml::_('select.genericlist', CodeHelperSelect::getStatusOptions(), 'filter_status_id', array(
					'onchange' => 'document.forms.trackerForm.submit();'
				), 'value', 'text', $model->getState('issue.status_id')) ?>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="filter_tag_id">
				<?php echo JText::_('Tag') ?>
			</label>
			<div class="controls">
				<?php echo JHtml::_('select.genericlist', CodeHelperSelect::getComparatorOptions(), 'filter_tag_id_include', array(
					'onchange' => 'document.forms.trackerForm.submit();',
					'class' => 'input-small'
				), 'value', 'text', $model->getState('issue.tag_id_include')) ?>
				<?php echo JHtml::_('select.genericlist', CodeHelperSelect::getTagOptions(), 'filter_tag_id[]', array(
					'multiple' => 'multiple',
					'class' => 'advancedSelect'
				), 'value', 'text', $model->getState('issue.tag_id')) ?>
				<button type="submit" class="btn hasTooltip"
						title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>">
					<i class="icon-search"></i>
				</button>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="filter_submitter_name">
				<?php echo JText::_('Submitter') ?>
			</label>
			<div class="controls">
				<?php echo JHtml::_('select.genericlist', CodeHelperSelect::getComparatorOptions(), 'filter_submitter_id_include', array(
					'onchange' => 'document.forms.trackerForm.submit();',
					'class' => 'input-small'
				), 'value', 'text', $model->getState('issue.submitter_id_include')) ?>
				<input type="text" name="filter_submitter_name" id="filter_submitter_name" value="<?php echo $this->escape($model->getState('issue.submitter_name')) ?>"/>
				<button type="submit" class="btn hasTooltip"
						title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>">
					<i class="icon-search"></i>
				</button>
			</div>
		</div>
	</div>
</div>
