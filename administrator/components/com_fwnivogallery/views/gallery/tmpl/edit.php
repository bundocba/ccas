<?php
// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">
    Joomla.submitbutton = function(task)
    {

        if (task == 'gallery.cancel' || document.formvalidator.isValid(document.id('gallery-form'))) {
            Joomla.submitform(task, document.getElementById('gallery-form'));
        }
    }
    
</script>

<form action="<?php echo JRoute::_('index.php?option=com_fwnivogallery&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="gallery-form" class="form-validate" enctype="multipart/form-data">
    <div class="width-60 fltlft">
        <fieldset class="adminform">
            <legend><?php echo empty($this->item->id) ? 'New Gallery' : "Detail #".$this->item->id; ?></legend>
            <ul class="adminformlist">
                <li><?php echo $this->form->getLabel('title'); ?>
                <?php echo $this->form->getInput('title'); ?></li>
                
                <li><?php echo $this->form->getLabel('link'); ?>
                <?php echo $this->form->getInput('link'); ?></li>
                
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
                <li><?php echo $this->form->getLabel('created_date'); ?>
                <?php echo $this->form->getInput('created_date'); ?></li>
                
                 <li><?php echo $this->form->getLabel('published'); ?>
                <?php echo $this->form->getInput('published'); ?></li>
            </ul>

        </fieldset>
    <?php echo JHtml::_('sliders.start', 'gallery-sliders-'.$this->item->id, array('useCookie'=>1)); ?>

    <?php echo JHtml::_('sliders.end'); ?>
    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
</div>

<div class="clr"></div>
</form>
