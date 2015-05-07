<?php defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');
class fwuserViewuser extends JView{
	function display($tpl = null){
		$items = $this->get('Items');
		$pagination = $this->get('Pagination');

		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}

		$this->rows = $items;
		$this->pagination = $pagination;

		$this->addToolBar();
		parent::display($tpl);
		$this->setDocument();
	}	
	protected function addToolBar(){
		fwuserHelper::addSubmenu('user');
		JToolBarHelper::title(JText::_('COM_FWUSER_USER_TITLE'), 'fwuser');
		
		$document = JFactory::getDocument();
		$document->addStyleDeclaration('.icon-48-fwuser {background-image: url(../administrator/components/com_fwuser/images/icon-48-user.png);}');
		
		$canDo = fwuserHelper::getActions();
		if ($canDo->get('core.create')){
			JToolBarHelper::addNewX('useredit.add');
		}
		if ($canDo->get('core.edit')){
			JToolBarHelper::editListX('useredit.edit');
		}
		if ($canDo->get('core.delete')){
			JToolBarHelper::deleteListX('', 'user.delete');
		}
	}	
	protected function setDocument(){
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_FWUSER_USER_TITLE'));
	}
}