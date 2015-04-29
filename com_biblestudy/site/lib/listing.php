<?php
/**
 * Part of Joomla BibleStudy Package
 *
 * @package    BibleStudy.Admin
 * @copyright  2007 - 2014 Joomla Bible Study Team All rights reserved
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.JoomlaBibleStudy.org
 * */
// No Direct Access
defined('_JEXEC') or die;

require_once JPATH_ADMINISTRATOR . '/components/com_biblestudy/lib/defines.php';
require_once JPATH_ADMINISTRATOR . '/components/com_biblestudy/tables/template.php';
use Joomla\Registry\Registry;

/**
 * BibleStudy listing class
 *
 * @since  7.0.0
 */
class JBSMListing
{
	/** @var  Registry */
	public $params;

	/**
	 * Get Fluid Listing
	 *
	 * @param   Object                    $items     Items
	 * @param   Joomla\Registry\Registry  $params    Page Params
	 * @param   TableTemplate             $template  Template name
	 * @param   String                    $type      Type of Listing
	 *
	 * @return string
	 */
	public function getFluidListing($items, $params, $template, $type)
	{
		$list         = '';
		$row          = array();
		$this->params = $params;
		$item         = '';

		if ($type == 'sermons')
		{
			foreach ($items as $item)
			{
				if (isset($item->mids))
				{
					$medias[] = $this->getFluidMediaids($item);
				}
			}
		}
		if ($type == 'sermon')
		{
			$medias = $this->getFluidMediaids($items);
			$item   = $items;
		}
		// Get the media files in one query
		if (isset($medias))
		{
			$mediafiles = $this->getMediaFiles($medias);
		}

		// Create an array from each param variable set
		// Find out what view we are in
		$extra = '';

		switch ($type)
		{
			case 'sermons':
				$extra = '';
				break;
			case 'sermon':
				$extra = 'd';
				break;
			case 'seriesdisplays':
				$extra = 's';
				break;
			case 'seriesdisplay':
				$extra = 'sd';
				break;
			case 'teachers':
				$extra = 'ts';
				break;
			case 'teacher':
				$extra = 'td';
				break;
			case 'module':
				$extra = 'm';
				break;
		}

		$listparams = array();
		if ($params->get($extra . 'scripture1row') > 0)
		{
			$listparams[] = $this->getListParamsArray($extra . 'scripture1');
		}
		if ($params->get($extra . 'scripture2row') > 0)
		{
			$listparams[] = $this->getListParamsArray($extra . 'scripture2');
		}
		if ($params->get($extra . 'secondaryrow') > 0)
		{
			$listparams[] = $this->getListParamsArray($extra . 'secondary');
		}
		if ($params->get($extra . 'titlerow') > 0)
		{
			$listparams[] = $this->getListParamsArray($extra . 'title');
		}
		if ($params->get($extra . 'daterow') > 0)
		{
			$listparams[] = $this->getListParamsArray($extra . 'date');
		}
		if ($params->get($extra . 'teacherrow') > 0)
		{
			$listparams[] = $this->getListParamsArray($extra . 'teacher');
		}
		if ($params->get($extra . 'teacher-titlerow') > 0)
		{
			$listparams[] = $this->getListParamsArray($extra . 'teacher-title');
		}
		if ($params->get($extra . 'durationrow') > 0)
		{
			$listparams[] = $this->getListParamsArray($extra . 'duration');
		}
		if ($params->get($extra . 'studyintrorow') > 0)
		{
			$listparams[] = $this->getListParamsArray($extra . 'studyintro');
		}
		if ($params->get($extra . 'seriesrow') > 0)
		{
			$listparams[] = $this->getListParamsArray($extra . 'series');
		}
		if ($params->get($extra . 'descriptionrow') > 0)
		{
			$listparams[] = $this->getListParamsArray($extra . 'description');
		}
		if ($params->get($extra . 'seriesthumbnailrow') > 0)
		{
			$listparams[] = $this->getListParamsArray($extra . 'seriesthumbnail');
		}
		if ($params->get($extra . 'submittedrow') > 0)
		{
			$listparams[] = $this->getListParamsArray($extra . 'submitted');
		}
		if ($params->get($extra . 'hitsrow') > 0)
		{
			$listparams[] = $this->getListParamsArray($extra . 'hits');
		}
		if ($params->get($extra . 'downloadsrow') > 0)
		{
			$listparams[] = $this->getListParamsArray($extra . 'downloads');
		}
		if ($params->get($extra . 'studynumberrow') > 0)
		{
			$listparams[] = $this->getListParamsArray($extra . 'studynumber');
		}
		if ($params->get($extra . 'topicrow') > 0)
		{
			$listparams[] = $this->getListParamsArray($extra . 'topic');
		}
		if ($params->get($extra . 'locationsrow') > 0)
		{
			$listparams[] = $this->getListParamsArray($extra . 'locations');
		}
		if ($params->get($extra . 'jbsmediarow') > 0)
		{
			$listparams[] = $this->getListParamsArray($extra . 'jbsmedia');
		}
		if ($params->get($extra . 'messagetyperow') > 0)
		{
			$listparams[] = $this->getListParamsArray($extra . 'messagetype');
		}
		if ($params->get($extra . 'thumbnailrow') > 0)
		{
			$listparams[] = $this->getListParamsArray($extra . 'thumbnail');
		}
		if ($params->get($extra . 'teacherimagerrow') > 0)
		{
			$listparams[] = $this->getListParamsArray($extra . 'teacherimage');
		}
		if ($params->get($extra . 'seriesdescriptionrow') > 0)
		{
			$listparams[] = $this->getListParamsArray($extra . 'description');
		}
		if ($params->get($extra . 'teacheremailrow') > 0)
		{
			$listparams[] = $this->getListParamsArray($extra . 'teacheremail');
		}
		if ($params->get($extra . 'teacherwebrow') > 0)
		{
			$listparams[] = $this->getListParamsArray($extra . 'teacherweb');
		}
		if ($params->get($extra . 'teacherphonerow') > 0)
		{
			$listparams[] = $this->getListParamsArray($extra . 'teacherphone');
		}
		if ($params->get($extra . 'teacherfbrow') > 0)
		{
			$listparams[] = $this->getListParamsArray($extra . 'teacherfb');
		}
		if ($params->get($extra . 'teachertwrow') > 0)
		{
			$listparams[] = $this->getListParamsArray($extra . 'teachertw');
		}
		if ($params->get($extra . 'teacherblogrow') > 0)
		{
			$listparams[] = $this->getListParamsArray($extra . 'teacherblog');
		}
		if ($params->get($extra . 'teachershortrow') > 0)
		{
			$listparams[] = $this->getListParamsArray($extra . 'teachershort');
		}
		if ($params->get($extra . 'teacherlongrow') > 0)
		{
			$listparams[] = $this->getListParamsArray($extra . 'teacherlong');
		}
		if ($params->get($extra . 'teacheraddressrow') > 0)
		{
			$listparams[] = $this->getListParamsArray($extra . 'teacheraddress');
		}
		if ($params->get($extra . 'teacherlink1row') > 0)
		{
			$listparams[] = $this->getListParamsArray($extra . 'teacherlink1');
		}
		if ($params->get($extra . 'teacherlink2row') > 0)
		{
			$listparams[] = $this->getListParamsArray($extra . 'teacherlink2');
		}
		if ($params->get($extra . 'teacherlink3row') > 0)
		{
			$listparams[] = $this->getListParamsArray($extra . 'teacherlink3');
		}
		if ($params->get($extra . 'teacherlargeimagerow') > 0)
		{
			$listparams[] = $this->getListParamsArray($extra . 'teacherlargeimage');
		}
		if ($params->get($extra . 'teacherallinonerow') > 0)
		{
			$listparams[] = $this->getListParamsArray($extra . 'teacherallinone');
		}
		if ($params->get($extra . 'customrow'))
		{
			$listparams[] = $this->getListParamsArray($extra . 'custom');
		}
		$row1       = array();
		$row2       = array();
		$row3       = array();
		$row4       = array();
		$row5       = array();
		$row6       = array();
		$row1sorted = array();
		$row2sorted = array();
		$row3sorted = array();
		$row4sorted = array();
		$row5sorted = array();
		$row6sorted = array();

		// Create an array sorted by row and then by column
		foreach ($listparams as $listing)
		{
			if ($listing->row == 1)
			{
				$row1[] = $listing;
			}
			if ($listing->row == 2)
			{
				$row2[] = $listing;
			}
			if ($listing->row == 3)
			{
				$row3[] = $listing;
			}
			if ($listing->row == 4)
			{
				$row4[] = $listing;
			}
			if ($listing->row == 5)
			{
				$row5[] = $listing;
			}
			if ($listing->row == 6)
			{
				$row6[] = $listing;
			}
		}
		if (count($row1))
		{
			$row1sorted = $this->sortArrayofObjectByProperty($row1, 'col', $order = "ASC");
		}
		if (count($row2))
		{
			$row2sorted = $this->sortArrayofObjectByProperty($row2, 'col', $order = "ASC");
		}
		if (count($row3))
		{
			$row3sorted = $this->sortArrayofObjectByProperty($row3, 'col', $order = "ASC");
		}
		if (count($row4))
		{
			$row4sorted = $this->sortArrayofObjectByProperty($row4, 'col', $order = "ASC");
		}
		if (count($row5))
		{
			$row5sorted = $this->sortArrayofObjectByProperty($row5, 'col', $order = "ASC");
		}
		if (count($row6))
		{
			$row6sorted = $this->sortArrayofObjectByProperty($row6, 'col', $order = "ASC");
		}
		$listrows    = array_merge($row1sorted, $row2sorted, $row3sorted, $row4sorted, $row5sorted, $row6sorted);
		$listsorts   = array();
		$listsorts[] = $row1sorted;
		$listsorts[] = $row2sorted;
		$listsorts[] = $row3sorted;
		$listsorts[] = $row4sorted;
		$listsorts[] = $row5sorted;
		$listsorts[] = $row6sorted;

		$class1  = $params->get($extra . 'listcolor1', '');
		$class2  = $params->get($extra . 'listcolor2', '');
		$oddeven = $class1;
		if ($type == 'sermons')
		{
			if ($params->get('use_headers_list') > 0)
			{
				$list .= $this->getFluidRow($listrows, $listsorts, $item, $params, $template, $oddeven, $header = 1, $type);
			}
		}
		if ($type == 'sermon')
		{
			if ($params->get('use_headers_view') > 0)
			{
				$list .= $this->getFluidRow($listrows, $listsorts, $item, $params, $template, $oddeven, $header = 1, $type);
			}
		}
		if ($type == 'seriesdisplays')
		{
			if ($params->get('use_headers_series') > 0 && is_object($items))
			{
				$list .= $this->getFluidRow($listrows, $listsorts, $items[0], $params, $template, $oddeven, $header = 1, $type);
			}
		}
		if ($type == 'seriesdisplay')
		{
			if ($params->get('use_header_seriesdisplay') > 0)
			{
				$oddeven = $params->get('seriesdisplay_color');
				$list .= $this->getFluidRow($listrows, $listsorts, $items, $params, $template, $oddeven, $header = 1, $type);
			}
			$list .= $this->getFluidRow($listrows, $listsorts, $items, $params, $template, $oddeven, $header = 0, $type);
		}
		if ($type == 'teacher')
		{
			if ($params->get('use_headers_teacher_details') > 0)
			{
				$oddeven = $params->get('teacherdisplay_color', 'white');
				$list .= $this->getFluidRow($listrows, $listsorts, $items, $params, $template, $oddeven, $header = 1, $type);
			}
			$list .= $this->getFluidRow($listrows, $listsorts, $items, $params, $template, $oddeven, $header = 0, $type);
		}
		if ($type == 'teachers')
		{
			if ($params->get('use_headers_teacher_list') > 0)
			{
				$list .= $this->getFluidRow($listrows, $listsorts, $items, $params, $template, $oddeven, $header = 1, $type);
			}

		}

		// Go through and attach the media files as an array to their study
		if ($type == 'sermons')
		{
			foreach ($items as $item)
			{
				$oddeven    = ($oddeven == $class1) ? $class2 : $class1;
				$studymedia = array();
				if (isset($mediafiles))
				{
					foreach ($mediafiles as $mediafile)
					{
						if ($mediafile->study_id == $item->id)
						{
							$studymedia[] = $mediafile;
						}
					}
				}
				if (isset($studymedia))
				{
					$item->mediafiles = $studymedia;
				}
				$row[] = $this->getFluidRow($listrows, $listsorts, $item, $params, $template, $oddeven, $header = 0, $type);
			}
		}
		if ($type == 'sermon')
		{
			$oddeven    = ($oddeven == $class1) ? $class2 : $class1;
			$studymedia = array();
			if (isset($mediafiles))
			{
				foreach ($mediafiles as $mediafile)
				{
					if ($mediafile->study_id == $item->id)
					{
						$studymedia[] = $mediafile;
					}
				}
			}
			if (isset($studymedia))
			{
				$item->mediafiles = $studymedia;
			}
			$row[] = $this->getFluidRow($listrows, $listsorts, $item, $params, $template, $oddeven, $header = 0, $type);
		}
		if ($type == 'seriesdisplays')
		{
			foreach ($items as $item)
			{
				$oddeven = ($oddeven == $class1) ? $class2 : $class1;
				$row[]   = $this->getFluidRow($listrows, $listsorts, $item, $params, $template, $oddeven, $header = 0, $type);
			}
		}
		if ($type == 'teachers')
		{
			foreach ($items as $item)
			{
				$oddeven = ($oddeven == $class1) ? $class2 : $class1;
				$row[]   = $this->getFluidRow($listrows, $listsorts, $item, $params, $template, $oddeven, $header = 0, $type);
			}
		}
		foreach ($row as $value)
		{
			$list .= $value;
		}

		return $list;
	}

