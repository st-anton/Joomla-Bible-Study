<?php

/**
 * @version $Id: landingpage.php 1 $
 * @package BibleStudy
 * @Copyright (C) 2007 - 2011 Joomla Bible Study Team All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.JoomlaBibleStudy.org
 * */
//No Direct Access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');
include_once (JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'translated.php');



class biblestudyModellandingpage extends JModelList {

    /**
     * @since   7.0
     */
    protected function populateState($ordering=null, $direction=null) {
        $order = $this->getUserStateFromRequest($this->context . '.filter.order', 'filter_orders');
        $this->setState('filter.order', $order);

        parent::populateState('s.studydate', 'DESC');
    }
   
     protected function getListQuery() {
         $db = $this->getDbo();
         $query = $db->getQuery(true);
         $template_params = $this->getTemplate();
         $registry = new JRegistry;
         $registry->loadJSON($template_params->params);
         $t_params = $registry;
         // Load the parameters. Merge Global and Menu Item params into new object
         $app = JFactory::getApplication('site');
         $params = $app->getParams();
         $menuparams = new JRegistry;

        if ($menu = $app->getMenu()->getActive()) {
            $menuparams->loadString($menu->params);
        }
         $query->select('list.select','s.id');
         $query->from('#__bsms_studies as s');
         $query->select('t.id as tid, t.teachername, t.title as teachertitle');
         $query->join('LEFT','#__bsms_teachers as t on s.teacher_id = t.id');
         $query->select('se.id as sid, se.series_text, se.description as sdescription, se.series_thumbnail');
         $query->join('LEFT','#__bsms_series as se on s.series_id = se.id');
         $query->select('m.id as mid, m.message_type');
         $query->join('LEFT','#__bsms_message_type as m on s.messagetype = m.id');
         $query->select('GROUP_CONCAT(DISTINCT st.topic_id)');
         $query->join('LEFT', '#__bsms_studytopics AS st ON s.id = st.study_id');
         $query->select('GROUP_CONCAT(DISTINCT tp.id), GROUP_CONCAT(DISTINCT tp.topic_text) as topics_text, GROUP_CONCAT(DISTINCT tp.params)');
         $query->join('LEFT', '#__bsms_topics AS tp ON tp.id = st.topic_id');
         $query->select('l.id as lid, l.location_text');
         $query->join('LEFT','#__bsms_locations as l on s.location_id = l.id');
         $rightnow = date('Y-m-d H:i:s');
         $query->where('s.published = 1');
         $query->where("date_format(s.studydate, %Y-%m-%d %T') <= " . (int) $rightnow);
         //Order by order filter
        $orderparam = $params->get('default_order');
        if (empty($orderparam)) {
            $orderparam = $t_params->get('default_order', '1');
        }
        if ($orderparam == 2) {
            $order = "ASC";
        } else {
            $order = "DESC";
        }
        $orderstate = $this->getState('filter.order');
        if (!empty($orderstate))
            $order = $orderstate;

        $query->order('studydate ' . $order);
        return $query;
     }

   

    /**
     * @desc Returns teachers
     * @return Array
     */
    function getAdmin() {
        if (empty($this->_admin)) {
            $query = 'SELECT *'
                    . ' FROM #__bsms_admin'
                    . ' WHERE id = 1';
            $this->_admin = $this->_getList($query);
        }
        return $this->_admin;
    }

    function getTemplate() {
        if (empty($this->_template)) {
            $templateid = JRequest::getVar('t', 1, 'get', 'int');
            $query = 'SELECT *'
                    . ' FROM #__bsms_templates'
                    . ' WHERE published = 1 AND id = ' . $templateid;
            $this->_template = $this->_getList($query);
        }
        return $this->_template;
    }

   

   
   

}