<?php
/**
 * @version		$Id: default.php 17137 2010-05-17 07:00:07Z infograf768 $
 * @package		Joomla.Site
 * @subpackage	Templates.beez5
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

$app = JFactory::getApplication();
$templateparams = $app->getTemplate(true)->params;
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

// Create shortcut to parameters.
$params = $this->item->params;

if ($templateparams->get('html5') != 1) :
	require(JPATH_BASE.'/components/com_content/views/article/tmpl/default.php');
	//evtl. ersetzen durch JPATH_COMPONENT.'/views/...'

else :
	JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
?>
<?php if($params->get('show_title')):?>
<h2 class="title">
	<?php echo $this->escape($this->item->title); ?>
</h2>
<?php endif;?>
<div class="sidebar">{module About Randeer}</div>
<div class="content">
<article class="item-page<?php echo $this->pageclass_sfx?>">

<div class="wrapper"> <div class="box">
<?php if ($this->params->get('show_page_heading', 1) And $params->get('show_title')) :?>
</hgroup>
<?php endif; ?>
	<?php  if (!$params->get('show_intro')) :
		echo $this->item->event->afterDisplayTitle;
	endif; ?>
	<?php echo $this->item->event->beforeDisplayContent; ?>


	<?php if (isset ($this->item->toc)) : ?>
		<?php echo $this->item->toc; ?>
	<?php endif; ?>
	<?php echo $this->item->text; ?>
	<?php echo $this->item->event->afterDisplayContent; ?>
</div></div>
</article>
<?php endif; ?>
</div>