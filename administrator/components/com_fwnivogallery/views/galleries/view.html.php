<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class FwNivoGalleryViewGalleries extends JView
{
    protected $pagination;
    protected $state;
    
    public function display($tpl=null)
    {
        $this->enabled = NivoGalleryHelper::isEnabled();
        
       //ham hanh vi cua lop model no dc ke thua tu lop cha JModle 
       $this->items        = $this->get('Items');
       $this->pagination    = $this->get('Pagination'); 
       $this->state = $this->get('State'); 
 
       // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }
          
       $this->addToolbar(); 
  
       parent::display($tpl);
    }

    /**
    * Add the page title and toolbar.
    *
    * @return  void
    *
    * @since   1.6
    */
    protected function addToolbar()
    {
        JToolBarHelper::title(JText::_('NivoGallery Manager: Galleries'));
        $state    = $this->get('State');
        $canDo    = NivoGalleryHelper::getActions();
   
        if ($canDo->get('core.create')) {
            JToolBarHelper::addNew('gallery.add');
        }
        if ($canDo->get('core.edit')) {
            JToolBarHelper::editList('gallery.edit');
        }
        if ($canDo->get('core.edit.state')) {
            if ($state->get('filter.state') != 2){
                JToolBarHelper::divider();
                JToolBarHelper::publish('galleries.publish', 'JTOOLBAR_ENABLE', true);
                JToolBarHelper::unpublish('galleries.unpublish', 'JTOOLBAR_DISABLE', true);
            }
        }
     
        if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
            JToolBarHelper::deleteList('', 'galleries.delete', 'JTOOLBAR_EMPTY_TRASH');
            JToolBarHelper::divider();
        } elseif ($canDo->get('core.edit.state')) {
            JToolBarHelper::trash('galleries.trash');
            JToolBarHelper::divider();
        }
        if ($canDo->get('core.admin')) {
            JToolBarHelper::preferences('com_fwnivogallery');
            JToolBarHelper::divider();
        }
        JToolBarHelper::help('JHELP_COMPONENTS_REDIRECT_MANAGER');
    }
}