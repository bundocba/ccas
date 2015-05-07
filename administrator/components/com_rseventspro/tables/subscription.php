<?php
/**
* @version 1.0.0
* @package RSEvents!Pro 1.0.0
* @copyright (C) 2011 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class rseventsproTableSubscription extends JTable
{
	/**
	 * @param	JDatabase	A database connector object
	 */
	public function __construct($db) {
		parent::__construct('#__rseventspro_users', 'id', $db);
	}
	
	/**
	 * Overloaded check function
	 *
	 * @return  boolean  True on success, false on failure
	 *
	 * @see     JTable::check
	 * @since   11.1
	 */
	public function check() {
		if (!$this->id) {
			$this->date = JFactory::getDate()->toSql();
			$this->verification = md5($this->ide.$this->name);
			$this->ip = $_SERVER['REMOTE_ADDR'];
			$this->lang = JFactory::getLanguage()->getTag();
		}
		
		return true;
	}
	
	/**
	 * Method to delete a node and, optionally, its child nodes from the table.
	 *
	 * @param   integer  $pk        The primary key of the node to delete.
	 * @param   boolean  $children  True to delete child nodes, false to move them up a level.
	 *
	 * @return  boolean  True on success.
	 *
	 * @see     http://docs.joomla.org/JTable/delete
	 * @since   2.5
	 */
	public function delete($pk = null, $children = false) {
		$db		= JFactory::getDBO();
		$query	= $db->getQuery(true);
		
		// Remove the history
		$query->clear();
		$query->delete();
		$query->from($db->quoteName('#__rseventspro_user_tickets'));
		$query->where($db->quoteName('ids').' = '.$db->quote($pk));
		
		$db->setQuery($query);
		$db->execute();
		
		
		$query->clear()
			->select($db->qn('e.id'))->select($db->qn('e.sync'))->select($db->qn('u.SubmissionId'))
			->from($db->qn('#__rseventspro_users','u'))
			->join('left', $db->qn('#__rseventspro_events','e').' ON '.$db->qn('e.id').' = '.$db->qn('u.ide'))
			->where($db->qn('u.id').' = '.$db->quote($pk));
		
		$db->setQuery($query);
		$subscription = $db->loadObject();
		
		// Delete RSForm!Pro submission
		if (file_exists(JPATH_SITE.'/components/com_rsform/rsform.php') && $subscription->sync) {
			$query->clear()
				->delete()
				->from($db->qn('#__rsform_submission_values'))
				->where($db->qn('SubmissionId').' = '.(int) $subscription->SubmissionId);
			
			$db->setQuery($query);
			$db->execute();
			
			$query->clear()
				->delete()
				->from($db->qn('#__rsform_submissions'))
				->where($db->qn('SubmissionId').' = '.(int) $subscription->SubmissionId);
			
			$db->setQuery($query);
			$db->execute();
		}
		
		
		return parent::delete($pk, $children);
	}
}