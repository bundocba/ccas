<?php
/**
* @version 1.0.0
* @package RSEvents!Pro 1.0.0
* @copyright (C) 2011 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined( '_JEXEC' ) or die( 'Restricted access' );
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'group.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
			Joomla.submitform(task, document.getElementById('adminForm'));
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
	
	function JHide() {
		if ($$('input[id^=jform_can_post_events]:checked').get('value') == 1) {
			<?php if (rseventsproHelper::isJ3()) { ?>
			$$('fieldset[id=jform_can_repeat_events]').getParent().getParent().setStyle('display','');
			$$('fieldset[id=jform_event_moderation]').getParent().getParent().setStyle('display','');
			<?php } else { ?>
			$$('fieldset[id=jform_can_repeat_events]').getParent().setStyle('display','');
			$$('fieldset[id=jform_event_moderation]').getParent().setStyle('display','');
			<?php } ?>
		} else {
			<?php if (rseventsproHelper::isJ3()) { ?>
			$$('fieldset[id=jform_can_repeat_events]').getParent().getParent().setStyle('display','none');
			$$('fieldset[id=jform_event_moderation]').getParent().getParent().setStyle('display','none');
			<?php } else { ?>
			$$('fieldset[id=jform_can_repeat_events]').getParent().setStyle('display','none');
			$$('fieldset[id=jform_event_moderation]').getParent().setStyle('display','none');
			<?php } ?>
		}
	}
	
	window.addEvent('domready', function() {
		JHide();
	<?php if (!rseventsproHelper::isJ3()) { ?>
		$$('.rschosen').chosen({
			disable_search_threshold : 10
		});
	<?php } ?>
	<?php if ($this->used) { ?>
		var used = new String('<?php echo implode(',',$this->used); ?>');
		var array = used.split(','); 
		
		for (var i=0; i < $('jformjgroups').options.length; i++) {
			var o = $('jformjgroups').options[i];
			if (array.contains(o.value)) 
				o.disabled = true;
		}
		<?php echo rseventsproHelper::isJ3() ? 'jQuery(\'#jformjgroups\').trigger("liszt:updated");' : '$(\'jformjgroups\').fireEvent("liszt:updated");'; ?>
	<?php } ?>
	});
</script>

<?php if (!rseventsproHelper::isJ3()) { ?>
<style type="text/css">
.rsfieldsetfix {overflow: visible !important;}
</style>
<?php } ?>

<form action="<?php echo JRoute::_('index.php?option=com_rseventspro&view=group&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" autocomplete="off" class="form-validate form-horizontal">
	<div class="row-fluid">
		<div class="span12">
			<?php $extra = '<span class="rsextra"><a class="modal" rel="{handler: \'iframe\'}" href="'.JRoute::_('index.php?option=com_users&view=users&layout=modal&tmpl=component&field=jform_jusers'.(!empty($this->excludes) ? ('&excluded=' . base64_encode(json_encode($this->excludes))) : '')).'">'.JText::_('COM_RSEVENTSPRO_GROUP_ADD_USERS').'</a>'; ?>
			<?php $extra .= ' / <a href="javascript:void(0);" onclick="removeusers();">'.JText::_('COM_RSEVENTSPRO_GROUP_REMOVE_USERS').'</a></span>'; ?>
			
			<?php echo JHtml::_('rsfieldset.start', 'adminform rsfieldsetfix'); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('name'), $this->form->getInput('name')); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('jgroups'), $this->form->getInput('jgroups')); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('jusers'), $this->form->getInput('jusers').$extra); ?>
			<?php echo JHtml::_('rsfieldset.end'); ?>
		</div>
		<div class="span6 rswidth-50 rsfltlft">
			<?php echo JHtml::_('rsfieldset.start', 'adminform', JText::_('COM_RSEVENTSPRO_GROUP_EVENT_PERMISSIONS')); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('can_edit_events'), $this->form->getInput('can_edit_events')); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('can_post_events'), $this->form->getInput('can_post_events')); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('can_repeat_events'), $this->form->getInput('can_repeat_events')); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('event_moderation'), $this->form->getInput('event_moderation')); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('can_delete_events'), $this->form->getInput('can_delete_events')); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('can_register'), $this->form->getInput('can_register')); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('can_unsubscribe'), $this->form->getInput('can_unsubscribe')); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('can_download'), $this->form->getInput('can_download')); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('can_upload'), $this->form->getInput('can_upload')); ?>
			<?php echo JHtml::_('rsfieldset.end'); ?>
		</div>
		<div class="span6 rswidth-50 rsfltlft">
			<?php echo JHtml::_('rsfieldset.start', 'adminform', JText::_('COM_RSEVENTSPRO_GROUP_CATEGORY_PERMISSIONS')); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('can_create_categories'), $this->form->getInput('can_create_categories')); ?>
			<?php echo JHtml::_('rsfieldset.end'); ?>
			
			<?php echo JHtml::_('rsfieldset.start', 'adminform', JText::_('COM_RSEVENTSPRO_GROUP_TAG_PERMISSIONS')); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('tag_moderation'), $this->form->getInput('tag_moderation')); ?>
			<?php echo JHtml::_('rsfieldset.end'); ?>
			
			<?php echo JHtml::_('rsfieldset.start', 'adminform', JText::_('COM_RSEVENTSPRO_GROUP_LOCATION_PERMISSIONS')); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('can_add_locations'), $this->form->getInput('can_add_locations')); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('can_edit_locations'), $this->form->getInput('can_edit_locations')); ?>
			<?php echo JHtml::_('rsfieldset.end'); ?>
			
			<?php echo JHtml::_('rsfieldset.start', 'adminform', JText::_('COM_RSEVENTSPRO_GROUP_APPROVAL_PERMISSIONS')); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('can_approve_events'), $this->form->getInput('can_approve_events')); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('can_approve_tags'), $this->form->getInput('can_approve_tags')); ?>
			<?php echo JHtml::_('rsfieldset.end'); ?>
		</div>
		
	</div>

	<?php echo JHTML::_('form.token'); ?>
	<input type="hidden" name="task" value="" />
	<?php echo $this->form->getInput('id'); ?>
	<?php echo JHTML::_('behavior.keepalive'); ?>
</form>