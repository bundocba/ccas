<?php

/**

 * @version		$Id: default.php 15 2009-11-02 18:37:15Z chdemko $

 * @package		Joomla16.Tutorials

 * @subpackage	Components

 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.

 * @author		Christophe Demko

 * @link		http://joomlacode.org/gf/project/helloworld_1_6/

 * @license		License GNU General Public License version 2 or later

 */

// No direct access to this file

defined('_JEXEC') or die('Restricted access');

$rows=$this->items; 



$i=0;



$Itemid = JRequest::getVar('Itemid');



$doc = JFactory::getDocument();



$page_title = $doc->getTitle();





?>

<h2><?php echo $page_title; ?></h2>
<p>
	<img alt="" src="images/pic_event.png" style="width: 381px; height: 285px;float:right;   margin-top: 20px;" /></p>

<div id="list_event" >

<?php foreach($rows as $row):

	$link = JRoute::_("index.php?option=com_fwevent&view=event&eventid=".$row->id,false); 

	$i++;

	

	/* $title1 = str_replace(" ","<br/><span>",$row->title); */

?>	



	<div class="event class_vent<?php echo $i;?> event_bg">

		<div class="e_title">

			<h2>

			<!--	<a   href='<?php echo $link; ?>'>-->

					<?php echo $row->title;?>

			<!--	</a>-->

			</h2>

		</div>



		<div class="event_description">

			<div class="div_des">

				<div class="title2"><?php echo $row->text_title;?></div>
				 <div class="event_img">
					<img src="<?php echo JURI::base().$row->image; ?>"  width="200px"/>
				</div> 
				
				<div class="intro">

					<?php 

					$str = $row->introtext;

					$arr = explode(" ", str_replace(",", ", ", $str));

					for ($index = 0; $index < 90; $index++) { ?>

					<?php echo $arr[$index]. " ";?>

					<?php	} ?>
				</div>
				
				<div class="venue">
					<div style="float:left; margin-right:3px;">Venue: </div>
					<div style="float:left;"><?php echo nl2br( $row->venue);?></div>
				</div>
				
				<?php if($row->price1==0 && $row->price2 ==0) { ?>
					<div class="course_fee">Price for CCAS Member: <span>NIL</span></div>		
				<?php } else {  ?>
					<div class="course_fee">Price for CCAS Member: <span><?php echo '$'.number_format($row->price1, 2);?></span></div>
					<div class="course_fee">Price for Non-CCAS Member:  <span><?php echo '$'.number_format($row->price2, 2);?></span></div>
				<?php } ?>

				

			</div>

	<!--		<div class="file"><a class="moduleItemReadMore"  target="_blank" href="<?php echo JURI::root();?><?php echo $row->file; ?>">Dowload</a></div>-->

		<!--	 <p><a  class="moduleItemReadMore moreinfo"  href='<?php echo $link; ?>'>Read more</a></p> -->

		</div>

		

	</div>




<?php endforeach;?>

	<div class="pagination">

		<?php echo $this->pagination->getPagesLinks();?>



		<div class="clear"></div>

	</div>

	<div class="clear"></div>


</div>
