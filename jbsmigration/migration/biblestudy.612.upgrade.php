<?php

/**
 * @version $Id: biblestudy.612.upgrade.php 1 $
 * @package COM_JBSMIGRATION
 * @Copyright (C) 2007 - 2011 Joomla Bible Study Team All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.JoomlaBibleStudy.org
 **/
defined('_JEXEC') or die();
class JBS612Install{
    
function upgrade612()
{

$messages = array();
$query ="UPDATE #__bsms_mediafiles SET params = 'player=2', internal_viewer = '0' WHERE internal_viewer = '1' AND params IS NULL";
$msg = $this->performdb($query);
         if (!$msg)
             {
                $messages[] = '<font color="green">'.JText::_('JBS_EI_QUERY_SUCCESS').': '.$query.' </font><br /><br />';
             } 
             else
             {
                $messages[] = $msg;
             }	

$application->enqueueMessage( ''. JText::_('Upgrading from build 612') .'' ) ;
$results = array('build'=>'612','messages'=>$messages);
    
    return $results;
}

function performdb($query)
    {
        $db = JFactory::getDBO();
        $results = false;
        $db->setQuery($query);
        $db->query();
           		if ($db->getErrorNum() != 0)
					{
						$results = JText::_('JBS_EI_DB_ERROR').': '.$db->getErrorNum()."<br /><font color=\"red\">";
						$results .= $db->stderr(true);
						$results .= "</font>";
                        return $results;
					}
				else
					{
						$results = false; return $results;
					}	
    }
}