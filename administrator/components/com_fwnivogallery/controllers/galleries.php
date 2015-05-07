<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

class FwNivoGalleryControllerGalleries extends JControllerAdmin
{
	//override get model JControllerAdmin
	public function getModel($name = 'Gallery', $prefix = 'FwNivoGalleryModel', $config = array('ignore_request' => true))
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
}
