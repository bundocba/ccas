<?php
/**
 * @version		$Id: default.php 17130 2010-05-17 05:52:36Z eddieajau $
 * @package		Joomla.Site
 * @subpackage	Templates.beez5
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
$app = JFactory::getApplication();
$templateparams =$app->getTemplate(true)->params;

//if ($templateparams->get('html5')!=1)
if (1 == 0)
{
	//require(JPATH_BASE.'/components/com_content/views/categories/tmpl/default.php');
	//evtl. ersetzen durch JPATH_COMPONENT.'/views/...'
} else {
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');
?>
<div class="cen-cats categories-list<?php echo $this->pageclass_sfx;?>">
<?php if ($this->params->get('show_page_heading', 1)) : ?>
<h1>
	<?php echo $this->escape($this->params->get('page_heading')); ?>
</h1>
<?php endif; ?>
<?php if ($this->params->get('show_base_description')) : ?>
	<?php 	//If there is a description in the menu parameters use that; ?>
		<?php if($this->params->get('categories_description')) : ?>
			<?php echo  JHtml::_('content.prepare',$this->params->get('categories_description')); ?>
		<?php  else: ?>
			<?php //Otherwise get one from the database if it exists. ?>
			<?php  if ($this->parent->description) : ?>
				<div class="category-desc">
					<?php  echo JHtml::_('content.prepare', $this->parent->description); ?>
				</div>
			<?php  endif; ?>
		<?php  endif; ?>
<?php endif; ?>
<?php
echo $this->loadTemplate('items');
?>
</div>
<div class="clr"></div>
<?php } ?>
<style>

.item-title {
	margin: 0 0 10px 0;
}
.item-title a {
	color: #1590cf;
	font-size: 18px;

}

.clr {
	clear:both;
}
.cat-items {

    display: block;
    float: left;
    margin-bottom: 15px;
    margin-left: 80px;
    margin-top: 15px;
    width: 820px;
}
}
.cat-items .cat-items-row {
	display: none;
}
.cat-items .cat-items-row-0 {
	display: block;
	
}
.pagination {
	display: block;
	float: left;
	width: 100%;
	margin: 20px 0 0 0;
}
.pagination ul {
	text-align: left;
}
.pagination ul li {
	display: inline-block;
	*display:inline;zoom:1;
	margin: 0 10px;

}
.cat-items .cat-item {
	float: left;
	width: 194px;
	/* margin: 0 0 0 11px; */
	margin: 0px;
	height:225px;
}


.cat-items .cat-item .cat-item-title {
    clear: both;
    display: block;
    margin: 5px 0 0;
    text-align: center;
    width: 101px;
	padding-top: 10px;
}

.cat-items .cat-item .cat-item-introtext {
	margin: 5px 0 0 0;
	float: left;
	display: block;
	text-align:center;
	font-size:12px;
	width:101px;
	clear:both;
}
.cat-items .cat-item .cat-item-title a {
	color: #716363;
	font-size: 14px;
	text-align:center;
	padding-top:10px;
	float:left;
	width:101px;
}
.cat-items .cat-item .cat-item-title a:hover {
	color: #CD1041;
}
.cat-items .cat-item.cat-item-0 {
	margin: 0;
}
 
.cat-items .cat-item .cat-item-img {
	float: left;
	width: 94px;
	border:3px solid #716363;
	border-radius:3px;
}

.cat-items .cat-item .cat-item-img img {
	float: left;
	width: 94px;
	height: 117px;
}


</style>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('.page-link').click(function(e) {
			e.preventDefault();
			var cat = $(this).attr("data-cat");
			var page = $(this).attr("data-page");

			$("#cat-" + cat + " .cat-items-row").hide();
			$("#cat-" + cat + " .cat-items-row-" + page).show();
			
		}) 
	})

</script>