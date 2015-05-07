<?php

/**
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
$class = ' class="first"';

$db = JFactory::getDbo();

if (count($this->items[$this->parent->id]) > 0 && $this->maxLevelcat != 0) :
?>
<ul>
<?php 
foreach($this->items[$this->parent->id] as $id => $item) { 
	$query = $db->getQuery(true);
	$query->select("*");
	$query->from("`#__content` AS `c`");
	$query->where("`c`.`catid`='{$item->id}'");
	$query->where("`c`.`state`<>-2");
	$query->where("`c`.`state`<>0");	$query->order("c.ordering asc");

	$db->setQuery($query);
	$rows = $db->loadObjectList();
	// for ($i = 0; $i < 1; $i ++) {	
	// 	foreach ($rows as $row) {
	// 		$rows[] = $row;
	// 	}
	// }
?>
	<?php
	if ($this->params->get('show_empty_categories_cat') || $item->numitems || count($item->getChildren())) :
	if (!isset($this->items[$this->parent->id][$id + 1]))
	{
		$class = ' class="last"';
	}
	?>
	<li<?php echo $class; ?> id="cat-<?php echo $item->id; ?>">
	<?php $class = ''; ?>
		<div class="item-title">
		<!--<a href="<?php echo JRoute::_(ContentHelperRoute::getCategoryRoute($item->id));?>">-->
			<?php echo $this->escape($item->title); ?> <!--</a>-->
		</div>
		<div class="cat-items">
			<?php 
			$pagination = "<ul>";
			$rows_count = count($rows);
			$item_per_row =10;
			$item_rows = 0;
			foreach ($rows as $i => $row) {
				$images = json_decode($row->images);
				if ($i % $item_per_row == 0) {
					echo "<div class='cat-items-row cat-items-row-".($item_rows)."'>";
					$pagination .= "<li><a class='page-link' data-cat='".$item->id."' data-page='".$item_rows."' href='#$item_rows'>".(++$item_rows)."</a></li>";
				}
			?>
			<div id="cat-item" class="cat-item <?php echo "cat-item-".($i % $item_per_row); ?>" >
				<div class="cat-item-img">
					<?php if ($images->image_intro != "") { ?>
					<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($row->id)); ?>">
						<img src="<?php echo $images->image_intro; ?>" /> </a>
					<?php } ?>
				</div>
				<div class="cat-item-title">
					<!--<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($row->id)); ?>"> --><?php echo $row->title; ?><!--</a>-->
				</div>				
				<div class="cat-item-introtext">
					<?php echo $row->introtext ?>
				</div>
			</div>
			<?php
				if ($i % $item_per_row == $item_per_row - 1 || $i == $rows_count - 1) {
					echo "<div class='clr'></div></div>";
				}
			} 
			$pagination .= "</ul>";
			?>
			<div class="clr"></div>
			<div class="pagination">
				<?php 
				if ($item_rows > 1) {
					echo $pagination; 
				}
				?>
				<div class="clr"></div>
			</div>
			<div class="clr"></div>
		</div>
		<?php if (count($item->getChildren()) > 0) :
			$this->items[$item->id] = $item->getChildren();
			$this->parent = $item;
			$this->maxLevelcat--;
			echo $this->loadTemplate('items');
			$this->parent = $item->getParent();
			$this->maxLevelcat++;
		endif; ?>
		<div class="clr"></div>
	</li>
	<?php endif; ?>
<?php } ?>
</ul>
<?php endif; ?>
