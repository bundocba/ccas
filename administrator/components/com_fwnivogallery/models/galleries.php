<?php

defined('_JEXEC') or die;
jimport('joomla.application.component.modellist');

class FwNivoGalleryModelGalleries extends JModelList
{
 
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            //these are fields that can filter or sort
            $config['filter_fields'] = array(
                'id', 'g.id',
                'title', 'g.title',
            	'created_date', 'g.created_date',
            	'ordering', 'g.ordering',
                'published', 'g.published'
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
        $query->from($db->quoteName('#__fw_nivogallery').' AS g');
      
        // Filter by published state
        $state = $this->getState('filter.state');
        if (is_numeric($state)) {
            $query->where('g.published = '.(int) $state);
        } elseif ($state === '') {
            $query->where('(g.published IN (0,1,2))');
        }
           
       
        // Filter the items over the search string if set.
        $search = $this->getState('filter.search');
        if (!empty($search)) 
        {
            if (stripos($search, 'id:') === 0) 
            {
                $query->where('g.id = '.(int) substr($search, 3));
            } 
            else 
            {
                $search = $db->Quote('%'.$db->escape($search, true).'%');
                $query->where(
                    '('.$db->quoteName('g.title').' LIKE '.$search .')'
                );
            }
        }
      
           
        // Add the list ordering clause.
        $orderCol    = $this->state->get('list.ordering', 'title');
        $orderDirn    = $this->state->get('list.direction', 'ASC');
         
        $query->order($db->escape($orderCol.' '.$orderDirn)); 
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
        $this->setState('filter.state', $state);

        // Load the parameters.
        $params = JComponentHelper::getParams('com_fwnivogallery');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('g.title', 'asc');
    }

    protected function getStoreId($id = '')
    {
        // Compile the store id.
        $id    .= ':'.$this->getState('filter.search');
        $id    .= ':'.$this->getState('filter.state');

        return parent::getStoreId($id);
    }

}