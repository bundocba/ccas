<?php

defined('_JEXEC') or die('Restricted access'); 
?>
<div id="newsflash" class="testimonials"><ul>
<?php foreach ($list as $item) :  ?><li>
		<div id="images_testimonials">			<img src="<?php echo $item->images; ?>" height="383px" width="940px" />		</div>
		<div id="intro_testimonials">			
		<div id="intro_testimonials_inner">		
		<div id="intro_testimonials_inner_2">		
		<?php echo $item->introtext; ?>			
		</div>
		</div>
		</div>
		</li>
<?php endforeach; ?>
</ul>
	
</div>