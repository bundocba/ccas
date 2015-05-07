<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// Get an instance of the controller prefixed by FwEvent
$controller = JControllerLegacy::getInstance('FwEvent');

$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
