<?php

/**
 * @version     $Id: message.php 1466 2011-01-31 23:13:03Z bcordis $
 * @package BibleStudy
 * @Copyright (C) 2007 - 2011 Joomla Bible Study Team All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.JoomlaBibleStudy.org
 **/

//No Direct Access
defined('_JEXEC') or die;

    jimport('joomla.application.component.controllerform');

    abstract class controllerClass extends JControllerForm {

    }

class biblestudyControllermessage extends controllerClass {
    
    	/**
	 * @since	1.6
	 */
	protected $view_item = 'message';

	/**
	 * @since	1.6
	 */
	protected $view_list = 'messages';
    	/**
	 * Function that allows child controller access to model data after the data has been saved.
	 *
	 * @param	JModel	$model		The data model object.
	 * @param	array	$validData	The validated data. Changed to $data in JBS
	 *
	 * @return	void
	 * @since	1.6
	 */
	protected function postSaveHook(JModel &$model, $data)
	{
		$task = $this->getTask();

		if ($task == 'save') {
		  $id = JRequest::getInt('a_id'); 
          //this will be null if new
          if (empty($id))
          {
            $db = JFactory::getDBO();
            $query = 'SELECT id FROM #__bsms_studies ORDER BY id DESC LIMIT 1';
            $db->setQuery($query);
            $db->query();
            $pks = $db->loadResult();
            if ($this->setTopics($pks, $data))
            {
                $msg = JText::_('JSUCCESS');
                $this->setRedirect('index.php?option=com_biblestudy&view=messages', $msg);
            }


          }
		 // dump ($id);
		//  $this->setTopics($id = JRequest::getInt('id', 0), $data);
		//	$this->setRedirect(JRoute::_('index.php?option=com_content&view=category&id='.$validData['catid'], false));
		}
	}
/**
	 * Method to cancel an edit.
	 *
	 * @param	string	$key	The name of the primary key of the URL variable.
	 *
	 * @return	Boolean	True if access level checks pass, false otherwise.
	 * @since	1.6
	 */
	public function cancel($key = 'a_id')
	{
		parent::cancel($key);
	}

     /**
     * Routine to save the topics(tags)
     * @param type $data from post
     * @param type $pks is the id of the record being saved
     * @since 7.0.2
     * @todo This may need to be optimized
     */
    public function setTopics($pks, $data )
    {
       // dump ($pks);
        if (empty($pks)) {
			$this->setError(JText::_('JBS_STY_ERROR_TOPICS_UPDATE'));
			return false;
		}

            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            //Clear the tags first
            $query->delete();
            $query->from('#__bsms_studytopics');
            $query->where('study_id = '.$pks);
            $db->setQuery($query->__toString());
            if (!$db->query())
                    {
			            throw new Exception($db->getErrorMsg());
		            }
            $query->clear();

            //Add all the tags back
            if ($data['topics'])
            {
                $topics = explode(",", $data['topics']);
                $topics_sql = array();
                foreach ($topics as $topic)
                $topics_sql[] = '('.$topic.', '.  $pks.')';
                $query->insert('#__bsms_studytopics (topic_id, study_id) VALUES '.  implode(',', $topics_sql));
                $db->setQuery($query->__toString());
                if (!$db->query())
                    {
			            throw new Exception($db->getErrorMsg());
		            }
            }

    }


    public function resetHits()
    {
        $msg = null;
        $id = JRequest::getInt('a_id', 0, 'post');
        $db = JFactory::getDBO();
        $db->setQuery("UPDATE #__bsms_studies SET hits='0' WHERE id = " . $id);
        $reset = $db->query();
        if ($db->getErrorNum() > 0) {
            $error = $db->getErrorMsg();
            $msg = JText::_('JBS_CMN_ERROR_RESETTING_HITS') . ' ' . $error;
            $this->setRedirect('index.php?option=com_biblestudy&view=studiesedit&controller=admin&layout=form&cid[]=' . $id, $msg);
        } else {
            $updated = $db->getAffectedRows();
            $msg = JText::_('JBS_CMN_RESET_SUCCESSFUL') . ' ' . $updated . ' ' . JText::_('JBS_CMN_ROWS_RESET');
            $this->setRedirect('index.php?option=com_biblestudy&view=studiesedit&controller=studiesedit&layout=form&cid[]=' . $id, $msg);
        }
}

/**
	 * Method to edit an existing record.
	 *
	 * @param	string	$key	The name of the primary key of the URL variable.
	 * @param	string	$urlVar	The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return	Boolean	True if access level check and checkout passes, false otherwise.
	 * @since	1.6
	 */
	public function edit($key = null, $urlVar = 'a_id')
	{
		$result = parent::edit($key, $urlVar);
		return $result;
	}

        	/**
	 * Method to save a record.
	 *
	 * @param	string	$key	The name of the primary key of the URL variable.
	 * @param	string	$urlVar	The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return	Boolean	True if successful, false otherwise.
	 * @since	1.6
	 */
	public function save($key = null, $urlVar = 'a_id')
	{
	
            $result = parent::save($key, $urlVar); 
            return $result;
	}
        
        	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param	string	$name	The model name. Optional.
	 * @param	string	$prefix	The class prefix. Optional.
	 * @param	array	$config	Configuration array for model. Optional.
	 *
	 * @return	object	The model.
	 *
	 * @since	1.5
	 */
	public function getModel($name = 'message', $prefix = 'biblestudyModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}
}