	/**
	 * Get Fluid Media Id's
	 *
	 * @param   Object  $item  Items info
	 *
	 * @return array
	 */
	public function getFluidMediaids($item)
	{
		$medias    = array();
		$mediatemp = explode(',', $item->mids);
		foreach ($mediatemp as $mtemp)
		{
			$medias[] = $mtemp;
		}

		return $medias;
	}

	/**
	 * Get Media Files
	 *
	 * @param   Array  $medias  Media files
	 *
	 * @return mixed
	 */
	public function getMediaFiles($medias)
	{
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('#__bsms_mediafiles.*, #__bsms_servers.id AS ssid, #__bsms_servers.params AS sparams, #__bsms_servers.media AS smedia,'
			. ' s.studytitle, s.studydate, s.studyintro, s.media_hours, s.media_minutes, s.media_seconds, s.teacher_id,'
			. ' s.booknumber, s.chapter_begin, s.chapter_end, s.verse_begin, s.verse_end, t.teachername, t.id as tid, s.id as sid, s.studyintro');
		$query->from('#__bsms_mediafiles');
		$query->leftJoin('#__bsms_servers ON (#__bsms_servers.id = #__bsms_mediafiles.server_id)');
		$query->leftJoin('#__bsms_studies AS s ON (s.id = #__bsms_mediafiles.study_id)');
		$query->leftJoin('#__bsms_teachers AS t ON (t.id = s.teacher_id)');
		$where2   = array();
		$subquery = '(';
		foreach ($medias as $media)
		{
			if (is_array($media))
			{
				foreach ($media as $m)
				{
					$where2[] = '#__bsms_mediafiles.id = ' . (int) $m;
				}
			}
			else
			{
				$where2[] = '#__bsms_mediafiles.id = ' . (int) $media;
			}
		}
		$subquery .= implode(' OR ', $where2);
		$subquery .= ')';
		$query->where($subquery);
		$query->where('#__bsms_mediafiles.published = 1');
		$query->where('#__bsms_mediafiles.language in (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')');
		$query->order('ordering ASC');
		$db->setQuery($query);

		return $db->loadObjectList();
	}

	/**
	 * Get list Params Array
	 *
	 * @param   string  $paramtext  Param Text
	 *
	 * @return stdClass
	 */
	public function getListParamsArray($paramtext)
	{
		$l             = new stdClass;
		$l->row        = $this->params->get($paramtext . 'row');
		$l->col        = $this->params->get($paramtext . 'col');
		$l->colspan    = $this->params->get($paramtext . 'colspan');
		$l->element    = $this->params->get($paramtext . 'element');
		$l->custom     = $this->params->get($paramtext . 'custom');
		$l->linktype   = $this->params->get($paramtext . 'linktype');
		$l->name       = $paramtext;
		$l->customtext = $this->params->get($paramtext . 'text');

		return $l;
	}

	/**
	 * Sort Array of Object by Property
	 *
	 * @param   array   $array     ?
	 * @param   string  $property  ?
	 * @param   string  $order     ?
	 *
	 * @return array
	 */
	public function sortArrayofObjectByProperty($array, $property, $order = "ASC")
	{
		$cur           = 1;
		$stack[1]['l'] = 0;
		$stack[1]['r'] = count($array) - 1;

		do
		{
			$l = $stack[$cur]['l'];
			$r = $stack[$cur]['r'];
			$cur--;

			do
			{
				$i   = $l;
				$j   = $r;
				$tmp = $array[(int) (($l + $r) / 2)];

				/* Split the array in to parts
				// first: objects with "smaller" property $property
				// second: objects with "bigger" property $property */
				do
				{
					while ($array[$i]->{$property} < $tmp->{$property})
					{
						$i++;
					}
					while ($tmp->{$property} < $array[$j]->{$property})
					{
						$j--;
					}

					// Swap elements of two parts if necesary
					if ($i <= $j)
					{
						$w         = $array[$i];
						$array[$i] = $array[$j];
						$array[$j] = $w;

						$i++;
						$j--;
					}

				}
				while ($i <= $j);

				if ($i < $r)
				{
					$cur++;
					$stack[$cur]['l'] = $i;
					$stack[$cur]['r'] = $r;
				}
				$r = $j;

			}
			while ($l < $r);

		}
		while ($cur != 0);

		// Added ordering.
		if ($order == "DESC")
		{
			$array = array_reverse($array);
		}

		return $array;
	}

