<?php
// no direct access
defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$user        = JFactory::getUser();       
$listOrder    = $this->escape($this->state->get('list.ordering'));
$listDirn    = $this->escape($this->state->get('list.direction'));

$saveOrder    = $listOrder=='title';
$params       = (isset($this->state->params)) ? $this->state->params : new JObject();
$canOrder     = $user->authorise('core.edit.state','com_fwnivogallery'); 

$saveOrder    = ($listOrder == 'g.ordering');

?>
<form action="<?php echo JRoute::_('index.php?option=com_fwnivogallery&view=galleries'); ?>" method="post" name="adminForm" id="adminForm">
    <fieldset id="filter-bar">
        <!-- Filter Search -->
        <div class="filter-search fltlft">
            <label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
            <input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_BANNERS_SEARCH_IN_TITLE'); ?>" />
            <button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
            <button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
        </div>
        <!-- End Filter Search -->
        
        <!-- Filter select -->
        <div class="filter-select fltrt">
            <select name="filter_state" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
                <?php echo JHtml::_('select.options', NivoGalleryHelper::publishedOptions(), 'value', 'text', $this->state->get('filter.state'), true);?>
            </select>
        </div>
        <!-- End Filter select -->
        
    </fieldset>
    <div class="clr"> </div>

    <table class="adminlist">
        <thead>
            <tr>
                <th width="1%">
                    <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
                </th>
                <th>
                    <?php echo JHtml::_('grid.sort',  'Title', 'g.title', $listDirn, $listOrder); ?>
                </th>
             	
             	<th>
                    <?php echo JHtml::_('grid.sort',  'Created Date', 'g.created_date', $listDirn, $listOrder); ?>
                </th>
             	
             	<th width="10%">
                    <?php echo JHtml::_('grid.sort',  'Order', 'g.ordering', $listDirn, $listOrder); ?>
                    <?php if ($canOrder && $saveOrder) :?>
                        <?php echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'galleries.saveorder'); ?>
                    <?php endif; ?>
                </th>
          
                <th width="5%">
                    <?php echo JHtml::_('grid.sort', 'JSTATUS', 'g.published', $listDirn, $listOrder); ?>
                </th>
                <th width="1%" class="nowrap">
                    <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'g.id', $listDirn, $listOrder); ?>
                </th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="9">
                    <?php echo $this->pagination->getListFooter(); ?>
                </td>
            </tr>
        </tfoot>
        
        <tbody>
            <?php 
            $canCreate    = $user->authorise('core.create',        'com_fwnivogallery');
            $canEdit    = $user->authorise('core.edit',            'com_fwnivogallery');
            $canChange    = $user->authorise('core.edit.state',    'com_fwnivogallery');
        	$ordering    = ($listOrder == 'g.ordering');
            foreach ($this->items as $i => $item) : 
                
                ?>
                <tr class="row<?php echo $i % 2; ?>">
                    <td class="center">
                        <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                    </td>
                    <td>
                        <a href="<?php echo JRoute::_('index.php?option=com_fwnivogallery&task=gallery.edit&id='.(int) $item->id); ?>">
                            <?php echo $item->title; ?>
                        </a>
                    </td>
                    
                    <td class="center">
                        <?php echo $item->created_date; ?>
                    </td>
                    
            		
            		 <td class="order">
	                    <?php if ($canChange) : ?>  
	                        <?php if ($saveOrder) : ?>
	                                <span><?php echo $this->pagination->orderUpIcon($i, true, 'galleries.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
	                                <span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'galleries.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
	                        <?php endif; ?>
	                        <?php $disabled = $saveOrder ?  '' : 'disabled="disabled"'; ?>
	                        <input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="text-area-order" />
	                    <?php else : ?>
	                        <?php echo $item->ordering; ?>
	                    <?php endif; ?>
                	</td>
                
                    <td class="center">
                        <?php echo JHtml::_('gallery.published', $item->published, $i); 
                        ?>
                    </td>
                    <td class="center">
                        <?php echo $item->id; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div>
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>