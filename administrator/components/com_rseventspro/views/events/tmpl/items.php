<?php
/**
* @version 1.0.0
* @package RSEvents!Pro 1.0.0
* @copyright (C) 2011 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined( '_JEXEC' ) or die( 'Restricted access' );

echo 'RS_DELIMITER0';
$k = 0;
$i = $this->total;
$n = count($this->data);
if (!empty($this->data))
{
	foreach ($this->data as $id) {
		$row = $this->getDetails($id);
		$stars = rseventsproHelper::stars($row->id);
		$complete = empty($row->completed) ? ' rs_incomplete' : '';
		
		echo '<tr class="row'.$k.$complete.'">';
		echo '<td align="center" class="center hidden-phone">'.JHTML::_('grid.id',$i,$row->id).'</td>';
		echo '<td class="hidden-phone">';
		echo '<div class="rs_event_img">';
		$image = !empty($row->icon) ? 'events/thumbs/s_'.$row->icon : 'blank.png';
		echo '<img src="'.JURI::root().'components/com_rseventspro/assets/images/'.$image.'" alt="" width="70" />';
		echo '</div>';
		echo '</td>';
		echo '<td class="nowrap has-context">';
		
		if ($stars) {
			echo '<div class="rs_stars">';
			echo '<ul class="rsepro_star_rating">';
			echo '<li id="rsepro_current_rating" class="rsepro_feedback_selected_'.$stars.'">&nbsp;</li>';
			echo '</ul>';
			echo '</div>';
		}
		
		echo '<div class="rs_event_details">';
		echo '<p>';
		echo '<b><a href="'.JRoute::_('index.php?option=com_rseventspro&task=event.edit&id='.$row->id).'">'.$row->name.'</a></b>';
		
		if (empty($row->completed)) 
			echo '<b>'.JText::_('COM_RSEVENTSPRO_GLOBAL_INCOMPLETE_EVENT').'</b>';
		
		echo '</p>';
		
		if ($row->allday)
			echo '<p>'.rseventsproHelper::date($row->start,rseventsproHelper::getConfig('global_date'),true).'</p>';
		else
			echo '<p>'.rseventsproHelper::date($row->start,null,true).'</p>';
		
		echo '<p>'.$this->getTickets($row->id).'</p>';
		echo '</div>';
		
		if ($row->parent) {
			echo '<div class="rs_child">';
			echo '<img src="'.JURI::root().'administrator/components/com_rseventspro/assets/images/baloon.png" alt="'.JText::_('COM_RSEVENTSPRO_CHILD_EVENT_INFO').'" title="'.JText::_('COM_RSEVENTSPRO_CHILD_EVENT_INFO').'" />';
			echo '</div>';
		}
		
		echo '</td>';
		echo '<td align="center" class="center hidden-phone"><a href="'.JRoute::_('index.php?option=com_rseventspro&task=location.edit&id='.$row->lid).'">'.$row->lname.'</a></td>';
		echo '<td align="center" class="center hidden-phone">'.(empty($row->owner) ? JText::_('COM_RSEVENTSPRO_GLOBAL_GUEST') : $row->uname).'</td>';
		echo '<td align="center" class="center hidden-phone">'.rseventsproHelper::categories($row->id, true).'</td>';
		echo '<td align="center" class="center hidden-phone">'.rseventsproHelper::tags($row->id,true).'</td>';
		
		if ($row->allday)
			echo '<td align="center" class="center hidden-phone"></td>';
		else
			echo '<td align="center" class="center hidden-phone">'.rseventsproHelper::date($row->end,null,true).'</td>';
		
		echo '<td align="center" class="center hidden-phone">'.JHTML::_('jgrid.published', $row->published, $i, 'events.').'</td>';
		echo '<td class="center hidden-phone">'.$id.'</td>';
		echo '</tr>';
		
		$i++;
		$k = 1-$k;
	}
}
echo 'RS_DELIMITER1';
JFactory::getApplication()->close();