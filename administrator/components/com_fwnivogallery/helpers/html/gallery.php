<?php
// No direct access
defined('_JEXEC') or die;

class JHtmlGallery
{
	public static function published($value = 0, $i, $canChange = true)
	{
		// Array of image, task, title, action
		$states	= array(
			1	=> array('tick.png',		'galleries.unpublish',	'JENABLED',	'Enable'),
			0	=> array('publish_x.png',	'galleries.publish',	'JDISABLED','Disable'),
			-2	=> array('trash.png',		'galleries.publish',	'JTRASHED',	'Enable'),
		);
		$state	= JArrayHelper::getValue($states, (int) $value, $states[0]);
		$html	= JHtml::_('image', 'admin/'.$state[0], JText::_($state[2]), NULL, true);
		if ($canChange) {
			$html	= '<a href="#" onclick="return listItemTask(\'cb'.$i.'\',\''.$state[1].'\')" title="'.JText::_($state[3]).'">'
					. $html.'</a>';
		}

		return $html;
	}
	
}
