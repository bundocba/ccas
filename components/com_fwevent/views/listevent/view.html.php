<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

 
/**
 * HTML View class for the HelloWorld Component
 */
class FwEventViewListEvent extends JViewLegacy
{
    // Overwriting JView display method
    function display($tpl = null) 
    {                                      
 		/* $model = $this->getModel();
 	
    	$state		= $this->get('State');
    	$this->items = $this->get("List");
    	$this->params = &$state->params;
    
   // echo "<pre>"; print_r($this->items); exit;    
        
        $this->pagination    = $this->get('Pagination');
   // echo "<pre>"; print_r($this->pagination);   */
	$model = $this->getModel();
	$items = & $this->get( 'Data');
	$total = & $this->get( 'Total');
	$pageNav = & $this->get( 'Pagination' );

	$this->assignRef('items', $items);
	$this->assignRef('pagination', $pageNav);

 
        // Display the view
        parent::display($tpl);         
    }
}