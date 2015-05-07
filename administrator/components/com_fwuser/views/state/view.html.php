<?php defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');
class fwuserViewstate extends JView{
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
		fwuserHelper::addSubmenu('state');
		JToolBarHelper::title(JText::_('COM_FWUSER_STATE_TITLE'), 'fwuser');
		
		$document = JFactory::getDocument();
		$document->addStyleDeclaration('.icon-48-fwuser {background-image: url(../administrator/components/com_fwuser/images/icon-48-state.png);}');
		
		$canDo = fwuserHelper::getActions();
		if ($canDo->get('core.create')){
			JToolBarHelper::addNewX('stateedit.add');
		}
		if ($canDo->get('core.edit')){
			JToolBarHelper::editListX('stateedit.edit');
		}
		if ($canDo->get('core.delete')){
			JToolBarHelper::deleteListX('', 'state.delete');
		}
	}	
	protected function setDocument(){
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_FWUSER_STATE_TITLE'));
	}
}