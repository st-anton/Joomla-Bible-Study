<?php

/**
 * JView html
 * @package BibleStudy.Admin
 * @copyright (C) 2007 - 2011 Joomla Bible Study Team All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.JoomlaBibleStudy.org
 * */
// No Direct Access
defined('_JEXEC') or die;



/**
 * View class for Templates
 * @package BibleStudy.Admin
 * @since 7.0.0
 */
class BiblestudyViewTemplates extends JViewLegacy {

    /**
     * Items
     * @var array
     */
    protected $items;

    /**
     * Pagination
     * @var array
     */
    protected $pagination;

    /**
     * State
     * @var array
     */
    protected $state;

    /**
     * Execute and display a template script.
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  mixed  A string if successful, otherwise a JError object.
     *
     * @see     fetch()
     * @since   11.1
     */
    public function display($tpl = null) {


        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');
        $this->canDo = JBSMHelper::getActions('', 'template');
        $templates = $this->get('templates');
        $types[] = JHTML::_('select.option', '0', JTEXT::_('JBS_CMN_SELECT_TEMPLATE'));
        $types = array_merge($types, $templates);
        $this->templates = JHTML::_('select.genericlist', $types, 'template_export', 'class="inputbox" size="1" ' , 'value', 'text', "$");

        // Levels filter.
        $options = array();
        $options[] = JHtml::_('select.option', '1', JText::_('J1'));
        $options[] = JHtml::_('select.option', '2', JText::_('J2'));
        $options[] = JHtml::_('select.option', '3', JText::_('J3'));
        $options[] = JHtml::_('select.option', '4', JText::_('J4'));
        $options[] = JHtml::_('select.option', '5', JText::_('J5'));
        $options[] = JHtml::_('select.option', '6', JText::_('J6'));
        $options[] = JHtml::_('select.option', '7', JText::_('J7'));
        $options[] = JHtml::_('select.option', '8', JText::_('J8'));
        $options[] = JHtml::_('select.option', '9', JText::_('J9'));
        $options[] = JHtml::_('select.option', '10', JText::_('J10'));

        $this->f_levels = $options;
        
        if (BIBLESTUDY_CHECKREL)
                {$this->sidebar = JHtmlSidebar::render();}
        // Set the toolbar
        $this->addToolbar();
        $bar = JToolBar::getInstance('toolbar');
        $url = JRoute::_('index.php?option=com_biblestudy&view=templates&layout=default_export');
        $bar->appendButton('Link','export', 'JBS_TPL_IMPORT_EXPORT_TEMPLATE', $url);

        // Display the template
        parent::display($tpl);

        // Set the document
        $this->setDocument();
    }

    /**
     * Add Toolbar
     * @since 7.0.0
     */
    protected function addToolbar() {
        JToolBarHelper::title(JText::_('JBS_CMN_TEMPLATES'), 'templates.png');
        if ($this->canDo->get('core.create')) {
            JToolBarHelper::addNew('template.add');
        }
        if ($this->canDo->get('core.edit')) {
            JToolBarHelper::editList('template.edit');
          //  JToolBarHelper::custom( $task = 'template.template_export ', $icon = 'download.png', $iconOver = 'JBS_TPL_EXPORT_TEMPLATE', $alt = 'JBS_TPL_EXPORT_TEMPLATE', $listSelect = true, $x = false );
           // JToolBarHelper::custom( $task = 'template.template_import ', $icon = 'upload.png', $iconOver = 'JBS_TPL_IMPORT_TEMPLATE', $alt = 'JBS_TPL_IMPORT_TEMPLATE', $listSelect = false, $x = false );
        }
        if ($this->canDo->get('core.edit.state')) {
            JToolBarHelper::divider();
            JToolBarHelper::publishList('templates.publish');
            JToolBarHelper::unpublishList('templates.unpublish');
        }
        if ($this->state->get('filter.published') == -2 && $this->canDo->get('core.delete')) {
            JToolBarHelper::deleteList('', 'templates.delete', 'JTOOLBAR_EMPTY_TRASH');
        } elseif ($this->canDo->get('core.delete')) {
            JToolBarHelper::trash('templates.trash');
        }
        JToolBarHelper::divider();
    }

    /**
     * Add the page title to browser.
     *
     * @since	7.1.0
     */
    protected function setDocument() {
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('JBS_TITLE_TEMPLATES'));
    }

/**
     * Returns an array of fields the table can be sorted by
     *
     * @return  array  Array containing the field name to sort by as the key and display text as value
     *
     * @since   3.0
     */
    protected function getSortFields() {
        return array(
            'template.title' => JText::_('JBS_TPL_TEMPLATE_ID'),
            'template.published' => JText::_('JSTATUS'),
            'template.id' => JText::_('JGRID_HEADING_ID')
        );
    }

}