	/**
	 * Get Fluid Row
	 *
	 * @param   Array                     $listrows   ?
	 * @param   Array                     $listsorts  ?
	 * @param   Object                    $item       ?
	 * @param   Joomla\Registry\Registry  $params     Item Params
	 * @param   TableTemplate             $template   Template info
	 * @param   string                    $oddeven    ?
	 * @param   string                    $header     ?
	 * @param   string                    $type       ?
	 *
	 * @return string
	 */
	public function getFluidRow($listrows, $listsorts, $item, $params, $template, $oddeven, $header, $type)
	{
		$span        = '';
		$headerstyle = '';
		if ($header == 1)
		{
			$headerstyle = "style=visibility:hidden;";
		}
		$extra = '';
		switch ($type)
		{
			case 'sermon':
				$extra = 'd';

				break;
			case 'seriesdisplays':
				$extra = 's';

				break;
			case 'seriesdisplay':
				$extra = 'sd';

				break;
			case 'sermons':

				break;
			case 'teachers':
				$extra = 'ts';
				break;
			case 'teacher':
				$extra = 'td';
				break;
		}

		$pull        = $params->get($extra . 'rowspanitempull');
		$rowspanitem = $params->get($extra . 'rowspanitem');
		if ($rowspanitem)
		{
			switch ($rowspanitem)
			{
				case 1:
					(isset($item->thumb) ? $span = '<img src="' . JURI::base() . $item->thumb . '" class="' . $params->get('rowspanitemimage') .
						'" alt="' . JText::_('JBS_CMN_TEACHER') . '">' : $span = '');
					(isset($item->teacher_thumbnail) ? $span = '<img src="' . JURI::base() . $item->teacher_thumbnail . '" class="' .
						$params->get('rowspanitemimage') . '" alt="' . JText::_('JBS_CMN_TEACHER') . '">' : $span = '');
					break;
				case 2:
					(isset($item->thumbm) ? $span = '<img src="' . JURI::base() . $item->thumbm . '" class="' . $params->get('rowspanitemimage') .
						'" alt="' . JText::_('JBS_CMN_THUMBNAIL') . '">' : $span = '');
					break;
				case 3:
					(isset($item->series_thumbnail) ? $span = '<img src="' . JURI::base() . $item->series_thumbnail . '" class="' .
						$params->get('rowspanitemimage') . '" alt="' . JText::_('JBS_CMN_SERIES') . '">' : $span = '');
					break;
				case 4:
					(isset($item->teacher_image) ? $span = '<img src="' . JURI::base() . $item->teacher_image . '" class="' .
						$params->get('rowspanitemimage') . '" alt="' .
						JText::_('JBS_CMN_TEACHER') . '">' : $span = '');
					break;
			}
		}

		$rowspanitemspan = $params->get($extra . 'rowspanitemspan');
		$rowspanbalance  = 12 - $rowspanitemspan;
		$frow            = '<div class="row-fluid" style="background-color:' . $oddeven . '; padding:5px;">';

		$row1count  = 0;
		$row2count  = 0;
		$row3count  = 0;
		$row4count  = 0;
		$row5count  = 0;
		$row6count  = 0;
		$row1count2 = 0;
		$row2count2 = 0;
		$row3count2 = 0;
		$row4count2 = 0;
		$row5count2 = 0;
		$row6count2 = 0;
		if ($span)
		{
			$frow .= '<div class="row-fluid" >';
			$frow .= '<div class="span' . $rowspanitemspan . ' ' . $pull . '"><div ' . $headerstyle . ' class="">' . $span . '</div></div>';
			$frow .= '<div class="span' . $rowspanbalance . '">';
		}
		foreach ($listsorts as $sort)
		{
			if (count($sort))
			{
				foreach ($sort as $s)
				{
					if ($s->row == 1)
					{
						$row1count++;
						$row1count2++;
					}
					if ($s->row == 2)
					{
						$row2count++;
						$row2count2++;
					}
					if ($s->row == 3)
					{
						$row3count++;
						$row3count2++;
					}
					if ($s->row == 4)
					{
						$row4count++;
						$row4count2++;
					}
					if ($s->row == 5)
					{
						$row5count++;
						$row5count2++;
					}
					if ($s->row == 6)
					{
						$row6count++;
						$row6count2++;
					}
				}
			}

		}

		foreach ($listrows as $row)
		{
			if ($row->row == 1)
			{

				if ($row1count == $row1count2)
				{
					$frow .= '<div class="row-fluid">';
				}
				if ($header == 1)
				{
					$frow .= '<b>' . $this->getFluidData($item, $row, $params, $template, $header = 1, $type) . '</b>';
				}
				else
				{
					$frow .= $this->getFluidData($item, $row, $params, $template, $header = 0, $type);
				}
				$row1count--;
				if ($row1count == 0)
				{
					$frow .= '</div>';
				}
			}
			if ($row->row == 2)
			{

				if ($row2count == $row2count2)
				{
					$frow .= '<div class="row-fluid">';
				}
				if ($header == 1)
				{
					$frow .= '<b>' . $this->getFluidData($item, $row, $params, $template, $header = 1, $type) . '</b>';
				}
				else
				{
					$frow .= $this->getFluidData($item, $row, $params, $template, $header = 0, $type);
				}
				$row2count--;
				if ($row2count == 0)
				{
					$frow .= '</div>';
				}
			}
			if ($row->row == 3)
			{

				if ($row3count == $row3count2)
				{
					$frow .= '<div class="row-fluid">';
				}
				if ($header == 1)
				{
					$frow .= '<b>' . $this->getFluidData($item, $row, $params, $template, $header = 1, $type) . '</b>';
				}
				else
				{
					$frow .= $this->getFluidData($item, $row, $params, $template, $header = 0, $type);
				}
				$row3count--;
				if ($row3count == 0)
				{
					$frow .= '</div>';
				}
			}
			if ($row->row == 4)
			{

				if ($row4count == $row4count2)
				{
					$frow .= '<div class="row-fluid">';
				}
				if ($header == 1)
				{
					$frow .= '<b>' . $this->getFluidData($item, $row, $params, $template, $header = 1, $type) . '</b>';
				}
				else
				{
					$frow .= $this->getFluidData($item, $row, $params, $template, $header = 0, $type);
				}
				$row4count--;
				if ($row4count == 0)
				{
					$frow .= '</div>';
				}
			}
			if ($row->row == 5)
			{

				if ($row5count == $row5count2)
				{
					$frow .= '<div class="row-fluid">';
				}
				if ($header == 1)
				{
					$frow .= '<b>' . $this->getFluidData($item, $row, $params, $template, $header = 1, $type) . '</b>';
				}
				else
				{
					$frow .= $this->getFluidData($item, $row, $params, $template, $header = 0, $type);
				}
				$row5count--;
				if ($row5count == 0)
				{
					$frow .= '</div>';
				}
			}
			if ($row->row == 6)
			{

				if ($row6count == $row6count2)
				{
					$frow .= '<div class="row-fluid">';
				}
				if ($header == 1)
				{
					$frow .= '<b>' . $this->getFluidData($item, $row, $params, $template, $header = 1, $type) . '</b>';
				}
				else
				{
					$frow .= $this->getFluidData($item, $row, $params, $template, $header = 0, $type);
				}
				$row6count--;
				if ($row6count == 0)
				{
					$frow .= '</div>';
				}
			}
		}
		$frow .= '</div>';
		if ($span)
		{
			$frow .= '</div></div>';
		}

		return $frow;
	}

