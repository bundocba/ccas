<?php defined('_JEXEC') or die('Restricted Access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal', 'a.modal');
$db = JFactory::getDBO();
?>
<form action="<?php echo JRoute::_('index.php?option=com_fwuser'); ?>" method="post" name="adminForm">
	<table class="adminlist">
		<thead>
            <tr>
				<th width="5">ID</th>
                <th width="20"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->rows); ?>);" /></th>
				<th><?php echo JText::_('FI_NAME'); ?></th>
				<th><?php echo JText::_('FI_CODE1'); ?></th>
				<th><?php echo JText::_('FI_CODE2'); ?></th>
				<th><?php echo JText::_('FI_STATUS'); ?></th>
            </tr>
		</thead>
		<tfoot>
            <tr>
                <td colspan="6"><?php echo $this->pagination->getListFooter(); ?></td>
            </tr>
		</tfoot>
		<tbody>
<?php 
	$i = 0;
	foreach($this->rows as $n => $row): 
		$published 	= JHTML::_('grid.published', $row, $i );
?>
            <tr class="row<?php echo $n % 2; ?>">
				<td>
                    <a href="index.php?option=com_fwuser&task=countryedit.edit&id=<?php echo $row->id; ?>"><?php echo $row->id; ?></a>
                </td>
                <td>
                    <?php echo JHtml::_('grid.id', $n, $row->id); ?>
                </td>
				<td>
					<?php echo $row->name; ?>
				</td>
				<td>
					<?php echo $row->code1; ?>
				</td>
				<td>
					<?php echo $row->code2; ?>
				</td>
				<td>
					<?php echo JHtml::_('jgrid.published', $row->published, $i, 'country.'); ?>
				</td>
            </tr>
<?php 
		$i++;
	endforeach; 
?>
		</tbody>
	</table>

<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="country" />
<input type="hidden" name="boxchecked" value="0" />
<?php echo JHtml::_('form.token'); ?>
</form>