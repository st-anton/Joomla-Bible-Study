<?php

/**
 * @version     $Id
 * @package     com_biblestudy
 * @license     GNU/GPL
 */
//No Direct Access
defined('_JEXEC') or die();
require_once (JPATH_ADMINISTRATOR  .DS. 'components' .DS. 'com_biblestudy' .DS. 'lib' .DS. 'biblestudy.defines.php');
require_once (JPATH_ADMINISTRATOR  .DS. 'components' .DS. 'com_biblestudy' .DS. 'helpers' .DS. 'biblestudy.php');
jimport('joomla.application.component.view');

class biblestudyViewMessagetypeedit extends JView {

    protected $form;
    protected $item;
    protected $state;
    protected $defaults;

    function display($tpl = null) {
        $this->form = $this->get("Form");
        $this->item = $this->get("Item");
        $this->state = $this->get("State");
        $this->canDo	= BibleStudyHelper::getActions($this->item->id, 'messagetypeedit');
        $this->setLayout("form");
        $this->addToolbar();
        parent::display($tpl);
    }

    protected function addToolbar() {
        $isNew = ($this->item->id < 1);
        $title = $isNew ? JText::_('JBS_CMN_NEW') : JText::_('JBS_CMN_EDIT');
        JToolBarHelper::title(JText::_('JBS_MST_MESSAGE_TYPE_EDIT') . ': <small><small>[' . $title . ']</small></small>', 'folder.png');
        JToolBarHelper::save('messagetypeedit.save');
        if ($isNew)
            JToolBarHelper::cancel('messagetypeedit.cancel', 'JTOOLBAR_CANCEL');
        else {
            JToolBarHelper::apply('messagetypeedit.apply');
            JToolBarHelper::cancel('messagetypeedit.cancel', 'JTOOLBAR_CLOSE');
        }
		JToolBarHelper::divider();
        JToolBarHelper::help('biblestudy', true);
    }

}
?>