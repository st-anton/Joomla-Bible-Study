<?php

/**
 * @author Joomla Bible Study
 * @copyright 2010
 * @desc Updates the media files table to reflect new way of associating podcasts and adds Landing Page CSS
 */
defined( '_JEXEC' ) or die('Restricted access');
 $result_table = '<table><tr><td>This routine adds some items to the css file for the Landing Page view and updates the mediafiles table';
//This updates the mediafiles table to reflect the new way of associating files to podcasts
$db = JFactory::getDBO();
   $query = 'SELECT id, params, podcast_id FROM #__bsms_mediafiles WHERE podcast_id > 0';
   $db->setQuery($query);
   $db->query();
   $num_rows = $db->getNumRows();
   if ($num_rows > 0)
   {
  		$add = 0;
	  	$result_table .= '<tr><td>'.$num_rows.' rows from Media Files Records in need of updating for new podcast association.</td></tr>';
		$results = $db->loadObjectList();
	   foreach ($results as $result)
	   {
	   	
	   	$podcast = 'podcasts='.$result->podcast_id;
	   	$params = $result->params;
	   	$update = $podcast.' '.$params;
	   	$query = "UPDATE #__bsms_mediafiles SET `params` = '".$update."', `podcast_id`='0' WHERE `id` = ".$result->id;
	  	$db->setQuery($query);
	  	$db->query();
	   	if ($db->getErrorNum() > 0)
				{
					$error = $db->getErrorMsg();
					$result_table .= '<tr><td>An error occured while updating mediafiles table: '.$error.'</td></tr>';
				}
			else
			{
				$updated = 0;
				$updated = $db->getAffectedRows(); //echo 'affected: '.$updated;
				$add = $add + $updated;
			} 
		}
	   $result_table .= '<tr><td>'.$add.' Rows in Media Files Records table updated.</td></tr>';
	   
	}

// This adds some css for the Landing Page

$dest = JPATH_SITE.DS.'components/com_biblestudy/assets/css/biblestudy.css';
$landingread = JFile::read($dest);
$landingexists = 1;
	$landingexists = substr_count($landingread,'#landinglist');
	if ($landingexists < 1)
	{
		$landing = '
/* Landing Page Items */
#landinglist {
	
}
#landing_label {
	
}
#landing_item {
	
}
#landing_title {
	
}
#biblestudy_landing {
	
}';
$landingwrite = $landingread.$landing;
			$errcss = '';
			if (!JFile::write($dest, $landingwrite))
			{
				$result_table .= '<tr><td>There was a problem writing to the css file. Please contact customer support on JoomlaBibleStudy.org</td></tr>';
			}
			else
			{
				$result_table .= '<tr><td>Landing Page CSS written to file.</td></tr>';
			}
}
	$result_table .= '</table>';
	echo $result_table;
?>