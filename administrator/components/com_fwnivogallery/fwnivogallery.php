<?php
/**
 * @package        Joomla.Administrator
 * @subpackage    com_redirect
 * @copyright    Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 * @since        1.6
 */

// No direct access.
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_fwnivogallery')) {
    return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT.'/helpers/nivogallery.php';
$controller    = JController::getInstance('fwnivogallery');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();