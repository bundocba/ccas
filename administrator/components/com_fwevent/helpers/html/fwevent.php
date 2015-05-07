<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Utility class for creating HTML Grids
 *
 * @static
 * @package		Joomla.Administrator
 * @subpackage	com_redirect
 * @since		1.6
 */
class JHtmlFwevent
{
	/**
	 * @param	int $value	The state value.
	 * @param	int $i
	 * @param	string		An optional prefix for the task.
	 * @param	boolean		An optional setting for access control on the action.
	 */
	public static function published($value = 0, $i, $canChange = true)
	{
		// Array of image, task, title, action
		$states	= array(
			1	=> array('tick.png',		'fwevents.unpublish',	'JENABLED',	'COM_BIODATA_DISABLE_LINK'),
			0	=> array('publish_x.png',	'fwevents.publish',		'JDISABLED',	'COM_BIODATA_ENABLE_LINK'),
			2	=> array('disabled.png',	'fwevents.unpublish',	'JARCHIVED',	'JUNARCHIVE'),
			-2	=> array('trash.png',		'fwevents.publish',		'JTRASHED',	'COM_BIODATA_ENABLE_LINK'),
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
