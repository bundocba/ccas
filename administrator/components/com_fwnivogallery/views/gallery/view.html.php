<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class FwNivoGalleryViewGallery extends JView
{
    protected $form;
    protected $item;
    protected $state;

    /**
     * Display the view
     */
    public function display($tpl = null)
    {                            
        // Initialiase variables.
        $model = $this->getModel();
        $this->form        = $this->get('Form');
        $this->item        = $this->get('Item');
        $this->state    = $this->get('State');
         
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

        $this->addToolbar();
        parent::display($tpl);
    }


    protected function addToolbar()
    {
        JRequest::setVar('hidemainmenu', true);

        $user        = JFactory::getUser();
        $isNew       = ($this->item->id == 0);
        $canDo       = NivoGalleryHelper::getActions();

        JToolBarHelper::title("Nivo Gallery Manager: Item");

        // If not checked out, can save the item.
        if ($canDo->get('core.edit')) {
            JToolBarHelper::apply('gallery.apply');
            JToolBarHelper::save('gallery.save');
        	if ($canDo->get('core.create')) {
				JToolBarHelper::save2new('gallery.save2new');
			}
        }

        if (empty($this->item->id)) {
            JToolBarHelper::cancel('gallery.cancel');
        } else {
            JToolBarHelper::cancel('gallery.cancel', 'JTOOLBAR_CLOSE');
        }
    }
}
