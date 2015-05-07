<?php

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class FwNivoGalleryController extends JController
{  

    protected $default_view = 'galleries';


    public function display($cachable = false, $urlparams = false)
    {

        $view        = JRequest::getCmd('view', 'nivogallery');
        $layout     = JRequest::getCmd('layout', 'default');
        $id            = JRequest::getInt('id');


        parent::display();
    }
}