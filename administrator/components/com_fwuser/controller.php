<?php defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
class fwuserController extends JController{
	function display($cachable = false){
		JRequest::setVar('view', JRequest::getCmd('view', 'user'));
		parent::display($cachable);
	}
}

?>