<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
$db =& JFactory::getDBO();
$app =& JFactory::getApplication();
$user=&JFactory::getUser();
$user_id=$user->get('id');
$query="select * from #__fw_user WHERE `user_id`='".$user_id."' ";
$db->setQuery($query);
$row_user=$db->loadObject();

$row=$this->item;


?>

<div class="event_detail">
	<div>
		<h2><?php echo $row->title?></h2>
		<div>
			<div class="title2"><?php echo $row->text_title;?></div>
			<div class="venue">				<div style="float:left; margin-right:3px;">Venue: </div>				<div style="float:left;"><?php echo nl2br( $row->venue);?></div>			</div>						<?php if($row->price1==0 && $row->price2 ==0) { ?>				<div class="course_fee">Price for CCAS Member: <span>NIL</span></div>					<?php } else {  ?>				<div class="course_fee">Price for CCAS Member: <span><?php echo '$'.number_format($row->price1, 2);?></span></div>				<div class="course_fee">Price for Non-CCAS Member:  <span><?php echo '$'.number_format($row->price2, 2);?></span></div>			<?php } ?>
			<div class="intro"><?php echo $row->introtext; ?><?php echo $row->fulltext;  ?></div>
		</div>

		<!-- End Form -->
	</div>

	<div class="clear"></div>
</div>