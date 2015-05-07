<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @since		1.5
 */

defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
?>
<h2 class="title">
	<?php echo $this->escape($this->params->get('page_heading')); ?>
</h2>

<div class="blockLogin">
	
	<div class="login<?php echo $this->pageclass_sfx?>">
		
		
		<?php if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', $this->params->get('login_description')) != '') || $this->params->get('login_image') != '') : ?>
		<div class="login-description">
		<?php endif ; ?>

			<?php if($this->params->get('logindescription_show') == 1) : ?>
				<?php echo $this->params->get('login_description'); ?>
			<?php endif; ?>

			<?php if (($this->params->get('login_image')!='')) :?>
				<img src="<?php echo $this->escape($this->params->get('login_image')); ?>" class="login-image" alt="<?php echo JTEXT::_('COM_USER_LOGIN_IMAGE_ALT')?>"/>
			<?php endif; ?>

		<?php if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', $this->params->get('login_description')) != '') || $this->params->get('login_image') != '') : ?>
		</div>
		<?php endif ; ?>

		<form action="<?php echo JRoute::_('index.php?option=com_users&task=user.login'); ?>" method="post">

			<fieldset>
				<?php foreach ($this->form->getFieldset('credentials') as $field): ?>
					<?php if (!$field->hidden): ?>
						<div class="login-fields"><?php echo $field->label; ?>
						<?php echo $field->input; ?></div>
					<?php endif; ?>
				<?php endforeach; ?>
				<div class="login-fields1">
					<p id="form-login-remember">
						<label for="modlgn-remember"><?php echo JText::_('Remember me on this computer') ?></label>
						<input id="modlgn-remember" type="checkbox" name="remember" class="inputbox" value="yes"/>
					</p>
					<p class="forgot">
						<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>"><?php echo JText::_('COM_USERS_LOGIN_RESET'); ?></a>
					</p>
					<div class="button">
						<input type="submit"  class="bt-register" value="Login" />	
					</div>
				</div>
				<input type="hidden" name="return" value="<?php echo base64_encode($this->params->get('login_redirect_url', $this->form->getValue('return'))); ?>" />
				<?php echo JHtml::_('form.token'); ?>
			</fieldset>
		</form>
		
		
	</div>
	
</div>
<div class="blockReg">
	<h4>Not yet a member?</h4>
	<a href="index.php?option=com_virtuemart&view=user&layout=editaddress&Itemid=127" class="bt-create" title="Create Register"> Register now! </a>
</div>

<div class="clear"></div>