<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');  
jimport('joomla.application.component.modellist');

class FwEventModelListEvent extends JModelList
{
	protected $_extension = 'com_fwevent';
	private $limit=7;
	var $_data = null;
	var $_total = null;
	var $_pagination = null;

	function __construct()
    {
        parent::__construct();
        $menuitemid = JRequest::getInt( 'Itemid' );
        $menuparams="";
        if ($menuitemid)
        {
            $menu = JSite::getMenu();
            $menuparams = $menu->getParams( $menuitemid );
            
            $quantity = $menuparams->get("quantity",$this->limit);
        }
        else
        	$quantity = $this->limit;
			
        //Get configuration
        $app = JFactory::getApplication();
        $config = JFactory::getConfig();

        $limit = JRequest::getVar('limit', 7, '', 'int');
		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);

    }

	function getData() {
		if (empty($this->_data)) {
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}
		return $this->_data;
	}

	function getTotal() {
		if (empty($this->_total)) {
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}
		return $this->_total;
	}

	function getPagination() {
		if (empty($this->_pagination)) {
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}
		return $this->_pagination;
	}

	function _buildQuery(){
		$query="select * from #__fwevent where published = 1 order by start_date desc ";
		return $query;
	}
	
	
		
   /*  public function getList()
    {

		
    	$view = JRequest::getVar('layout','upcoming');
         // Initialise variables.

		$db= &JFactory::getDBO();
		$sql="select * from #__fwevent where published = 1 ";
		$db->setQuery($sql);
		$list= $db->loadObjectList();
		return $list;
    } */
    
	
	



	protected function populateState()
	{
		$app = JFactory::getApplication();
		$this->setState('filter.extension', $this->_extension);

		// Get the parent id if defined.
		$parentId = JRequest::getInt('id');
		$this->setState('filter.parentId', $parentId);

		$params = $app->getParams();
		$this->setState('params', $params);

		$this->setState('filter.published',	1);
		$this->setState('filter.access',	true);

        // List state information
        $value = $this->limit;
        $this->setState('list.limit', $value);

        $value = JRequest::getUInt('limitstart', 0);
        $this->setState('list.start', $value);
	}
}