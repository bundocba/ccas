<?php
/**
* @version 1.0.0
* @package RSEvents!Pro 1.0.0
* @copyright (C) 2011 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.model' );

class rseventsproModelSubscription extends JModelAdmin
{
	protected $text_prefix = 'COM_RSEVENTSPRO';

	/**
	 * Returns a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 *
	 * @return	JTable	A database object
	*/
	public function getTable($type = 'Subscription', $prefix = 'rseventsproTable', $config = array()) {
		return JTable::getInstance($type, $prefix, $config);
	}
	
	/**
	 * Method to get a single record.
	 *
	 * @param	integer	The id of the primary key.
	 *
	 * @return	mixed	Object on success, false on failure.
	 */
	public function getItem($pk = null) {
		return $item = parent::getItem($pk);
	}
	
	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		Data for the form.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 *
	 * @return	mixed	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true) {
		// Get the form.
		$form = $this->loadForm('com_rseventspro.subscription', 'subscription', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
			return false;
		
		return $form;
	}
	
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData() {
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_rseventspro.edit.subscription.data', array());

		if (empty($data))
			$data = $this->getItem();

		return $data;
	}
	
	/**
	 * Method to toggle the subscriber status.
	 *
	 * @param	array	The ids of the items to toggle.
	 * @param	int		The value to toggle to.
	 *
	 * @return	boolean	True on success.
	 */
	public function status($pks, $value = 0) {
		// Sanitize the ids.
		$pks = (array) $pks;
		JArrayHelper::toInteger($pks);
		
		if (empty($pks)) {
			$this->setError(JText::_('JERROR_NO_ITEMS_SELECTED'));
			return false;
		}
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		foreach ($pks as $pk) {
			$query->clear()
				->select($db->qn('state'))
				->from($db->qn('#__rseventspro_users'))
				->where($db->qn('id').' = '.$pk);
			
			$db->setQuery($query);
			$oldstate = $db->loadResult();
			
			$query->clear()
				->update($db->qn('#__rseventspro_users'))
				->set($db->qn('state').' = '.(int) $value)
				->where($db->qn('id').' = '.$pk);
			
			$db->setQuery($query);
			$db->execute();
			
			// Send activation email
			if ($oldstate != 1 && $value == 1) {
				rseventsproHelper::confirm($pk);
			}
			
			// Send denied email
			if ($oldstate != 2 && $value == 2) {
				rseventsproHelper::denied($pk);
			}
		}
		
		return true;
	}
	
	/**
	 * Method to get Card details.
	 */
	public function getCard() {
		$id = JFactory::getApplication()->input->getInt('id');
		return rseventsproHelper::getCardDetails($id);
	}
	
	/**
	 * Method to get the RSForm!Pro fields.
	 */
	public function getFields() {
		$id = JFactory::getApplication()->input->getInt('id',0);
		return rseventsproHelper::getRSFormData($id);
	}
	
	/**
	 * Method to get all the tickets from events that have registration ON.
	 */
	public function getTickets() {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$active	= rseventsproHelper::getConfig('active_events');
		
		$query->clear()
			->select($db->qn('id','value'))
			->select($db->qn('name','text'))
			->select($db->qn('start'))
			->from($db->qn('#__rseventspro_events'))
			->where($db->qn('registration').' = 1');
		
		if ($active)
			$query->where($db->qn('end').' >= '.$db->q(JFactory::getDate()->toSql()));
		
		$db->setQuery($query);
		$events = $db->loadObjectList();
		
		$tickets[] = JHTML::_('select.option', 0, JText::_('COM_RSEVENTSPRO_SELECT_TICKET'));
		
		if (!empty($events)) {
			foreach($events as $event) {			
				$query->clear()
					->select($db->qn('id'))->select($db->qn('name'))->select($db->qn('price'))
					->from($db->qn('#__rseventspro_tickets'))
					->where($db->qn('ide').' = '.(int) $event->value);
				
				$db->setQuery($query);
				$etickets = $db->loadObjectList();
				
				$tickets[] = JHTML::_('select.optgroup', $event->text.' ('.$event->start.')');
				
				if (!empty($etickets)) {
					foreach($etickets as $ticket) {
						if ($ticket->price > 0)
							$tickets[] = JHTML::_('select.option' , $ticket->id,$ticket->name . ' (' . rseventsproHelper::currency($ticket->price).')');
						else
							$tickets[] = JHTML::_('select.option' , $ticket->id,$ticket->name . ' (' .JText::_('COM_RSEVENTSPRO_GLOBAL_FREE').')');
					}
				} else {
					$tickets[] = JHTML::_('select.option' , 'ev'.$event->value, JText::_('COM_RSEVENTSPRO_SUBSCRIPTION_FREE_ENTRANCE'));
				}
				
				$tickets[] = JHTML::_('select.optgroup', $event->text.' ('.$event->start.')');
			}
		}
		
		return $tickets;
	}
	
	/**
	 * Method to save the form data.
	 *
	 * @param	array	The form data.
	 *
	 * @return	boolean	True on success.
	 * @since	1.6
	 */
	public function save($data) {
		// Initialise variables;
		$table = $this->getTable();
		$db = $table->getDbo();
		$query = $db->getQuery(true);
		$pk = (!empty($data['id'])) ? $data['id'] : (int) $this->getState($this->getName() . '.id');
		$isNew = true;

		// Load the row if saving an existing tag.
		if ($pk > 0) {
			$table->load($pk);
			$isNew = false;
		}

		// Bind the data.
		if (!$table->bind($data)) {
			$this->setError($table->getError());
			return false;
		}

		// Check the data.
		if (!$table->check()) {
			$this->setError($table->getError());
			return false;
		}
		
		$query->clear()
			->select($db->qn('state'))
			->from($db->qn('#__rseventspro_users'))
			->where($db->qn('id').' = '. (int) $pk);
		$db->setQuery($query);
		$state = (int) $db->loadResult();
		
		// Store the data.
		if (!$table->store()) {
			$this->setError($table->getError());
			return false;
		}
		
		if ($isNew) {
			if ($tickets = JFactory::getApplication()->input->get('tickets',array(),'array')) {
			
				JArrayHelper::toInteger($tickets);
				foreach ($tickets as $ticket => $quantity) {
					if (strpos($ticket,'ev') !== false)
						$ticket = 0;
					
					$query->clear()
						->insert($db->qn('#__rseventspro_user_tickets'))
						->set($db->qn('ids').' = '.(int) $table->id)
						->set($db->qn('idt').' = '.(int) $ticket)
						->set($db->qn('quantity').' = '.(int) $quantity);
					
					$db->setQuery($query);
					$db->execute();
				}
			}
			
			// Send registration email
			if (JFactory::getApplication()->input->getInt('registration',0))
				rseventsproHelper::confirm($table->id,true);
			
		}
		
		// Send activation email
		if ($state != 1 && $data['state'] == 1)
			rseventsproHelper::confirm($table->id);
		
		// Send denied email
		if ($state != 2 && $data['state'] == 2)
			rseventsproHelper::denied($table->id);
		
		$this->setState($this->getName() . '.id', $table->id);
		
		return true;
	}
}