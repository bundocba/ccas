<?php
// No direct access.
defined('_JEXEC') or die;

class NivoGalleryHelper
{
	public static $extension = 'com_fwnivogallery';
	
	public static function getActions()
    {
        $user        = JFactory::getUser();
        $result        = new JObject;
        $assetName    = 'com_fwnivogallery';

        $actions = JAccess::getActions($assetName);//get info from access.xml

        foreach ($actions as $action) {
            $result->set($action->name,    $user->authorise($action->name, $assetName));
        }

        return $result;
    }


    public static function publishedOptions()
    {
        // Build the active state filter options.
        $options    = array();
        $options[]    = JHtml::_('select.option', '*', 'JALL');
        $options[]    = JHtml::_('select.option', '1', 'JENABLED');
        $options[]    = JHtml::_('select.option', '0', 'JDISABLED');
        $options[]    = JHtml::_('select.option', '-2', 'JTRASHED');

        return $options;
    }
    
    public static function isEnabled()
    {
        $db = JFactory::getDbo();
        $db->setQuery(
            'SELECT enabled' .
            ' FROM #__extensions' .
            ' WHERE folder = '.$db->quote('system').
            '  AND element = '.$db->quote('fwcarservicing')
        );
        $result = (boolean) $db->loadResult();
        if ($error = $db->getErrorMsg()) {
            JError::raiseWarning(500, $error);
        }
        return $result;
    }
    
}