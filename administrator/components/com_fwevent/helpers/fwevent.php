<?php
/**
 * @copyright    Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Redirect component helper.
 *
 * @package        Joomla.Administrator
 * @subpackage    com_book
 * @since        1.6
 */
class FweventHelper
{
    public static $extension = 'com_fwevent';

    /**
     * Configure the Linkbar.
     *
     * @param    string    The name of the active view.
     */
     /*


    /**
     * Gets a list of the actions that can be performed.
     *
     * @return    JObject
     */
    public static function getActions()
    {
        $user        = JFactory::getUser();
        $result        = new JObject;
        $assetName    = 'com_fwevent';

        $actions = JAccess::getActions($assetName);//get info from access.xml
//echo "<pre>"; print_r($actions);
        foreach ($actions as $action) {
            $result->set($action->name,    $user->authorise($action->name, $assetName));
        }

        return $result;
    }

    /**
     * Returns an array of standard published state filter options.
     *
     * @return    string            The HTML code for the select tag
     */
    public static function publishedOptions()
    {
        // Build the active state filter options.
        $options    = array();
        $options[]    = JHtml::_('select.option', '*', 'JALL');
        $options[]    = JHtml::_('select.option', '1', 'JENABLED');
        $options[]    = JHtml::_('select.option', '0', 'JDISABLED');
        //$options[]    = JHtml::_('select.option', '2', 'JARCHIVED');
        $options[]    = JHtml::_('select.option', '-2', 'JTRASHED');

        return $options;
    }

    /**
     * Determines if the plugin for book to work is enabled.
     *
     * @return    boolean
     *//* 
    public static function isEnabled()
    {
        $db = JFactory::getDbo();
        $db->setQuery(
            'SELECT enabled' .
            ' FROM #__extensions' .
            ' WHERE folder = '.$db->quote('system').
            '  AND element = '.$db->quote('fwevent')
        );
        $result = (boolean) $db->loadResult();
        if ($error = $db->getErrorMsg()) {
            JError::raiseWarning(500, $error);
        }
        return $result;
    } */
}
