<?php

/**
 * @version		$Id: helloworld.php 46 2010-11-21 17:27:33Z chdemko $
 * @package		Joomla16.Tutorials
 * @subpackage	Components
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @author		Christophe Demko
 * @link		http://joomlacode.org/gf/project/helloworld_1_6/
 * @license		License GNU General Public License version 2 or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * Hello Table class
 */
class FweventTableFwevent extends JTable
{
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db) 
	{
		parent::__construct('#__fwevent', 'id', $db);
	}
	public function check() {/* 
        print_r($_FILES["file"]); */
        /* echo "<pre>";print_r($_POST); */
		ini_set("display_errors",1);
    	$start = strtotime($this->start_date);					
		/* echo $start ;		echo '<br/>';		 */
    	$end = strtotime($this->end_date);				
		/* echo $end ;  */

    	if($end <= $start)
    	{
    		$this->setError("End date have to >= start date");
    		return false;
    	}	
    
    	// Set name
		$this->title = htmlspecialchars_decode($this->title, ENT_QUOTES);

		// Set alias
		$this->alias = JApplication::stringURLSafe($this->alias);
		if (empty($this->alias)) {
			$this->alias = JApplication::stringURLSafe($this->title);
		}
		
		$result = $this->uploadfile();
        if(!$result) return $result;		
		
		$description =  stripcslashes($_POST['jform']['description']);
    	$pattern = '#<hr\s+id=("|\')system-readmore("|\')\s*\/*>#i'; 
		$tagPos = preg_match($pattern, $description);
    
		if ($tagPos == 0)
		{
			$this->introtext = $description;
			$this->fulltext = '';
		}
		else
		{
			list($this->introtext, $this->fulltext) = preg_split($pattern, $description, 2);
		}

        return true;
    }

	protected function uploadfile()
    {
        if(!$_FILES["file"]['size'])
            return true;
 

        $allowedExts = array("docx","doc","pdf");
        $extension = end(explode(".", $_FILES["file"]["name"]));
        if( in_array($extension, $allowedExts))
        {
            /* $filename = "images/event_doc/".date("Y_m_d_H_i").".".$extension; */            $filename = "images/event_doc/".$_FILES["file"]["name"];
            $path = JPATH_ROOT."/".$filename;
            $this->file=$filename;
            $result = JFile::upload($_FILES["file"]['tmp_name'],$path);
            return true;
        }
        else
        {
            $this->setError(JText::_('File upload must have extendtion is docx'));
            return false;
        }
    }
/* 	
    public function store($updateNulls = false)
    {
        // Initialise variables.
    	$date = JFactory::getDate()->toSql();


        $result = parent::store($updateNulls);

        return $result;
    } */
	
    public function store($updateNulls = false)
    {
        // Initialise variables.
    	$date = JFactory::getDate()->toSql();

        if (!$this->id) {
            $this->created_date = $date;
        }
        $result = parent::store($updateNulls);

        return $result;
    }
}
