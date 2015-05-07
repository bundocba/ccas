<?php

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');


class FwNivoGalleryModelGallery extends JModelAdmin
{

    public function getTable($type = 'Gallery', $prefix = 'FwNivoGalleryTable', $config = array()) 
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = array(), $loadData = true) 
    {
        // Get the form.
        $form = $this->loadForm('com_fwnivogallery.category', 'gallery',
                                array('control' => 'jform', 'load_data' => $loadData));
                                
        if (empty($form)) 
        {
            return false;
        }
        return $form;
    }

    protected function loadFormData() 
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_fwnivogallery.edit.gallery.data', array());
        if (empty($data)) 
        {
            $data = $this->getItem();
        }
        return $data;
    }

	
}

