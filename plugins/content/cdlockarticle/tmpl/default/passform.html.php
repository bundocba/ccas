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

$headertext = JText::_('PLG_CONTENT_CDLOCKARTICLE_ENTERPASSWORD');
if (isset($this->row->headertext) and $this->row->headertext)
{
    $headertext = stripslashes($this->row->headertext);
}
?>
<div class="{$name} <?php echo $this->uitheme; ?>" id="{$name}_<?php echo $this->random_id; ?>">
	<div class="{$name}_content ui-widget ui-widget-content ui-corner-all">
		<form action="<?php echo $this->formActionURL; ?>" method="post" data-{$name}-event='<?php echo json_encode( array('type' => 'submit', 'name' => 'checkPassword')); ?>'>
			<div class="{$name}_headertext ui-state-highlight ui-corner-all"><?php echo $headertext; ?></div>
			<div class="{$name}_spacer"></div>
			<input type="password" maxlength="255" name="{$name}_articlepassword" title="<?php echo strip_tags( $headertext ); ?>" />
			<input type="hidden" name="{$name}_task" value="post_checkPassword" />
            <input type="hidden" name="{$name}_sourceid" value="<?php echo $this->sourceid; ?>" />
            <input type="hidden" name="{$name}_context" value="<?php echo $this->context; ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</form>
        <div class="{$name}_clr"></div>
	</div>
</div>