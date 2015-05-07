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
JHtml::_('behavior.modal');
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'subscription.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
			Joomla.submitform(task, document.getElementById('adminForm'));
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
	
	function jSelectUser_jform_idu(id, title) {
		var old_id = document.getElementById("jform_idu_id").value;
		if (old_id != id) {
			var req = new Request({
				method: 'post',
				url: 'index.php?option=com_rseventspro',
				data: 'task=subscription.email&id=' + id,
				onSuccess: function(responseText, responseXML) {
					var response = responseText;
					var start = response.indexOf('RS_DELIMITER0') + 13;
					var end = response.indexOf('RS_DELIMITER1');
					response = response.substring(start, end);
					
					document.getElementById("jform_idu_id").value = id;
					document.getElementById("jform_idu_name").value = title;
					document.getElementById("jform_name").value = title;
					document.getElementById("jform_email").value = response;
				}
			});
			req.send();				
		}
		SqueezeBox.close();
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_rseventspro&view=subscription&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" autocomplete="off" class="form-validate form-horizontal">
	<div class="row-fluid">
		<div class="span6 rswidth-50 rsfltlft">
			<?php echo JHtml::_('rsfieldset.start', 'adminform', JText::_('COM_RSEVENTSPRO_SUBSCRIBER_INFO')); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('idu'), $this->form->getInput('idu')); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('name'), $this->form->getInput('name')); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('email'), $this->form->getInput('email')); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('state'), $this->form->getInput('state')); ?>
			<?php if ($this->item->state == 1) { ?>
			<?php $extra = '<a href="'.JRoute::_('index.php?option=com_rseventspro&task=subscription.activation&id='.$this->item->id).'" class="rsextra">'.JText::_('COM_RSEVENTSPRO_SEND_ACTIVATION_EMAIL').'</a>'; ?>
			<?php echo JHtml::_('rsfieldset.element', '<label>&nbsp;</label>', $extra); ?>
			<?php } ?>
			<?php if (empty($this->item->id)) { ?>
			<?php echo JHtml::_('rsfieldset.element', '<label for="registration">'.JText::_('COM_RSEVENTSPRO_SEND_REGISTRATION_EMAIL').'</label>', '<input type="checkbox" name="registration" value="1" id="registration" />'); ?>
			<?php } ?>
			<?php echo JHtml::_('rsfieldset.end'); ?>
			
			
			<?php echo JHtml::_('rsfieldset.start', 'adminform', JText::_('COM_RSEVENTSPRO_SUBSCRIBER_DETAILS')); ?>
			<?php if ($this->item->id) { ?>
			<?php $total = 0; ?>
			
			<?php echo JHtml::_('rsfieldset.element', '<label title="'.JText::_('COM_RSEVENTSPRO_SUBSCRIBER_DATE_DESC').'" class="hasTip">'.JText::_('COM_RSEVENTSPRO_SUBSCRIBER_DATE').'</label>', '<span class="rsextra">'.rseventsproHelper::date($this->item->date,null,true).'</span>'); ?>
			
			<?php echo JHtml::_('rsfieldset.element', '<label>'.JText::_('COM_RSEVENTSPRO_SUBSCRIBER_IP').'</label>', '<span class="rsextra">'.$this->item->ip.'</span>'); ?>
			
			<?php $event = $this->getEvent($this->item->ide); ?>
			<?php $date = $event->allday ? rseventsproHelper::date($event->start, rseventsproHelper::getConfig('global_date')) : rseventsproHelper::date($event->start).' - '.rseventsproHelper::date($event->end); ?>
			<?php echo JHtml::_('rsfieldset.element', '<label title="'.JText::_('COM_RSEVENTSPRO_SUBSCRIBER_EVENT_DESC').'" class="hasTip">'.JText::_('COM_RSEVENTSPRO_SUBSCRIBER_EVENT').'</label>', '<span class="rsextra"><a href="'.JRoute::_('index.php?option=com_rseventspro&task=event.edit&id='.$event->id).'">'.$event->name.'</a> ('.$date.')</span>'); ?>
			
			<?php echo JHtml::_('rsfieldset.element', '<label title="'.JText::_('COM_RSEVENTSPRO_SUBSCRIBER_PAYMENT_DESC').'" class="hasTip">'.JText::_('COM_RSEVENTSPRO_SUBSCRIBER_PAYMENT').'</label>', '<span class="rsextra">'.rseventsproHelper::getPayment($this->item->gateway).'</span>'); ?>
			
			<?php $tickets = rseventsproHelper::getUserTickets($this->item->id); ?>
			<?php $purchasedtickets = ''; ?>
			<?php if ($tickets) {
					foreach ($tickets as $ticket) {
						if ($ticket->price > 0) {
							$purchasedtickets .= $ticket->quantity.' x '.$ticket->name.' ('.rseventsproHelper::currency($ticket->price).')<br />';
							$total += (int) $ticket->quantity * $ticket->price;
						} else {
							$purchasedtickets .= $ticket->quantity.' x '.$ticket->name.' ('.JText::_('COM_RSEVENTSPRO_GLOBAL_FREE').')<br />';
						}
					}
				}
			?>
			<?php echo JHtml::_('rsfieldset.element', '<label title="'.JText::_('COM_RSEVENTSPRO_SUBSCRIBER_TICKETS_DESC').'" class="hasTip">'.JText::_('COM_RSEVENTSPRO_SUBSCRIBER_TICKETS').'</label>', '<span class="rsextra">'.$purchasedtickets.'</span>'); ?>
			
			<?php if ($this->item->discount) { ?>
			<?php echo JHtml::_('rsfieldset.element', '<label>'.JText::_('COM_RSEVENTSPRO_SUBSCRIBER_DISCOUNT').'</label>', '<span class="rsextra">'.rseventsproHelper::currency($this->item->discount).'</span>'); ?>
			<?php echo JHtml::_('rsfieldset.element', '<label>'.JText::_('COM_RSEVENTSPRO_SUBSCRIBER_DISCOUNT_CODE').'</label>', '<span class="rsextra">'.$this->item->coupon.'</span>'); ?>
			<?php $total = $total - $this->item->discount; ?>
			<?php } ?>
			
			<?php if ($this->item->early_fee) { ?>
			<?php echo JHtml::_('rsfieldset.element', '<label>'.JText::_('COM_RSEVENTSPRO_SUBSCRIBER_EARLY_FEE').'</label>', '<span class="rsextra">'.rseventsproHelper::currency($this->item->early_fee).'</span>'); ?>
			<?php $total = $total - $this->item->early_fee; ?>
			<?php } ?>
			
			<?php if ($this->item->late_fee) { ?>
			<?php echo JHtml::_('rsfieldset.element', '<label>'.JText::_('COM_RSEVENTSPRO_SUBSCRIBER_LATE_FEE').'</label>', '<span class="rsextra">'.rseventsproHelper::currency($this->item->late_fee).'</span>'); ?>
			<?php $total = $total + $this->item->late_fee; ?>
			<?php } ?>
			
			<?php if ($this->item->tax) { ?>
			<?php echo JHtml::_('rsfieldset.element', '<label>'.JText::_('COM_RSEVENTSPRO_SUBSCRIBER_TAX').'</label>', '<span class="rsextra">'.rseventsproHelper::currency($this->item->tax).'</span>'); ?>
			<?php $total = $total + $this->item->tax; ?>
			<?php } ?>
			
			<?php echo JHtml::_('rsfieldset.element', '<label>'.JText::_('COM_RSEVENTSPRO_SUBSCRIBER_TOTAL').'</label>', '<span class="rsextra">'.rseventsproHelper::currency($total).'</span>'); ?>
			
			<?php if (rseventsproHelper::pdf() && $this->item->state == 1 && $event->ticket_pdf == 1 && !empty($event->ticket_pdf_layout)) { ?>
			<?php echo JHtml::_('rsfieldset.element', '<label>&nbsp;</label>', '<a class="rsextra" href="'.JRoute::_('index.php?option=com_rseventspro&view=pdf&id='.$this->item->id).'">'.JText::_('COM_RSEVENTSPRO_SUBSCRIBER_TICKET_PDF').'</a>'); ?>
			<?php } ?>
			
			<?php } else { ?>
			<?php JText::script('COM_RSEVENTSPRO_SUBSCRIBER_PLEASE_SELECT_TICKET'); ?>
			<?php JText::script('COM_RSEVENTSPRO_SUBSCRIBER_PLEASE_SELECT_TICKET_FROM_EVENT'); ?>
			
			<?php $selecttickets = '<input type="text" name="" value="1" id="quantity" size="3" onkeyup="this.value=this.value.replace(/[^0-9]/g, \'\');" class="input-mini" />'; ?>
			<?php $selecttickets .= ' <select name="ticket" id="ticket">'; ?>
			<?php $selecttickets .= JHtml::_('select.options', $this->tickets); ?>
			<?php $selecttickets .= '</select>'; ?>
			<?php $selecttickets .= ' <a href="javascript:void(0)" onclick="addSubscriberTickets();" class="rsextra">'.JText::_('COM_RSEVENTSPRO_SUBSCRIBER_ADD_TICKET').'</a>'; ?>
			
			<?php echo JHtml::_('rsfieldset.element', '<label>'.JText::_('COM_RSEVENTSPRO_SUBSCRIBER_SELECT_TICKETS').'</label>', $selecttickets); ?>
			<?php echo JHtml::_('rsfieldset.element', '<label>&nbsp;</label>', '<span class="rsextra" id="ticketscontainer"></span><span id="ticketshidden"></span>'); ?>
			<?php echo JHtml::_('rsfieldset.element', '<label>&nbsp;</label>', '<a class="rsextra" id="cleartickets" style="display:none;" href="javascript:void(0);" onclick="clearTickets();">'.JText::_('COM_RSEVENTSPRO_SUBSCRIBER_CLEAR_TICKETS').'</a>'); ?>
			
			<?php } ?>
			<?php echo JHtml::_('rsfieldset.end'); ?>
		</div>
		
		<div class="span6 rswidth-50 rsfltlft">
			<?php if ($this->item->log) { ?>
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_RSEVENTSPRO_SUBSCRIBER_LOG'); ?></legend>
				<pre class="rslog"><?php echo $this->item->log; ?></pre>
			</fieldset>
			<?php } ?>
			
			<?php JFactory::getApplication()->triggerEvent('rsepro_info',array(array('method'=>&$this->item->gateway, 'data' => $this->params))); ?>
			
			<?php if (!empty($this->item->SubmissionId) && !empty($this->fields)) { ?>
			<?php echo JHtml::_('rsfieldset.start', 'adminform', JText::_('COM_RSEVENTSPRO_SUBSCRIBER_RSFORM')); ?>
			<?php foreach ($this->fields as $field) { ?>
			<?php $name = @$field['name']; ?>
			<?php $value = @$field['value']; ?>
			<?php $value = (strpos($value,'http://') !== false || strpos($value,'https://') !== false) ? '<a href="'.$value.'" target="_blank">'.$value.'</a>' : $value; ?>
			<?php echo JHtml::_('rsfieldset.element', '<label>'.$name.'</label>', '<span class="rsextra">'.$value.'</span>'); ?>
			<?php } ?>
			<?php echo JHtml::_('rsfieldset.end'); ?>
			<?php } ?>
		</div>
		
	</div>

	<?php echo JHTML::_('form.token'); ?>
	<input type="hidden" name="task" value="" />
	<?php echo $this->form->getInput('id'); ?>
	<?php echo $this->form->getInput('ide'); ?>
	<?php echo JHTML::_('behavior.keepalive'); ?>
</form>