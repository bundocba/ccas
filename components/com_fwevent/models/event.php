<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');  

class FwEventModelEvent extends JModel
{
	protected $_extension = 'com_fwevent';
	
    
    public function getEvent($id)
    {
    	$db = &JFactory::getDBO();
    	
    	$sql = "SELECT * FROM #__fwevent WHERE published = 1 AND id = ".(int)$id;
    	$db->setQuery($sql);
    	$row = $db->loadObject();

		return $row;
    }
	
	
}