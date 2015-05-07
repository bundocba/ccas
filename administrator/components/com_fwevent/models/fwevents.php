<?php

/**
 * @version		$Id: helloworlds.php 46 2010-11-21 17:27:33Z chdemko $
 * @package		Joomla16.Tutorials
 * @subpackage	Components
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @author		Christophe Demko
 * @link		http://joomlacode.org/gf/project/helloworld_1_6/
 * @license		License GNU General Public License version 2 or later
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import the Joomla modellist library
jimport('joomla.application.component.modellist');

/**
 * HelloWorlds Model
 */
class FweventModelFwevents extends JModelList
{
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return	string	An SQL query
	 */
	   public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            //these are fields that can filter or sort
            $config['filter_fields'] = array(
                'id', 'e.id',
                'title', 'e.title',
            	'price1', 'e.price1',
            	'price2', 'e.price2',
            	'venue', 'e.venue',
            	'created_date', 'e.created_date',
            	'ordering', 'e.ordering',
                'published', 'e.published'
            );
        }

        parent::__construct($config);
    }

  protected function getListQuery()
    {
        // Initialise variables.
        $db        = $this->getDbo();
        $query    = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select("*");
        $query->from($db->quoteName('#__fwevent').' AS e');
      
        // Filter by published state
        $state = $this->getState('filter.state');
        if (is_numeric($state)) {
            $query->where('e.published = '.(int) $state);
        } elseif ($state === '') {
            $query->where('(e.published IN (0,1,2))');
        }		 /* $query->where('order by e.created_date'); */
           
       
        // Filter the items over the search string if set.
		$search = $this->getState('filter.search');		if (!empty($search)) {			if (stripos($search, 'id:') === 0) {				$query->where('e.id = '.(int) substr($search, 3));			} else {				$search = $db->Quote('%'.$db->escape($search, true).'%');				$query->where('(e.title LIKE '.$search.')');			}		}
		           
        // Add the list ordering clause.
        $orderCol    = $this->state->get('list.ordering', 'start_date');
        $orderDirn    = $this->state->get('list.direction', 'desc');
         
        $query->order($db->escape($orderCol.' '.$orderDirn)); 		//echo $query;		
        return $query;
    }

	  
    protected function populateState($ordering = null, $direction = null)
    {
        // Initialise variables.
        $app = JFactory::getApplication('administrator');

        // Load the filter state.
        $search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
		
        $state = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_state', '', 'string');		
        $this->setState('filter.state', $state);		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');		$this->setState('filter.search', $search);

        // Load the parameters.
        $params = JComponentHelper::getParams('com_fwevent');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('e.start_date', 'desc');
    }

  
}
