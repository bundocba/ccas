<?php
/**
* @version 1.0.0
* @package RSEvents!Pro 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

class rseventsproControllerEvents extends JControllerAdmin
{
	protected $text_prefix = 'COM_RSEVENTSPRO_EVENTS';
	
	/**
	 * Constructor.
	 *
	 * @param	array	$config	An optional associative array of configuration settings.

	 * @return	rseventsproControllerGroups
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array()) {
		parent::__construct($config);
	}
	
	/**
	 * Proxy for getModel.
	 *
	 * @param	string	$name	The name of the model.
	 * @param	string	$prefix	The prefix for the PHP class name.
	 *
	 * @return	JModel
	 * @since	1.6
	 */
	public function getModel($name = 'Event', $prefix = 'rseventsproModel', $config = array('ignore_request' => true)) {
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
	
	/**
	 * Method to export events to iCal format.
	 *
	 * @return	.ics file
	 */
	public function exportical() {
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		// Get the selected items
		$pks = JFactory::getApplication()->input->get('cid', array(0), 'array');
		
		$model = $this->getModel();
		
		// Force array elements to be integers
		JArrayHelper::toInteger($pks);
		
		// Export events
		if ($model->exportical($pks)) {
			JFactory::getApplication()->close();
		} else {
			$this->setMessage($model->getError());
			$this->setRedirect('index.php?option=com_rseventspro&view=events');
		}
	}
	
	/**
	 * Method to export events to CSV format.
	 *
	 * @return	.csv file
	 */
	public function exportcsv() {
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		// Get the selected items
		$pks = JFactory::getApplication()->input->get('cid', array(0), 'array');
		
		$model = $this->getModel();
		
		// Force array elements to be integers
		JArrayHelper::toInteger($pks);
		
		// Export events
		if (!$model->exportcsv($pks)) {
			$this->setMessage($model->getError());
			$this->setRedirect('index.php?option=com_rseventspro&view=events');
		}
	}
	
	/**
	 * Method to clear event rating.
	 *
	 * @return	void
	 */
	public function rating() {
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		// Get the selected items
		$pks = JFactory::getApplication()->input->get('cid', array(0), 'array');
		
		$model = $this->getModel();
		
		// Force array elements to be integers
		JArrayHelper::toInteger($pks);
		
		// Export events
		if (!$model->rating($pks)) {
			$this->setMessage($model->getError());
		}
		
		$this->setRedirect('index.php?option=com_rseventspro&view=events');
	}
	
	/**
	 * Method to copy events.
	 *
	 * @return	void
	 */
	public function copy() {
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		// Get the selected items
		$pks = JFactory::getApplication()->input->get('cid', array(0), 'array');
		
		$model = $this->getModel();
		
		// Force array elements to be integers
		JArrayHelper::toInteger($pks);
		
		// Copy events
		if (!$model->copy($pks)) {
			$this->setMessage($model->getError());
		} else {
			$this->setMessage(JText::_('COM_RSEVENTSPRO_EVENTS_COPIED'));
		}
		
		$this->setRedirect('index.php?option=com_rseventspro&view=events');
	}
}