	/**
	 * Get Fluid Date
	 *
	 * @param   Object                    $item      ?
	 * @param   Object                    $row       ?
	 * @param   Joomla\Registry\Registry  $params    ?
	 * @param   TableTemplate             $template  ?
	 * @param   string                    $header    ?
	 * @param   string                    $type      ?
	 *
	 * @return string
	 */
	public function getFluidData($item, $row, $params, $template, $header, $type)
	{
		$smenu = $params->get('detailsitemid');
		$tmenu = $params->get('teacheritemid');
		/** @var string $data */
		$data = '';

		// Match the data in $item to a row/col in $row->name
		$extra = '';
		switch ($type)
		{
			case 'sermon':
				$extra = 'd';
				break;
			case 'seriesdisplays':
				$extra = 's';
				break;
			case 'seriesdisplay':
				$extra = 'sd';
				break;
			case 'teachers':
				$extra = 'ts';
				break;
			case 'teacher':
				$extra = 'td';
				break;
		}

		switch ($row->name)
		{
			case $extra . 'custom':

				if ($header == 1)
				{
					$data = '';
				}
				else
				{
					$data = $this->getFluidCustom($row->customtext, $item, $params, $template, $type);
				}
				break;

			case $extra . 'teacherallinone':
				($item->email ? $data = '<a href="mailto:' . $item->email . '"><img height="24" width="24" alt="' . JText::_('JBS_TCH_EMAIL') . '"  src="' .
					JURI::base() . 'media/com_biblestudy/images/email.png"></a>' : $data = '');
				if ($item->website)
				{
					if (substr_count($item->website, 'http://', 0))
					{
						$data .= '<a href="' . $item->website . '" target="_blank"><img height="24" width="24" alt="' . $item->website . '"  src="' . JURI::base() .
							'media/com_biblestudy/images/web.png"></a>';
					}
					else
					{
						$data .= '<a href="http://' . $item->website . '" target="_blank"><img height="24" width="24" alt="' . $item->website . '" src="' .
							JURI::base() . 'media/com_biblestudy/images/web.png"></a>';
					}
				}
				if ($item->facebooklink)
				{
					if (substr_count($item->facebooklink, 'http://', 0))
					{
						$data .= '<a href="' . $item->facebooklink . '" target="_blank"><img height="24" width="24" alt="' . $item->facebooklink . '"  src="' .
							JURI::base() . 'media/com_biblestudy/images/facebook.png"></a>';
					}
					else
					{
						$data .= '<a href="http://' . $item->facebooklink . '" target="_blank"><img height="24" width="24" alt="' . $item->facebooklink . '" src="' .
							JURI::base() . 'media/com_biblestudy/images/facebook.png"></a>';
					}
				}
				if ($item->twitterlink)
				{
					if (substr_count($item->twitterlink, 'http://', 0))
					{
						$data .= '<a href="' . $item->twitterlink . '" target="_blank"><img height="24" width="24" alt="' . $item->twitterlink . '" src="' .
							JURI::base() . 'media/com_biblestudy/images/twitter.png"></a>';
					}
					else
					{
						$data .= '<a href="http://' . $item->twitterlink . '" target="_blank"><img height="24" width="24" alt="' . $item->twitterlink . '"  src="' .
							JURI::base() . 'media/com_biblestudy/images/twitter.png"></a>';
					}
				}
				if ($item->bloglink)
				{
					if (substr_count($item->bloglink, 'http://', 0, 7))
					{
						$data .= '<a href="' . $item->bloglink . '" target="_blank"><img height="24" width="24" alt="' . $item->bloglink . '" target="_blank" src="' .
							JURI::base() . 'media/com_biblestudy/images/blog.png"></a>';
					}
					else
					{
						$data .= '<a href="http://' . $item->bloglink . '" target="_blank"><img height="24" width="24" alt="' . $item->bloglink .
							'" target="_blank" src="' . JURI::base() . 'media/com_biblestudy/images/blog.png"></a>';
					}
				}
				if ($item->link1)
				{
					if (substr_count($item->link1, 'http://', 0))
					{
						$data .= '<a href="' . $item->link1 . '" target="_blank">' . $item->link1label . '></a>';
					}
					else
					{
						$data .= '<a href="http://' . $item->link1 . '" target="_blank">' . $item->link1label . '</a>';
					}
				}
				if ($item->link2)
				{
					if (substr_count($item->link2, 'http://', 0))
					{
						$data .= '<a href="' . $item->link2 . '" target="_blank">' . $item->link2label . '></a>';
					}
					else
					{
						$data .= '<a href="http://' . $item->link2 . '" target="_blank">' . $item->link2label . '</a>';
					}
				}
				if ($item->link3)
				{
					if (substr_count($item->link3, 'http://', 0))
					{
						$data .= '<a href="' . $item->link3 . '" target="_blank">' . $item->link3label . '></a>';
					}
					else
					{
						$data .= '<a href="http://' . $item->link3 . '" target="_blank">' . $item->link3label . '</a>';
					}
				}
				break;

			case $extra . 'teacherlong':
				if ($header == 1)
				{
					$data = JText::_('JBS_TCH_INFORMATION');
				}
				else
				{
					($item->long ? $data = JHtml::_('content.prepare', $item->long, '', 'com_biblestudy.' . $type) : $data = '');
				}
				break;

			case $extra . 'teacheraddress':
				if ($header == 1)
				{
					$data = JText::_('JBS_TCH_ADDRESS');
				}
				else
				{
					($item->address ? $data = $item->address : $data = '');
				}
				break;

			case $extra . 'teacherlink1':
				if ($header == 1)
				{
					$data = JText::_('JBS_TCH_LINK1');
				}
				else
				{
					if ($item->link1)
					{
						if (substr_count($item->link1, 'http://', 0))
						{
							$data = '<a href="' . $item->link1 . '" target="_blank">' . $item->link1label . '></a>';
						}
						else
						{
							$data = '<a href="http://' . $item->link1 . '" target="_blank">' . $item->link1label . '</a>';
						}
					}
				}
				break;

			case $extra . 'teacherlink2':
				if ($header == 1)
				{
					$data = JText::_('JBS_TCH_LINK2');
				}
				else
				{
					if ($item->link2)
					{
						if (substr_count($item->link2, 'http://', 0))
						{
							$data = '<a href="' . $item->link2 . '" target="_blank">' . $item->link2label . '></a>';
						}
						else
						{
							$data = '<a href="http://' . $item->link2 . '" target="_blank">' . $item->link2label . '</a>';
						}
					}
				}
				break;

			case $extra . 'teacherlink3':
				if ($header == 1)
				{
					$data = JText::_('JBS_TCH_LINK3');
				}
				else
				{
					if ($item->link3)
					{
						if (substr_count($item->link3, 'http://', 0))
						{
							$data = '<a href="' . $item->link3 . '" target="_blank">' . $item->link3label . '></a>';
						}
						else
						{
							$data = '<a href="http://' . $item->link3 . '" target="_blank">' . $item->link3label . '</a>';
						}
					}
				}
				break;
			case $extra . 'teacheremail':
				if ($header == 1)
				{
					$data = JText::_('JBS_TCH_EMAIL');
				}
				else
				{
					($item->email ? $data = '<a href="mailto:' . $item->email . '"><img height="24" width="24" alt="' . JText::_('JBS_TCH_EMAIL') . '"  src="' .
						JURI::base() . 'media/com_biblestudy/images/email.png"></a>' : $data = '');
				}
				break;

			case $extra . 'teacherweb':
				if ($header == 1)
				{
					$data = JText::_('JBS_TCH_WEBSITE');
				}
				else
				{
					if ($item->website)
					{
						if (substr_count($item->website, 'http://', 0))
						{
							$data = '<a href="' . $item->website . '" target="_blank"><img height="24" width="24" alt="' . $item->website . '"  src="' .
								JURI::base() . 'media/com_biblestudy/images/web.png"></a>';
						}
						else
						{
							$data = '<a href="http://' . $item->website . '" target="_blank"><img height="24" width="24" alt="' . $item->website . '" src="' .
								JURI::base() . 'media/com_biblestudy/images/web.png"></a>';
						}
					}
				}

				break;

			case $extra . 'teacherphone':
				if ($header == 1)
				{
					$data = JText::_('JBS_TCH_PHONE');
				}
				else
				{
					(isset($item->phone) ? $data = $item->phone : $data = '');
				}
				break;

			case $extra . 'teacherfb':
				if ($header == 1)
				{
					$data = JText::_('JBS_TCH_FACEBOOK');
				}
				else
				{
					if ($item->facebooklink)
					{
						if (substr_count($item->facebooklink, 'http://', 0))
						{
							$data = '<a href="' . $item->facebooklink . '" target="_blank"><img height="24" width="24" alt="' . $item->facebooklink .
								'"  src="' . JURI::base() . 'media/com_biblestudy/images/facebook.png"></a>';
						}
						else
						{
							$data = '<a href="http://' . $item->facebooklink . '" target="_blank"><img height="24" width="24" alt="' . $item->facebooklink .
								'" src="' . JURI::base() . 'media/com_biblestudy/images/facebook.png"></a>';
						}
					}
				}
				break;

			case $extra . 'teachertw':
				if ($header == 1)
				{
					$data = JText::_('JBS_TCH_TWITTER');
				}
				else
				{
					if ($item->twitterlink)
					{
						if (substr_count($item->twitterlink, 'http://', 0))
						{
							$data = '<a href="' . $item->twitterlink . '" target="_blank"><img height="24" width="24" alt="' . $item->twitterlink . '" src="' .
								JURI::base() . 'media/com_biblestudy/images/twitter.png"></a>';
						}
						else
						{
							$data = '<a href="http://' . $item->twitterlink . '" target="_blank"><img height="24" width="24" alt="' . $item->twitterlink .
								'"  src="' . JURI::base() . 'media/com_biblestudy/images/twitter.png"></a>';
						}
					}
				}

				break;

			case $extra . 'teacherblog':
				if ($header == 1)
				{
					$data = JText::_('JBS_TCH_BLOG');
				}
				else
				{
					if ($item->bloglink)
					{
						if (substr_count($item->bloglink, 'http://', 0, 7))
						{
							$data = '<a href="' . $item->bloglink . '" target="_blank"><img height="24" width="24" alt="' . $item->bloglink .
								'" target="_blank" src="' . JURI::base() . 'media/com_biblestudy/images/blog.png"></a>';
						}
						else
						{
							$data = '<a href="http://' . $item->bloglink . '" target="_blank"><img height="24" width="24" alt="' . $item->bloglink .
								'" target="_blank" src="' . JURI::base() . 'media/com_biblestudy/images/blog.png"></a>';
						}
					}
				}

				break;

			case $extra . 'teachershort':
				if ($header == 1)
				{
					$data = JText::_('JBS_TCH_SHORT_LIST');
				}
				else
				{
					(isset($item->short) ? $data = JHtml::_('content.prepare', $item->short, '', 'com_biblestudy.' . $type) : $data = '');
				}
				break;

			case $extra . 'scripture1':
				$esv          = 0;
				$scripturerow = 1;
				if ($header == 1)
				{
					$data = JText::_('JBS_CMN_SCRIPTURE');
				}
				else
				{
					(isset($item->booknumber) ? $data = $this->getScripture($params, $item, $esv, $scripturerow) : $data = '');
				}
				break;
			case $extra . 'scripture2':
				$esv          = 0;
				$scripturerow = 2;
				if ($header == 1)
				{
					$data = JText::_('JBS_CMN_SCRIPTURE');
				}
				else
				{
					(isset($item->booknumber2) ? $data = $this->getScripture($params, $item, $esv, $scripturerow) : $data = '');
				}
				break;
			case $extra . 'secondary':
				if ($header == 1)
				{
					$data = JText::_('JBS_CMN_SECONDARY_REFERENCES');
				}
				else
				{
					(isset($item->secondary) ? $item->secondary : '');
				}
				break;
			case $extra . 'title':
				if ($header == 1)
				{
					$data = JText::_('JBS_CMN_TITLE');
				}
				else
				{
					(isset($item->studytitle) ? $data = stripslashes($item->studytitle) : $data = '');
				}
				break;
			case $extra . 'date':
				if ($header == 1)
				{
					$data = JText::_('JBS_CMN_STUDY_DATE');
				}
				else
				{
					(isset($item->studydate) ? $data = $this->getstudyDate($params, $item->studydate) : $data = '');
				}
				break;
			case $extra . 'teacher':
				if ($header == 1)
				{
					$data = JText::_('JBS_CMN_TEACHER');
				}
				else
				{
					(isset($item->teachername) ? $data = $item->teachername : $data = '');
				}

				break;
			case $extra . 'teacher-title':
				if ($header == 1)
				{
					$data = JText::_('JBS_CMN_TEACHER');
				}
				elseif (isset($item->teachertitle) && isset($item->teachername))
				{
					$data = $item->teachertitle . ' ' . $item->teachername;
				}
				else
				{
					$data = $item->teachername;
				}
				break;
			case $extra . 'duration':
				if ($header == 1)
				{
					$data = JText::_('JBS_CMN_DURATION');
				}
				else
				{
					(isset($item->media_minutes) ? $data = $this->getDuration($params, $item) : $data = '');
				}
				break;
			case $extra . 'studyintro':
				if ($header == 1)
				{
					$data = JText::_('JBS_CMN_STUDY_INTRO');
				}
				else
				{
					(isset($item->studyintro) ? $data = JHtml::_('content.prepare', $item->studyintro, '', 'com_biblestudy.' . $type) : $data = '');
				}
				break;
			case $extra . 'series':
				if ($header == 1)
				{
					$data = JText::_('JBS_CMN_SERIES');
				}
				else
				{
					(isset($item->series_text) ? $data = $item->series_text : $data = '');
				}
				break;
			case $extra . 'seriesthumbnail':
				if ($header == 1)
				{
					$data = JText::_('JBS_CMN_THUMBNAIL');
				}
				else
				{
					if ($item->series_thumbnail)
					{
						$data = $this->useJImage($item->series_thumbnail, JText::_('JBS_CMN_THUMBNAIL'));
					}
					else
					{
						$data = '';
					}
				}
				break;
			case $extra . 'teacherlargeimage':
				if ($header == 1)
				{
					$data = JText::_('JBS_TCH_TEACHER_IMAGE');
				}
				else
				{
					(isset($item->teacher_image) ? $data = '<img src="' . JURI::base() . $item->teacher_image . '" alt="' . JText::_('JBS_CMN_THUMBNAIL') .
						'">' : $data = '');
				}
				break;
			case $extra . 'description':
				if ($type == 'seriesdisplays' || $type == 'seriesdisplay' && $header != 1)
				{
					(isset($item->description) ? $data = JHtml::_('content.prepare', $item->description, '', 'com_biblestudy.' . $type) : $data = '');
				}
				else
				{
					(isset($item->sdescription) ? $data = JHtml::_('content.prepare', $item->sdescription, '', 'com_biblestudy.' . $type) : $data = '');
				}
				if ($type == 'seriesdisplays' && !$header)
				{
					(isset($item->description) ? $data = stripslashes($item->description) : $data = '');
				}
				break;
			case $extra . 'submitted':
				if ($header == 1)
				{
					$data = JText::_('JBS_CMN_SUBMITTED_BY');
				}
				else
				{
					(isset($item->submitted) ? $data = $item->submitted : $data = '');
				}
				break;
			case $extra . 'hits':
				if ($header == 1)
				{
					$data = JText::_('JBS_CMN_VIEWS');
				}
				else
				{
					(isset($item->hits) ? $data = $item->hits : $data = '');
				}
				break;
			case $extra . 'downloads':
				if ($header == 1)
				{
					$data = JText::_('JBS_CMN_DOWNLOADS');
				}
				else
				{
					(isset($item->downloads) ? $data = $item->downloads : $data = '');
				}
				break;
			case $extra . 'studynumber':
				if ($header == 1)
				{
					$data = JText::_('JBS_CMN_STUDYNUMBER');
				}
				else
				{
					(isset($item->studynumber) ? $data = $item->studynumber : $data = '');
				}
				break;
			case $extra . 'topic':
				if ($header == 1)
				{
					$data = JText::_('JBS_CMN_TOPIC');
				}
				elseif (isset($item->topics_text))
				{
					if (substr_count($item->topics_text, ','))
					{
						$topics = explode(',', $item->topics_text);

						foreach ($topics as $key => $value)
						{
							$topics[$key] = JText::_($value);
						}
						$data = implode(', ', $topics);
					}
					else
					{
						(isset($item->topics_text) ? $data = JText::_($item->topics_text) : $data = '');
					}
				}
				break;
			case $extra . 'locations':
				if ($header == 1)
				{
					$data = JText::_('JBS_CMN_LOCATION');
				}
				else
				{
					(isset($item->location_text) ? $data = $item->location_text : $data = '');
				}
				break;
			case $extra . 'jbsmedia':
				if ($header == 1)
				{
					$data = JText::_('JBS_CMN_MEDIA');
				}
				else
				{
					$data = $this->getFluidMediaFiles($item, $params, $template);
				}
				break;
			case $extra . 'messagetype':
				if ($header == 1)
				{
					$data = JText::_('JBS_CMN_MESSAGETYPE');
				}
				else
				{
					(isset($item->messaget_type) ? $data = $item->message_type : $data = '');
				}
				break;
			case $extra . 'thumbnail':
				if ($header == 1)
				{
					$data = JText::_('JBS_CMN_THUMBNAIL');
				}
				elseif ($item->thumbnailm)
				{
					$data = $this->useJImage($item->thumbnailm, JText::_('JBS_CMN_THUMBNAIL'));
				}
				else
				{
					$data = '';
				}
				break;
			case $extra . 'teacherimage':
				if ($type == 'seriesdisplays' || $type == 'seriesdisplay' || $type == 'teachers')
				{
					if ($item->teacher_thumbnail)
					{
						$data = $this->useJImage($item->teacher_thumbnail, JText::_('JBS_CMN_THUMBNAIL'));
					}
					else
					{
						$data = '';
					}
				}
				else
				{
					if ($item->thumb)
					{
						$data = $this->useJImage($item->thumb, JText::_('JBS_CMN_THUMBNAIL'));
					}
					else
					{
						$data = '';
					}
				}
				break;
		}

		$style        = '';
		$customclass  = '';
		$classelement = '';
		if (isset($row->custom))
		{
			if (strpos($row->custom, 'style=') !== false)
			{
				$style = $row->custom;
			}
			else
			{
				$customclass = $row->custom;
			}
		}
		switch ($row->element)
		{
			case 0:
				$classelement = '';
				break;
			case 1:
				$classelement = 'p';
				break;
			case 2:
				$classelement = 'h1';
				break;
			case 3:
				$classelement = 'h2';
				break;
			case 4:
				$classelement = 'h3';
				break;
			case 5:
				$classelement = 'h4';
				break;
			case 6:
				$classelement = 'h5';
				break;
			case 7:
				$classelement = 'blockquote';
		}
		if ($header == 1)
		{
			$classelement = '';
			$style        = 'style="font-weight:bold;"';
		}
		if ($classelement)
		{
			$classopen  = '<' . $classelement . ' ' . $style . '>';
			$classclose = '</' . $classelement . '>';
		}
		else
		{
			$classopen  = '';
			$classclose = '';
		}

		// See whether the element is a link to something and get the link from the function
		$link = 0;

		if ($type == 'sermons' || $type == 'seriesdisplays' || $type == 'teachers')
		{
			if ($row->linktype > 0 && $header == 0)
			{
				if ($type == 'seriesdisplays')
				{
					$item->teacher_id = $item->teacher;
				}
				if ($type == 'teachers')
				{
					$item->teacher_id = $item->id;
				}
				$link = $this->getLink($row->linktype, $item->id, $item->teacher_id, $smenu, $tmenu, $params, $item, $template);
			}
		}
		$frow = '<div class="span' . $row->colspan . ' ' . $customclass . '"><div class="">' . $classopen;
		if ($link)
		{
			$frow .= $link;
		}
		if ($data)
		{
			$frow .= $data;
		}

		if ($link)
		{
			$frow .= '</a>';
		}

		$frow .= $classclose . '</div>';

		$frow .= '</div>';

		return $frow;
	}

