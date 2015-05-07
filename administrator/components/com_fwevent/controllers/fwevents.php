<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

class FwEventControllerFwEvents extends JControllerAdmin
{
	//override get model JControllerAdmin
	public function getModel($name = 'FwEvent', $prefix = 'FwEventModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
	
	public function saveorder()
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Get the arrays from the Request
        $order    = JRequest::getVar('order',    null, 'post', 'array');
        $originalOrder = explode(',', JRequest::getString('original_order_values'));

        // Make sure something has changed
        if (!($order === $originalOrder)) {
            parent::saveorder();
        } else {
            // Nothing to reorder
            $this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_list, false));
            return true;
        }
    }

    public function open()
    {
    	$this->updateStatus(1);
    }
    
	public function close()
    {
    	$this->updateStatus(0);
    }
    
    private function updateStatus($status)
    {
    	$cids    = JRequest::getVar('cid', null, 'post','array');

    	$model = $this->getModel();
    	if($cids != null)
    		$result = $model->updateStatus($cids[0],$status);
    	
    	if($result)
    		$message = "Update Successfully";
    	else 
    		$message = "Update UnSuccessfully";
    		
    	$this->setRedirect(JRoute::_('index.php?option=com_fwevent', false),$message);
    	return;
    }

	
}
