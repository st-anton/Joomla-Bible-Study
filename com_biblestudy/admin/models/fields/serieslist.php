<?php

/**
 * Series list Field
 * @package BibleStudy.Admin
 * @copyright (C) 2007 - 2011 Joomla Bible Study Team All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.JoomlaBibleStudy.org
 * */
// No Direct Access
defined('_JEXEC') or die;

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * Series List Form Field class for the Joomla Bible Study component
 * @package BibleStudy.Admin
 * @since 7.0.4
 */
class JFormFieldSerieslist extends JFormFieldList {

    /**
     * The field type.
     *
     * @var         string
     */
    protected $type = 'Series';

    /**
     * Method to get a list of options for a list input.
     *
     * @return      array           An array of JHtml options.
     */
    protected function getOptions() {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('id,series_text');
        $query->from('#__bsms_series');
        $db->setQuery((string) $query);
        $messages = $db->loadObjectList();
        $options = array();
        if ($messages) {
            foreach ($messages as $message) {
                $options[] = JHtml::_('select.option', $message->id, $message->series_text);
            }
        }
        $options = array_merge(parent::getOptions(), $options);
        return $options;
    }

}