	/**
	 * Get Fluid Custom
	 *
	 * @param   String                    $custom    ?
	 * @param   Object                    $item      ?
	 * @param   Joomla\Registry\Registry  $params    ?
	 * @param   TableTemplate             $template  ?
	 * @param   String                    $type      ?
	 *
	 * @return mixed
	 */
	public function getFluidCustom($custom, $item, $params, $template, $type)
	{
		$countbraces = substr_count($custom, '{');

		while ($countbraces > 0)
		{
			$bracebegin = strpos($custom, '{');
			$braceend   = strpos($custom, '}');
			$subcustom  = substr($custom, ($bracebegin + 1), (($braceend - $bracebegin) - 1));

			$element = $this->getElement($subcustom, $item, $params, $template, $type);
			$custom  = substr_replace($custom, $element, $bracebegin, (($braceend - $bracebegin) + 1));
			$countbraces--;
		}

		return $custom;
	}

	/**
	 * Get Element
	 *
	 * @param   String                    $custom    ?
	 * @param   Object                    $row       ?
	 * @param   Joomla\Registry\Registry  $params    ?
	 * @param   TableTemplate             $template  ?
	 * @param   String                    $type      ?
	 *
	 * @return mixed|null|string
	 */
	public function getElement($custom, $row, $params, $template, $type)
	{
		$element = null;
		switch ($custom)
		{
			case 'scripture1':
				$esv          = 0;
				$scripturerow = 1;
				$element      = self::getScripture($params, $row, $esv, $scripturerow);
				break;
			case 'scripture2':
				$esv          = 0;
				$scripturerow = 2;
				$element      = self::getScripture($params, $row, $esv, $scripturerow);
				break;
			case 'secondary':
				$element = $row->secondary_reference;
				break;

			case 'duration':
				$element = self::getDuration($params, $row);
				break;
			case 'title':
				if (isset($row->studytitle))
				{
					$element = $row->studytitle;
				}
				else
				{
					$element = '';
				}
				break;
			case 'studyintro':
				if (isset($row->studyintro))
				{
					$element = JHtml::_('content.prepare', $row->studyintro, '', 'com_biblestudy.' . $type);
				}
				else
				{
					$element = '';
				}
				break;
			case 'teacher':
				// Teacher name and title
				if (isset($row->teachertitle) && isset($row->teachername))
				{
					$element = $row->teachertitle . ' ' . $row->teachername;
				}
				else
				{
					$element = '';
				}
				break;
			case 'studynumber':
				if (isset($row->studynumber))
				{
					$element = $row->studynumber;
				}
				else
				{
					$element = '';
				}
				break;
			case 'series_text':
				// Series title
				if (isset($row->series_text))
				{
					$element = $row->series_text;
				}
				else
				{
					$element = '';
				}
				break;
			case 'series_thumbnail':
				if ($row->series_thumbnail)
				{
					$element = '<img src="' . JURI::base() . $row->series_thumbnail . '" alt="' . $row->series_text . '">';
				}
				else
				{
					$element = '';
				}
				break;
			case 'submitted':
				if (isset($row->submitted))
				{
					$element = $row->submitted;
				}
				else
				{
					$element = '';
				}
				break;
			case 'teacherimage':
				if (isset($row->teacher_thumbnail))
				{
					$element = '<img src="' . JURI::base() . $row->teacher_thumbnail . '" alt="' . $row->teachername . '">';
				}
				else
				{
					$element = '';
				};
				break;
			case 'teachername':
				if (isset($row->teachername))
				{
					$element = $row->teachername;
				}
				else
				{
					$element = '';
				}
				break;
			case 'jbsmedia':
				if (isset($row->mids))
				{
					$medias          = $this->getFluidMediaids($row);
					$mediafiles      = $this->getMediaFiles($medias);
					$row->mediafiles = $mediafiles;
					$element         = $this->getFluidMediaFiles($row, $params, $template);
				}
				else
				{
					$element = '';
				}
				break;
			case 'thumbnail':
				// Assume study thumbnail
				$element = '<img src="' . JURI::base() . $row->thumbnailm . '" alt="' . $row->studytitle . '">';
				break;
			case 'studytitle':
				(isset($row->studytitle) ? $element = $row->studytitle : $element = '');
				break;
			case 'teacher-title-name':
				if (isset($row->teachertitle) && isset($row->teachername))
				{
					$element = $row->teachertitle . ' ' . $row->teachername;
				}
				else
				{
					$element = '';
				}
				break;
				break;
			case 'topics':
				if (isset($row->topics_text))
				{
					if (substr_count($row->topics_text, ','))
					{
						$topics = explode(',', $row->topics_text);

						foreach ($topics as $key => $value)
						{
							$topics[$key] = JText::_($value);
						}
						$element = implode(', ', $topics);
					}
					else
					{
						(isset($row->topics_text) ? $element = JText::_($row->topics_text) : $element = '');
					}
				}
				break;
			case 'message_type':
				if (isset($row->message_type))
				{
					$element = $row->message_type;
				}
				else
				{
					$element = '';
				}
				break;
			case 'location_text':
				if (isset($row->location_text))
				{
					$element = $row->location_text;
				}
				else
				{
					$element = '';
				}
				break;
			case 'date':
				if (isset($row->studydate))
				{
					$element = self::getstudyDate($params, $row->studydate);
				}
				else
				{
					$element = '';
				}
				break;
			case 'series_description':

				if (isset($row->sdescription))
				{
					if ($type == 'seriesdisplays' || $type == 'seriesdisplay')
					{
						$element = JHtml::_('content.prepare', $row->description, '', 'com_biblestudy.' . $type);
					}
					else
					{
						$element = JHtml::_('content.prepare', $row->sdescription, '', 'com_biblestudy.' . $type);
					}
				}
				else
				{
					$element = '';
				}
				break;
			case 'hits':
				if (isset($row->hits))
				{
					$element = JText::_('JBS_CMN_HITS') . ' ' . $row->hits;
				}
				else
				{
					$element = '';
				}
				break;
		}

		return $element;
	}

