<?php
/**
* @version 1.0.0
* @package RSEvents!Pro 1.0.0
* @copyright (C) 2011 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined( '_JEXEC' ) or die( 'Restricted access' ); 
jimport( 'joomla.application.component.view');

class rseventsproViewSubscription extends JViewLegacy
{
	protected $form;
	protected $item;
	protected $params;
	protected $fields;
	protected $tickets;
	
	public function display($tpl = null) {		
		$this->form 		= $this->get('Form');
		$this->item 		= $this->get('Item');
		$this->fields 		= $this->get('Fields');
		$this->tickets 		= $this->get('Tickets');
		$this->params		= $this->item->gateway == 'offline' ? $this->get('Card') : $this->item->params;
		
		$this->addToolBar();
		parent::display($tpl);
	}
	
	protected function addToolBar() {
		JToolBarHelper::title(JText::_('COM_RSEVENTSPRO_ADD_EDIT_SUBSCRIPTION'),'rseventspro48');
		JToolBarHelper::apply('subscription.apply');
		JToolBarHelper::save('subscription.save');
		JToolBarHelper::save2new('subscription.save2new');
		JToolBarHelper::cancel('subscription.cancel');
	}
	
	protected function getEvent($id) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->clear()
			->select($db->qn('id'))->select($db->qn('name'))->select($db->qn('allday'))
			->select($db->qn('start'))->select($db->qn('end'))
			->select($db->qn('ticket_pdf'))->select($db->qn('ticket_pdf_layout'))
			->from($db->qn('#__rseventspro_events'))
			->where($db->qn('id').' = '.(int) $id);
		
		$db->setQuery($query);
		return $db->loadObject();
		
	}
}