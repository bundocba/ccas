<?php
defined('_JEXEC') or die;

class FwNivoGalleryTableGallery extends JTable
{

    public function __construct(&$db)
    {
        parent::__construct('#__fw_nivogallery', 'id', $db);
    }

    public function check()
    {
        return true;
    }

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