	/**
	 * Get Scripture
	 *
	 * @param   object  $params        Item Params
	 * @param   object  $row           Row Info
	 * @param   string  $esv           ESV String
	 * @param   string  $scripturerow  Scripture Row
	 *
	 * @return string
	 */
	public function getScripture($params, $row, $esv, $scripturerow)
	{
		$scripture = '';
		$book      = '';

		if (!isset($row->id))
		{
			return null;
		}

		if (!isset($row->booknumber))
		{
			$row->booknumber = 0;
		}

		if (!isset($row->booknumber2))
		{
			$row->booknumber2 = 0;
		}

		if ($scripturerow == 2 && $row->booknumber2 >= 1)
		{
			$booknumber = $row->booknumber2;
			$ch_b       = $row->chapter_begin2;
			$ch_e       = $row->chapter_end2;
			$v_b        = $row->verse_begin2;
			$v_e        = $row->verse_end2;
			$book       = JText::_($row->bookname2);
		}
		elseif ($scripturerow == 1 && isset($row->booknumber) >= 1)
		{
			$booknumber = $row->booknumber;
			$ch_b       = $row->chapter_begin;
			$ch_e       = $row->chapter_end;
			$v_b        = $row->verse_begin;
			$v_e        = $row->verse_end;
			if (isset($row->bookname))
			{
				$book = JText::_($row->bookname);
			}
		}

		if (!isset($booknumber))
		{
			return $scripture;
		}
		$show_verses = $params->get('show_verses');

		if (!isset($row->bookname))
		{
			$scripture = '';

			return $scripture;
		}

		$b1  = ' ';
		$b2  = ':';
		$b2a = ':';
		$b3  = '-';

		if ($show_verses == 1)
		{
			/** @var $ch_b string */
			/** @var $v_b string */
			/** @var $ch_e string */
			/** @var $v_e string */
			if ($ch_e == $ch_b)
			{
				$ch_e = '';
				$b2a  = '';
			}
			if ($ch_e == $ch_b && $v_b == $v_e)
			{
				$b3   = '';
				$ch_e = '';
				$b2a  = '';
				$v_e  = '';
			}
			if ($v_b == 0)
			{
				$v_b = '';
				$v_e = '';
				$b2a = '';
				$b2  = '';
			}
			if ($v_e == 0)
			{
				$v_e = '';
				$b2a = '';
			}
			if ($ch_e == 0)
			{
				$b2a  = '';
				$ch_e = '';

				if ($v_e == 0)
				{
					$b3 = '';
				}
			}
			$scripture = $book . $b1 . $ch_b . $b2 . $v_b . $b3 . $ch_e . $b2a . $v_e;
		}
		// Else
		if ($show_verses == 0)
		{
			/** @var $ch_e string */
			/** @var $ch_b string */
			if ($ch_e > $ch_b)
			{
				$scripture = $book . $b1 . $ch_b . $b3 . $ch_e;
			}
			else
			{
				$scripture = $book . $b1 . $ch_b;
			}
		}
		if ($esv == 1)
		{
			/** @var $ch_b string */
			/** @var $v_b string */
			/** @var $ch_e string */
			/** @var $v_e string */
			if ($ch_e == $ch_b)
			{
				$ch_e = '';
				$b2a  = '';
			}
			if ($v_b == 0)
			{
				$v_b = '';
				$v_e = '';
				$b2a = '';
				$b2  = '';
			}
			if ($v_e == 0)
			{
				$v_e = '';
				$b2a = '';
			}
			if ($ch_e == 0)
			{
				$b2a  = '';
				$ch_e = '';

				if ($v_e == 0)
				{
					$b3 = '';
				}
			}
			$scripture = $book . $b1 . $ch_b . $b2 . $v_b . $b3 . $ch_e . $b2a . $v_e;
		}

		if ($row->booknumber > 166)
		{
			$scripture = $book;
		}

		if ($show_verses == 2)
		{
			$scripture = $book;
		}

		return $scripture;
	}

	/**
	 * Get Duration
	 *
	 * @param   Joomla\Registry\Registry  $params  Item Params
	 * @param   Object                    $row     Row info
	 *
	 * @return  null|string
	 */
	public function getDuration($params, $row)
	{

		$duration = $row->media_hours . $row->media_minutes . $row->media_seconds;

		if (!$duration)
		{
			$duration = null;

			return $duration;
		}
		$duration_type = $params->get('duration_type', 2);
		$hours         = $row->media_hours;
		$minutes       = $row->media_minutes;
		$seconds       = $row->media_seconds;

		switch ($duration_type)
		{
			case 1:
				if (!$hours)
				{
					$duration = $minutes . ' mins ' . $seconds . ' secs';
				}
				else
				{
					$duration = $hours . ' hour(s) ' . $minutes . ' mins ' . $seconds . ' secs';
				}
				break;
			case 2:
				if (!$hours)
				{
					$duration = $minutes . ':' . $seconds;
				}
				else
				{
					$duration = $hours . ':' . $minutes . ':' . $seconds;
				}
				break;
			default:
				$duration = $hours . ':' . $minutes . ':' . $seconds;
				break;

		} // End switch

		return $duration;
	}

	/**
	 * Get Fluid Media Files
	 *
	 * @param   Object                    $item      ?
	 * @param   Joomla\Registry\Registry  $params    ?
	 * @param   TableTemplate             $template  ?
	 *
	 * @return string
	 *
	 * @since 9.0.0
	 */
	public function getFluidMediaFiles($item, $params, $template)
	{
		$med      = new JBSMMedia;
		$mediarow = '<div style="display:inline;">';
		foreach ($item->mediafiles as $media)
		{
			$mediarow .= $med->getFluidMedia($media, $params, $template);
		}
		$mediarow .= '</div>';

		return $mediarow;
	}

	/**
	 * Get StudyDate
	 *
	 * @param   Joomla\Registry\Registry  $params     Item Params
	 * @param   string                    $studydate  Study Date
	 *
	 * @return string
	 */
	public function getStudyDate($params, $studydate)
	{
		switch ($params->get('date_format'))
		{
			case 0:
				$date = JHTML::_('date', $studydate, "M j, Y");
				break;
			case 1:
				$date = JHTML::_('date', $studydate, "M J");
				break;
			case 2:
				$date = JHTML::_('date', $studydate, "n/j/Y");
				break;
			case 3:
				$date = JHTML::_('date', $studydate, "n/j");
				break;
			case 4:
				$date = JHTML::_('date', $studydate, "l, F j, Y");
				break;
			case 5:
				$date = JHTML::_('date', $studydate, "F j, Y");
				break;
			case 6:
				$date = JHTML::_('date', $studydate, "j F Y");
				break;
			case 7:
				$date = JHTML::_('date', $studydate, "j/n/Y");
				break;
			case 8:
				$date = JHTML::_('date', $studydate, JText::_('DATE_FORMAT_LC'));
				break;
			case 9:
				$date = JHTML::_('date', $studydate, "Y/M/D");
				break;
			default:
				$date = JHTML::_('date', $studydate, "n/j");
				break;
		}

		$customDate = $params->get('custom_date_format');

		if ($customDate != '')
		{
			$date = JHTML::_('date', $studydate, $customDate);
		}

		return $date;
	}

	/**
	 * Use JImage Class
	 *
	 * @param   String  $path  Path to File
	 * @param   String  $alt   Alternate Text
	 *
	 * @return bool|stdClass
	 *
	 * @since 9.0.0
	 */
	public function useJImage($path, $alt = null)
	{
		$image = new JImage;

		try
		{
			$return = $image->getImageFileProperties($path);
		}
		catch (Exception $e)
		{
			$return = false;
		}
		$imagereturn = '<img src="' . JURI::base() . $path . '" alt="' . $alt . '" ' . $return->attributes . '>';

		return $imagereturn;
	}

