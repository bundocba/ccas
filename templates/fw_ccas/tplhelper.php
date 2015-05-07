<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class tplhelper{
	var $template = '';
	function tplhelper($template){
		$this->template = $template;
	}
	function getClass(){
		$class = '';
		/* No Left Module */
		if(!$this->template->countModules('left') && !$this->template->countModules('right')) {
			$class= '_nlnr';//no left module, no right module
		}
		else if(!$this->template->countModules('left')) {
			$class = '_nl';//no left module
		} else if (!$this->template->countModules('right')) {
			$class= '_nr';//no right module
		}
		
		return $class;
	}
		
	function isFrontPage(){
		$view = JRequest::getVar('view');
		return ($view == 'featured')?true:false;
	}
	
	function getPageTitle() {
		$app =& JFactory::getDocument();
		return $app->getTitle();
	}
	
	function getPageClass() {
		$menus   = &JSite::getMenu();
		$menu   = $menus->getActive();

		if (is_object( $menu )) $params = new JParameter( $menu->params );
		$pageclass = $params->get( 'pageclass_sfx' );
	}
}