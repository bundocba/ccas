<?php
/**
 * Core Design Lock Article plugin for Joomla! 2.5
 * @author		Daniel Rataj, <info@greatjoomla.com>
 * @package		Joomla
 * @subpackage	Content
 * @category	Plugin
 * @version		2.5.x.2.0.5
 * @copyright	Copyright (C) 2007 - 2013 Great Joomla!, http://www.greatjoomla.com
 * @license		http://www.gnu.org/copyleft/gpl.html GNU/GPL 3
 * 
 * This file is part of Great Joomla! extension.   
 * This extension is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This extension is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

// no direct access
defined('_JEXEC') or die;
?>
<div class="{$name} <?php echo $this->uitheme; ?>" id="{$name}_<?php echo $this->random_id; ?>">
	<form action="<?php echo $this->formActionURL; ?>" method="post" data-{$name}-event='<?php echo json_encode( array('type' => 'submit', 'name' => ($this->isLocked ? 'un' : '') . 'lock')); ?>'>
        <button type="submit" data-{$name}-ui-button='<?php echo json_encode(array('icons' => array('primary' => 'ui-icon-' . ($this->isLocked ? 'un' : '') . 'locked'))); ?>'>
            <?php echo JText::_( ( $this->isLocked ? 'PLG_CONTENT_CDLOCKARTICLE_UNLOCK_ARTICLE' : 'PLG_CONTENT_CDLOCKARTICLE_LOCK_ARTICLE' ) ); ?>
        </button>
		<input type="hidden" name="{$name}_sourceid" value="<?php echo $this->sourceid; ?>" />
		<input type="hidden" name="{$name}_context" value="<?php echo $this->context; ?>" />
		<input type="hidden" name="{$name}_password" value="" />
		<input type="hidden" name="{$name}_headertext" value="" />
		<input type="hidden" name="{$name}_task" value="post_<?php echo ($this->isLocked ? 'un' : ''); ?>lock" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>