	/**
	 *  Get Link
	 *
	 * @param   bool                      $islink      ?
	 * @param   string                    $id3         ?
	 * @param   int                       $tid         ?
	 * @param   object                    $smenu       ?
	 * @param   object                    $tmenu       ?
	 * @param   Joomla\Registry\Registry  $params      ?
	 * @param   object                    $row         ?
	 * @param   TableTemplate             $templateid  ?
	 *
	 * @return string
	 */
	private function getLink($islink, $id3, $tid, $smenu, $tmenu, $params, $row, $templateid)
	{
		$input    = new JInput;
		$Itemid   = $input->get('Itemid', '', 'int');
		$view     = $input->getString('view', '');
		$column   = '';
		$mime     = ' AND #__bsms_mediafiles.mime_type = 1';

		switch ($islink)
		{
			case 1 :
				$Itemid = $input->get('Itemid', '', 'int');

				if (!$Itemid)
				{
					$link = JRoute::_('index.php?option=com_biblestudy&view=sermon&id=' . $row->slug . '&t=' . $params->get('detailstemplateid'));

					if ($view == 'seriesdisplays')
					{
						$link = JRoute::_('index.php?option=com_biblestudy&view=seriesidsplay&id=' . $row->slug . '&t=' . $params->get('seriesdetailtemplateid'));
					}
				}
				else
				{
					$link = JRoute::_('index.php?option=com_biblestudy&view=sermon&id=' . $row->slug . '&t=' . $params->get('detailstemplateid'));

					if ($view == 'seriesdisplays')
					{
						$link = JRoute::_('index.php?option=com_biblestudy&view=seriesdisplay&id=' . $row->slug . '&t=' . $params->get('detailstemplateid'));
					}
				}
				$column = '<a href="' . $link . '">';
				break;

			case 2 :
				$filepath = $this->getFilepath($id3, 'study_id', $mime);
				$link     = JRoute::_($filepath);
				$column .= '<a href="' . $link . '">';
				break;

			case 3 :
				$link = JRoute::_('index.php?option=com_biblestudy&view=teacher&id=' . $tid . '&t=' . $params->get('teachertemplateid'));

				if ($tmenu > 0)
				{
					$link .= '&Itemid=' . $tmenu;
				}
				$column .= '<a href="' . $link . '">';
				break;

			case 4 :
				// Case 4 is a details link with tooltip
				if (!$Itemid)
				{
					$link = JRoute::_(JBSMHelperRoute::getArticleRoute($row->slug) . '&t=' . $params->get('detailstemplateid'));
				}
				else
				{
					$link = JRoute::_(JBSMHelperRoute::getArticleRoute($row->slug) . '&t=' . $params->get('detailstemplateid'));
				}
				$column = JBSMHelper::getTooltip($row, $params, $templateid);
				$column .= '<a href="' . $link . '">';

				break;

			case 5 :
				// Case 5 is a file link with Tooltip
				JFactory::getApplication()->enqueueMessage('Need to update this ASAP Listing->getLink->case5', 'error');

/*				$filepath = $this->getFilepath($id3, 'study_id', $mime);
				$link     = JRoute::_($filepath);
				$column   = JBSMHelper::getTooltip($row, $params, $templateid);
				$column  .= '<a href="' . $link . '">'; */
				break;

			case 6 :
				// Case 6 is for a link to the 1st article in the media file records
				$column .= '<a href="' . $this->getOtherlinks($id3, $islink, $params) . '">';
				break;

			case 7 :
				// Case 7 is for Virtuemart
				$column .= '<a href="' . $this->getOtherlinks($id3, $islink, $params) . '">';
				break;

			case 8 :
				// Case 8 is for Docman
				$column .= '<a href="' . $this->getOtherlinks($id3, $islink, $params) . '">';
				break;

			case 9 :
				// Case 9 is a link to download
				$column .= '<a href="index.php?option=com_biblestudy&amp;mid=' .
					$row->download_id . '&amp;view=sermons&amp;task=download">';
		}

		return $column;
	}

	/**
	 * Get File Path
	 *
	 * @param   string  $id3      ID
	 * @param   string  $idfield  ID Filed
	 * @param   string  $mime     MimeType ID
	 *
	 * @return string
	 */
	public function getFilepath($id3, $idfield, $mime = null)
	{
		JFactory::getApplication()->enqueueMessage('must remove fuction getFilepath');

		return false;
	}

	/**
	 * Get Other Links
	 *
	 * @param   int                       $id3     Study ID ID
	 * @param   string                    $islink  Is a Link
	 * @param   Joomla\Registry\Registry  $params  Item Params
	 *
	 * @return string
	 */
	public function getOtherlinks($id3, $islink, $params)
	{
		$link  = '';
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('#__bsms_mediafiles.*')
			->from('#__bsms_mediafiles')
			->where('study_id = ' . $db->q($id3))
			->where('#__bsms_mediafiles.published = 1');
		$db->setQuery($query);
		$db->execute();
		$num_rows = $db->getNumRows();

		if ($num_rows > 0)
		{
			$mediafiles = $db->loadObjectList();

			foreach ($mediafiles AS $media)
			{
				switch ($islink)
				{
					case 6:
						if ($media->article_id > 0)
						{
							$link = 'index.php?option=com_content&view=article&id=' . $media->article_id;
						}
						break;

					case 7:
						if ($media->virtueMart_id > 0)
						{
							$link = 'index.php?option=com_virtuemart&page=shop.product_details&flypage='
								. $params->get('store_page', 'flypage.tpl') . '&product_id=' . $media->virtueMart_id;
						}
						break;

					case 8:
						if ($media->docMan_id > 0)
						{
							$link = 'index.php?option=com_docman&task=doc_download&gid=' . $media->docMan_id;
						}
						break;
				}
			}
		}

		return $link;
	}

	/**
	 * Get Listing Exp
	 *
	 * @param   object                    $row       Item Info
	 * @param   Joomla\Registry\Registry  $params    Item Params
	 * @param   TableTemplate             $template  Template
	 *
	 * @return object
	 *
	 * @todo need to fix this as it relise on old code.
	 */
	public function getListingExp($row, $params, $template)
	{
		$Media  = new JBSMMedia;
		$images = new JBSMImages;
		$image  = $images->getStudyThumbnail($row->thumbnailm);
		$label  = $params->get('templatecode');
		$label  = str_replace('{{teacher}}', $row->teachername, $label);
		$label  = str_replace('{{title}}', $row->studytitle, $label);
		$label  = str_replace('{{date}}', $this->getStudydate($params, $row->studydate), $label);
		$label  = str_replace('{{studyintro}}', $row->studyintro, $label);
		$label  = str_replace('{{scripture}}', $this->getScripture($params, $row, 0, 1), $label);
		$label  = str_replace('{{topics}}', $row->topic_text, $label);
		$label  = str_replace('{{url}}', JRoute::_('index.php?option=com_biblestudy&view=sermon&id=' . $row->id . '&t=' . $template->id), $label);
		$label  = str_replace('{{mediatime}}', $this->getDuration($params, $row), $label);
		$label  = str_replace('{{thumbnail}}', '<img src="' . $image->path . '" width="' . $image->width . '" height="'
			. $image->height . '" id="bsms_studyThumbnail" />', $label
		);
		$label  = str_replace('{{seriestext}}', $row->series_text, $label);
		$label  = str_replace('{{messagetype}}', $row->message_type, $label);
		$label  = str_replace('{{bookname}}', $row->bookname, $label);
		$label  = str_replace('{{topics}}', $row->topic_text, $label);
		$label  = str_replace('{{hits}}', $row->hits, $label);
		$label  = str_replace('{{location}}', $row->location_text, $label);
		$label  = str_replace('{{plays}}', $row->totalplays, $label);
		$label  = str_replace('{{downloads}}', $row->totaldownloads, $label);

		// @todo need to replace this with a new fuction.
		// For now we need to use the existing mediatable function to get all the media
		$mediaTable = $Media->getMediaTable($row, $params);
		$label      = str_replace('{{media}}', $mediaTable, $label);

		// Need to add template items for media...

		return $label;
	}

	/**
	 * Get Study Exp
	 *
	 * @param   object                    $row     Item Info
	 * @param   Joomla\Registry\Registry  $params  Item Params
	 *
	 * @return object
	 *
	 * @deprecated 9.0.0
	 */
	public function getStudyExp($row, $params)
	{
		$Media  = new JBSMMedia;
		$images = new JBSMImages;
		$image  = $images->getStudyThumbnail($row->thumbnailm);
		$label  = $params->get('study_detailtemplate');
		$label  = str_replace('{{teacher}}', $row->teachername, $label);
		$label  = str_replace('{{title}}', $row->studytitle, $label);
		$label  = str_replace('{{date}}', $this->getStudydate($params, $row->studydate), $label);
		$label  = str_replace('{{studyintro}}', $row->studyintro, $label);
		$label  = str_replace('{{scripture}}', $this->getScripture($params, $row, 0, 1), $label);
		$label  = str_replace('{{topics}}', $row->topic_text, $label);
		$label  = str_replace('{{mediatime}}', $this->getDuration($params, $row), $label);
		$label  = str_replace('{{thumbnail}}', '<img src="' . $image->path . '" width="' . $image->width . '" height="'
			. $image->height . '" id="bsms_studyThumbnail" />', $label
		);
		$label  = str_replace('{{seriestext}}', $row->seriestext, $label);
		$label  = str_replace('{{messagetype}}', $row->message_type, $label);
		$label  = str_replace('{{bookname}}', $row->bname, $label);
		$label  = str_replace('{{studytext}}', $row->studytext, $label);
		$label  = str_replace('{{hits}}', $row->hits, $label);
		$label  = str_replace('{{location}}', $row->location_text, $label);

		// Passage
		$link  = '<strong><a class="heading" href="javascript:ReverseDisplay(\'bsms_scripture\')">>>' . JText::_('JBS_CMN_SHOW_HIDE_SCRIPTURE') . '<<</a>';
		$link .= '<div id="bsms_scripture" style="display:none;"></strong>';
		$response = $this->getPassage($params, $row);
		$link .= $response;
		$link .= '</div>';
		$label = str_replace('{{scripturelink}}', $link, $label);
		$label = str_replace('{{plays}}', $row->totalplays, $label);
		$label = str_replace('{{downloads}}', $row->totaldownloads, $label);

		$mediaTable = $Media->getMediaTable($row, $params);
		$label      = str_replace('{{media}}', $mediaTable, $label);

		// Share
		// Prepares a link string for use in social networking
		$u           = JURI::getInstance();
		$detailslink = htmlspecialchars($u->toString());
		$detailslink = JRoute::_($detailslink);

		// End social networking
		$share = $this->getShare($detailslink, $row, $params);
		$label = str_replace('{{share}}', $share, $label);

		// PrintableView
		$printview = JHTML::_('image.site', 'printButton.png', '/images/M_images/', null, null, JText::_('JBS_CMN_PRINT'));
		$printview = '<a href="#&tmpl=component" onclick="window.print();return false;">' . $printview . '</a>';

		$label = str_replace('{{printview}}', $printview, $label);

		// PDF View
		$url                = 'index.php?option=com_biblestudy&view=sermon&id=' . $row->id . '&format=pdf';
		$status             = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no';
		$text               = JHTML::_(
			'image.site', 'pdf24.png', '/media/com_biblestudy/images/', null, null, JText::_('JBS_MED_PDF'), JText::_('JBS_MED_PDF')
		);
		$attribs['title']   = JText::_('JBS_MED_PDF');
		$attribs['onclick'] = "window.open(this.href,'win2','" . $status . "'); return false;";
		$attribs['rel']     = 'nofollow';
		$link               = JHTML::_('link', JRoute::_($url), $text, $attribs);

		$label = str_replace('{{pdfview}}', $link, $label);

		// Comments

		return $label;
	}

