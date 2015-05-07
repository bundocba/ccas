<?php

/**

 * default template file for HelloWorlds view of HelloWorld component

 *

 * @version		$Id: default.php 46 2010-11-21 17:27:33Z chdemko $

 * @package		Joomla16.Tutorials

 * @subpackage	Components

 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.

 * @author		Christophe Demko

 * @link		http://joomlacode.org/gf/project/helloworld_1_6/

 * @license		License GNU General Public License version 2 or later

 */

// No direct access to this file

defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior

JHtml::_('behavior.tooltip');

$user		= JFactory::getUser();

$listOrder	= $this->escape($this->state->get('list.ordering'));

$listDirn	= $this->escape($this->state->get('list.direction'));

$canOrder	= $user->authorise('core.edit.state', 'com_fwevent.events');

$saveOrder	= $listOrder == 'e.ordering';

?><form action="<?php echo JRoute::_('index.php?option=com_fwevent'); ?>" method="post" name="adminForm">

	<fieldset id="filter-bar">

		<div class="filter-search fltlft">

			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>

			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_WEBLINKS_SEARCH_IN_TITLE'); ?>" />

			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>

			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>

		</div>

		<div class="filter-select fltrt">





			<select name="filter_state" class="inputbox" onchange="this.form.submit()">

				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>

				<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.state'), true);?>

			</select>



			

		</div>

	</fieldset>

	<div class="clr"> </div>

	<table class="adminlist">

		<thead>

			<tr>

				<th width="5">

					<?php echo JText::_('COM_FWEVENT_HEADING_ID'); ?>

				</th>

				<th width="20">

					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />

				</th>			

				<th width="450">

					<?php echo JHtml::_('grid.sort',  'COM_FWEVENT_HEADING_TITLE', 'e.title', $listDirn, $listOrder); ?>

				</th>		

				<th width="250">

					<?php echo JHtml::_('grid.sort',  'Image', 'e.image', $listDirn, $listOrder); ?>

					

				</th>			

				<th>

					<?php echo JHtml::_('grid.sort',  'COM_FWEVENT_FIELD_START_DATE_LABEL', 'e.start_date', $listDirn, $listOrder); ?>

				</th>				

<!--				<th>

					

				/*	<?php echo JHtml::_('grid.sort',  'End date', 'e.end_date', $listDirn, $listOrder); ?>*/

				</th>-->

				<th>

					

					<?php echo JHtml::_('grid.sort',  'Create date', 'e.created_date', $listDirn, $listOrder); ?>

				</th>

				<th width="10%">

					<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ORDERING', 'e.ordering', $listDirn, $listOrder); ?>

					<?php if ($canOrder && $saveOrder) :?>

						<?php echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'fwevents.saveorder'); ?>

					<?php endif; ?>

				</th>

                <th width="5%">

                   <?php echo JHtml::_('grid.sort',  'Published', 'e.state', $listDirn, $listOrder); ?>

                </th>

			</tr>

		</thead>



		<tbody>

			<?php foreach($this->items as $i => $item): 

			$ordering	= ($listOrder == 'e.ordering');

			$canCheckin	= $user->authorise('core.manage',		'com_checkin') || $item->checked_out==$user->get('id') || $item->checked_out==0;

			$canChange	= $user->authorise('core.edit.state',	'com_fwevent.event') && $canCheckin;

			?>

				<tr class="row<?php echo $i % 2; ?>">

					<td>

						<?php echo $item->id; ?>

					</td>

					<td>

						<?php echo JHtml::_('grid.id', $i, $item->id); ?>

					</td>

					<td>

						<a href="<?php echo JRoute::_('index.php?option=com_fwevent&task=fwevent.edit&id=' . $item->id); ?>">

							<?php echo $item->title; ?>

						</a>

					</td>

					<td>

						<a href="<?php echo JRoute::_('index.php?option=com_fwevent&task=fwevent.edit&id=' . $item->id); ?>">

						<center>
							<img src="<?php echo JURI::root();?>/<?php echo $item->image; ?>" height= "60px"  width="60px" />
						</center>

						</a>

					</td>

					<td>

						<a href="<?php echo JRoute::_('index.php?option=com_fwevent&task=fwevent.edit&id=' . $item->id); ?>">

							<?php echo $item->start_date; ?>

						</a>
<!--				</td>

					<td>

						<a href="<?php echo JRoute::_('index.php?option=com_fwevent&task=fwevent.edit&id=' . $item->id); ?>">

							<?php echo $item->end_date; ?>

						</a>

					</td>-->

					<td>

						<a href="<?php echo JRoute::_('index.php?option=com_fwevent&task=fwevent.edit&id=' . $item->id); ?>">

							<center>	<?php echo $item->created_date; ?> </center>

						</a>

					</td>

					<td class="order">

					<?php if ($canChange) : ?>

						<?php if ($saveOrder) :?>

							<?php if ($listDirn == 'asc') : ?>

								<span><?php echo $this->pagination->orderUpIcon($i, true, 'fwevents.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>

								<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'fwevents.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>

							<?php elseif ($listDirn == 'desc') : ?>

								<span><?php echo $this->pagination->orderUpIcon($i, true, 'fwevents.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>

								<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'fwevents.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>

							<?php endif; ?>

						<?php endif; ?>

						<?php $disabled = $saveOrder ?  '' : 'disabled="disabled"'; ?>

						<input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="text-area-order" />

					<?php else : ?>

						<?php echo $item->ordering; ?>

					<?php endif; ?>

				</td>

					<td class="center">



					<?php echo JHtml::_('jgrid.published', $item->published, $i, 'fwevents.', 1); ?>

                        

                    </td>

				</tr>

				<?php endforeach; ?>

		</tbody>

		<tfoot>

			<tr>

				<td colspan="8"><?php echo $this->pagination->getListFooter(); ?></td>

			</tr>

		</tfoot>

	</table>

	

	<div>

		<input type="hidden" name="task" value="" />

		<input type="hidden" name="boxchecked" value="0" />

		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />

		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />

		<?php echo JHtml::_('form.token'); ?>

	</div>

</form>

