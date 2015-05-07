<?php
/**
 * @version		$Id: edit.php 60 2010-11-27 18:45:40Z chdemko $
 * @package		Joomla16.Tutorials
 * @subpackage	Components
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @author		Christophe Demko
 * @link		http://joomlacode.org/gf/project/helloworld_1_6/
 * @license		License GNU General Public License version 2 or later
 */
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">     
	Joomla.submitbutton = function(task)    {        
		if (task == 'fwevent.cancel' || document.formvalidator.isValid(document.id('fwevent-form'))) 	{            
			Joomla.submitform(task, document.getElementById('fwevent-form'));        
		}    
	}	
</script>

<form action="<?php echo JRoute::_('index.php?option=com_fwevent&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="fwevent-form"  enctype="multipart/form-data"> 
	<div class="width-60 fltlft">        
		<fieldset class="adminform">           
			<legend><?php echo empty($this->item->id) ? 'New Event' : "Detail #".$this->item->id; ?></legend>            
				<ul class="adminformlist">                
					<li>
					<?php echo $this->form->getLabel('title'); ?>              
					<?php echo $this->form->getInput('title'); ?>
					</li>   
					<li>
					<?php echo $this->form->getLabel('venue'); ?>               
					<?php echo $this->form->getInput('venue'); ?>
					</li>  
   
					<li>
					<?php echo $this->form->getLabel('start_date'); ?>        
					<?php echo $this->form->getInput('start_date'); ?>
					</li>  
					<li>
					<?php echo $this->form->getLabel('end_date'); ?>   
					<?php echo $this->form->getInput('end_date'); ?>
					</li>
					<li>
					<?php echo $this->form->getLabel('price1'); ?>                
					<?php echo $this->form->getInput('price1'); ?>
					</li>           
					<li><?php echo $this->form->getLabel('price2'); ?>         
					<?php echo $this->form->getInput('price2'); ?></li>        
					<li><?php echo $this->form->getLabel('image'); ?>          
					<?php echo $this->form->getInput('image'); ?></li>          
				</ul>            		
				<div class="clr"></div>			
					<?php echo $this->form->getLabel('description'); ?>		
					<div class="clr"></div>			
					<?php echo $this->form->getInput('description'); ?>       			    
		</fieldset>    
	</div>
	<div class="width-40 fltrt">    
		<fieldset class="adminform">           
			<legend><?php echo 'Option'; ?></legend>            
			<ul class="adminformlist">                
				<li>
					<?php echo $this->form->getLabel('created_date'); ?>                
					<?php echo $this->form->getInput('created_date'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('published'); ?>                
					<?php echo $this->form->getInput('published'); ?>
				</li>            
			</ul>      
		</fieldset>    
		<?php echo JHtml::_('sliders.start', 'event-sliders-'.$this->item->id, array('useCookie'=>1)); ?>    
		<?php echo JHtml::_('sliders.end'); ?>    
		<input type="hidden" name="task" value="" />    
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