	/**
	 * Get Passage
	 *
	 * @param   Joomla\Registry\Registry  $params  Item Params
	 * @param   object                    $row     Item Info
	 *
	 * @return string
	 */
	public function getPassage($params, $row)
	{
		$esv          = 1;
		$scripturerow = 1;
		$scripture    = $this->getScripture($params, $row, $esv, $scripturerow);

		if ($scripture)
		{
			$key      = "IP";
			$response = "" . $scripture . " (ESV)";
			$passage  = urlencode($scripture);
			$options  = "include-passage-references=false";
			$url      = "http://www.esvapi.org/v2/rest/passageQuery?key=$key&passage=$passage&$options";

			// This tests to see if the curl functions are there. It will return false if curl not installed
			$p = (get_extension_funcs("curl"));

			if ($p)
			{ // If curl is installed then we go on

				// This will return false if curl is not enabled
				$ch = curl_init($url);

				if ($ch)
				{ // This will return false if curl is not enabled
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					$response .= curl_exec($ch);
					curl_close($ch);

				} // End of if ($ch)

			} // End if ($p)
		}
		else
		{
			$response = JText::_('JBS_STY_NO_PASSAGE_INCLUDED');
		}

		return $response;
	}

	/**
	 * Share Helper file
	 *
	 * @param   string                    $link    Link
	 * @param   object                    $row     Item Info
	 * @param   Joomla\Registry\Registry  $params  Item Params
	 *
	 * @return null|string
	 */
	public function getShare($link, $row, $params)
	{
		jimport('joomla.html.parameter');

		// Find a better way to do this.
		$template = new TableTemplate(JFactory::getDbo());
		$template->id = (int) '1';

		$sharetype = $params->get('sharetype', 1);

		if ($sharetype == 1)
		{
			$shareit = '<div class="row-fluid">
						<div class="span2 pull-right">
						';
			$shareit .= '<!-- AddThis Button BEGIN -->
						<a class="addthis_button" href="//www.addthis.com/bookmark.php?v=250">
						<img src="//s7.addthis.com/static/btn/v2/lg-share-en.gif" width="125" height="16" border="0" alt="Share" />
						</a>
						<script type="text/javascript">var addthis_config = {"data_track_clickback":true};</script>
						<script type="text/javascript" src="//s7.addthis.com/js/250/addthis_widget.js"></script>
						<!-- AddThis Button END --></div>';
		}
		else
		{
			// This will come from $admin_params
			$sharetitle = $params->get('sharelabel');

			// Get the information from the database on what social networking sites to use
			$db    = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('*')->from('#__bsms_share')->where('published = ' . 1)->order('name asc');
			$db->setQuery($query);
			$rows      = $db->loadObjectList();
			$sharerows = count($rows);

			if ($sharerows < 1)
			{
				$share = null;

				return $share;
			}

			// Begin to form the table
			$shareit = '<div class="row-fluid bsmsshareiconrow">' . $sharetitle;

			foreach ($rows as $sharerow)
			{

				// Convert parameter fields to objects.
				$registry = new Registry;
				$registry->loadString($sharerow->params);
				$share_params = $registry;
				$custom       = new JBSMCustom;

				$image      = $share_params->get('shareimage');
				$height     = $share_params->get('shareimageh', '44px');
				$width      = $share_params->get('shareimagew', '44px');
				$use_bitly  = $share_params->get('use_bitly');
				$mainlink   = $share_params->get('mainlink');
				$appkey     = $share_params->get('api', 'R_dc86635ad2d1e883cab8fad316ca12f6');
				$login      = $share_params->get('username', 'joomlabiblestudy');

				if ($use_bitly == 1)
				{
					$url = $this->make_bitly_url($link, $login, $appkey, 'json', '2.0.1');
				}
				else
				{
					$url = $link;
				}
				$element1          = new stdClass;
				$element1->element = '';
				$element2          = new stdClass;
				$element2->element = '';
				$element3          = new stdClass;
				$element3->element = '';
				$element4          = new stdClass;
				$element4->element = '';

				if ($share_params->get('item1'))
				{
					if ($share_params->get('item1') == 200)
					{
						$element1->element = $url;
					}
					elseif ($share_params->get('item1') == 24)
					{
						$element           = $custom->getCustom(
							$share_params->get('item1'), $share_params->get('item1custom'), $row, $params, $template
						);
						$element1->element = $element->element;
					}
					else
					{
						$element1 = self::getElement($share_params->get('item1'), $row, $params, $template, $type = 0);
					}
				}
				if ($share_params->get('item2'))
				{
					if ($share_params->get('item2') == 200)
					{
						$element2->element = $url;
					}
					elseif ($share_params->get('item2') == 24)
					{
						$element           = $custom->getCustom(
							$share_params->get('item2'), $share_params->get('item2custom'), $row, $params, $template
						);
						$element2->element = $element->element;
					}
					else
					{
						$element2 = self::getElement((int) $share_params->get('item2'), $row, $params, $template, $type = 0);
					}
				}
				if ($share_params->get('item3'))
				{
					if ($share_params->get('item3') == 200)
					{
						$element3->element = $url;
					}
					elseif ($share_params->get('item3') == 24)
					{
						$element           = $custom->getCustom(
							$share_params->get('item3'), $share_params->get('item3custom'),
							$row, $params, $template
						);
						$element3->element = $element->element;
					}
					else
					{
						$element3 = self::getElement($share_params->get('item3'), $row, $params, $template, $type = 0);
					}
				}
				if ($share_params->get('item4'))
				{
					if ($share_params->get('item4') == 200)
					{
						$element4->element = $url;
					}
					elseif ($share_params->get('item4') == 24)
					{
						$element           = $custom->getCustom(
							$share_params->get('item4'), $share_params->get('item4custom'), $row, $params, $template
						);
						$element4->element = $element->element;
					}
					else
					{
						$element4 = self::getElement($share_params->get('item4'), $row, $params, $template, $type = 0);
					}
				}

				$sharelink = $element1->element . ' ' . $share_params->get('item2prefix') . $element2->element . ' ' . $share_params->get('item3prefix')
					. $element3->element . ' ' . $share_params->get('item4prefix') . $element4->element;

				// Added to see if would make Facebook sharer work
				$sharelink = urlencode($sharelink);

				if ($share_params->get('totalcharacters'))
				{
					$sharelength = strlen($sharelink);

					if ($sharelength > $share_params->get('totalcharacters'))
					{
						$linkstartposition  = strpos($sharelink, '//', 0);
						$linkendposition    = strpos($sharelink, ' ', $linkstartposition);
						$linkextract        = substr($sharelink, $linkstartposition, $linkendposition);
						$linklength         = strlen($linkextract);
						$sharelink          = substr_replace($sharelink, '', $linkstartposition, $linkendposition);
						$newsharelinklength = $share_params->get('totalcharacters') - $linklength - 1;
						$sharelink          = substr($sharelink, 0, $newsharelinklength);
						$sharelink          = $sharelink . ' ' . $linkextract;
					}
				}
				$shareit .= '
							<a href="' . $mainlink . $share_params->get('item1prefix') . $sharelink . '" target="_blank">
							<img src="' . JURI::base() . $image . '" alt="' . $share_params->get('alttext') . '" title="'
					. $share_params->get('alttext') . '" width="' . $width . '" height="' . $height . '" border="0">
							</a>';

			} // End of foreach

		} // End of else $sharetype
		$shareit .= '</div>';

		return $shareit;
	}

	/**
	 * make a URL small
	 *
	 * @param   string  $url      Url
	 * @param   string  $login    Login
	 * @param   string  $appkey   AppKey
	 * @param   string  $format   Format
	 * @param   string  $version  Version
	 *
	 * @return string
	 */
	private function make_bitly_url($url, $login, $appkey, $format = 'xml', $version = '2.0.1')
	{
		// Create the URL
		$bitly = 'http://api.bit.ly/shorten?version=' . $version . '&longUrl=' . urlencode($url) . '&login='
			. $login . '&apiKey=' . $appkey . '&format=' . $format;

		// Get the url
		// Could also use cURL here
		$response = file_get_contents($bitly);

		// Parse depending on desired format
		if (strtolower($format) == 'json')
		{
			$json  = json_decode($response, true);
			$short = $json['results'][$url]['shortUrl'];
		}
		else
		{ // Xml
			$xml   = simplexml_load_string($response);
			$short = 'http://bit.ly/' . $xml->results->nodeKeyVal->hash;
		}

		return $short;
	}

}
