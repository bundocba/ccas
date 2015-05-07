<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class FwEventViewEvent extends JViewLegacy
{
    // Overwriting JView display method
    function display($tpl = null) 
    {      
    	$app = JFactory::getApplication();
    	$this->document->addScript(JURI::root()."components/com_fwevent/assets/js/fw_event.js?v=1.0");     
    	$eventId = JRequest::getVar('eventid',0); 

        $model = &$this->getModel();                   
   
    	$this->item = $model->getEvent($eventId);

        parent::display($tpl);         
    }
}