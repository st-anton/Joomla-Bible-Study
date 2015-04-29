<?php
/**
 * Part of Joomla BibleStudy Package
 *
 * @package    BibleStudy.Admin
 * @copyright  2007 - 2015 (C) Joomla Bible Study Team All rights reserved
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.JoomlaBibleStudy.org
 * */
// No Direct Access
defined('_JEXEC') or die;

use Joomla\Registry\Registry;

/**
 * Class for JBSMLanding
 *
 * @package  BibleStudy.Site
 * @since    8.0.0
 */
class JBSMLanding
{
	/**
	 * Get Locations for Landing Page
	 *
	 * @param   Joomla\Registry\Registry  $params  Item Params
	 * @param   int                       $id      Item ID
	 *
	 * @return string
	 */
	public function getLocationsLandingPage($params, $id)
	{
		$mainframe   = JFactory::getApplication();
		$user        = JFactory::getUser();
		$db          = JFactory::getDBO();
		$location    = null;
		$teacherid   = null;
		$template    = $params->get('studieslisttemplateid', 1);
		$limit       = $params->get('landinglocationslimit');
		$order       = 'ASC';

		if (!$limit)
		{
			$limit = 10000;
		}
		$locationuselimit = $params->get('landinglocationsuselimit', 0);
		$menu             = $mainframe->getMenu();
		$item             = $menu->getActive();
		$registry         = new Registry;

		if (isset($item->prams))
		{
			$registry->loadString($item->params);
			$m_params   = $registry;
			$language   = $db->quote($item->language) . ',' . $db->quote('*');
			$menu_order = $m_params->get('locations_order');
		}
		else
		{
			$language   = $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*');
			$menu_order = null;
		}
		if ($language == '*' || !$language)
		{
			$langlink = '';
		}
		elseif ($language != '*' && isset($item->language))
		{
			$langlink = '&amp;filter.languages=' . $item->language;
		}
		if ($menu_order)
		{
			switch ($menu_order)
			{
				case 2:
					$order = 'ASC';
					break;
				case 1:
					$order = 'DESC';
					break;
			}
		}
		else
		{
			$order = $params->get('landing_default_order', 'ASC');
		}
		// Compute view access permissions.
		$groups = implode(',', $user->getAuthorisedViewLevels());

		$query = $db->getQuery(true);
		$query->select('distinct a.*')
			->from('#__bsms_locations a')
			->select('b.access')
			->innerJoin('#__bsms_studies b on a.id = b.location_id')
			->where('b.location_id > 0')
			->where('a.published = 1')
			->where('b.published = 1')
			->where('b.language in (' . $language . ')')
			->where('b.access IN (' . $groups . ')')
			->where('a.landing_show > 0')
			->order('a.location_text ' . $order);
		$db->setQuery($query);

		$tresult = $db->loadObjectList();
		$count   = count($tresult);

		if ($count > 0)
		{
			switch ($locationuselimit)
			{
				case 0:
					$t = 0;
					$i = 0;

					$location = "\n" . '<table class="table landing_table" width="100%"><tr>';
					$showdiv  = 0;

					foreach ($tresult as $b)
					{

						if ($t >= $limit)
						{
							if ($showdiv < 1)
							{
								if ($i == 1)
								{
									$location .= "\n\t\t" . '<td  class="landing_td"></td>' . "\n\t\t" . '<td class="landing_td"></td>';
									$location .= "\n\t" . '</tr>';
								}
								if ($i == 2)
								{
									$location .= "\n\t\t" . '<td  class="landing_td"></td>';
									$location .= "\n\t" . '</tr>';
								}

								$location .= "\n" . '</table>';
								$location .= "\n\t" . '<div id="showhidelocations" style="display:none;"> <!-- start show/hide locations div-->';
								$location .= "\n" . '<table width="100%" class="table landing_table"><tr>';

								$i       = 0;
								$showdiv = 1;
							}
						}
						if ($i == 0)
						{
							$location .= "\n\t" . '<tr>';
						}
						$location .= "\n\t\t" . '<td class="landing_td">';
						$location .= '<a href="index.php?option=com_biblestudy&amp;view=sermons&amp;filter_location='
							. $b->id . '&amp;filter_teacher=0&amp;filter_series=0&amp;filter_topic=0&amp;filter_book=0&amp;filter_year=0&amp;filter_messagetype=0&amp;t='
							. $template . '">';

						$location .= $b->location_text;

						$location .= '</a>';

						$location .= '</td>';
						$i++;
						$t++;

						if ($i == 3 && $t != $limit && $t != $count)
						{
							$location .= "\n\t" . '</tr><tr>';
							$i = 0;
						}
						elseif ($i == 3 || $t == $count || $t == $limit)
						{
							$location .= "\n\t" . '</tr>';
							$i = 0;
						}
					}
					if ($i == 1)
					{
						$location .= "\n\t\t" . '<td  class="landing_td"></td>' . "\n\t\t" . '<td class="landing_td"></td>';
					}
					if ($i == 2)
					{
						$location .= "\n\t\t" . '<td  class="landing_td"></td>';
					}

					$location .= "\n" . '</table>' . "\n";

					if ($showdiv == 1)
					{

						$location .= "\n\t" . '</div> <!-- close show/hide locations div-->';
						$showdiv = 2;
					}
					$location .= '<div class="landing_separator"></div>';
					break;

				case 1:

					$location = '<div class="landingtable" style="display:inline-block;">';

					foreach ($tresult as $b)
					{
						if ($b->landing_show == 1)
						{
							$location .= '<div class="landingrow">';
							$location .= '<div class="landingcell">
							<a class="landinglink" href="index.php?option=com_biblestudy&amp;view=sermons&amp;filter_location='
								. $b->id . '&amp;filter_teacher=0&amp;filter_series=0&amp;filter_topic=0&amp;filter_book=0&amp;filter_year=0&amp;filter_messagetype=0&amp;t='
								. $template . '">';
							$location .= $b->location_text;
							$location .= '</a></div>';
							$location .= '</div>';
						}
					}

					$location .= '</div>';
					$location .= '<div id="showhidelocations" style="display:none;">';

					foreach ($tresult as $b)
					{
						if ($b->landing_show == 2)
						{
							$location .= '<div class="landingrow">';
							$location .= '<div class="landingcell">
							<a class="landinglink" href="index.php?option=com_biblestudy&amp;view=sermons&amp;filter_location='
								. $b->id . '&amp;filter_teacher=0&amp;filter_series=0&amp;filter_topic=0&amp;filter_book=0&amp;filter_year=0&amp;filter_messagetype=0&amp;t='
								. $template . '">';
							$location .= $b->location_text;
							$location .= '</a></div>';
							$location .= '</div>';
						}
					}

					$location .= '</div>';
					$location .= '<div class="landing_separator"></div>';
					break;
			}
		}
		else
		{
			$location = '<div class="landing_separator"></div>';
		}

		return $location;
	}

	/**
	 * Get Teacher for LandingPage
	 *
	 * @param   Joomla\Registry\Registry  $params  Item Params
	 * @param   int                       $id      Item ID
	 *
	 * @return string
	 */
	public function getTeacherLandingPage($params, $id)
	{
		$mainframe = JFactory::getApplication();
		$db        = JFactory::getDBO();
		$user      = JFactory::getUser();
		$langlink  = JLanguageMultilang::isEnabled();
		$order     = null;
		$teacher   = null;
		$teacherid = null;

		$template        = $params->get('teachertemplateid', 1);
		$limit           = $params->get('landingteacherslimit', 10000);
		$teacheruselimit = $params->get('landingteachersuselimit', 0);
		$menu            = $mainframe->getMenu();
		$item            = $menu->getActive();
		$registry        = new Registry;

		if (isset($item->params))
		{
			$registry->loadString($item->params);
			$m_params   = $registry;
			$language   = $db->quote($item->language) . ',' . $db->quote('*');
			$menu_order = $m_params->get('teachers_order');
		}
		else
		{
			$language   = $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*');
			$menu_order = null;
		}
		if ($language == '*' || !$language)
		{
			$langlink = '';
		}
		elseif ($language != '*' && isset($item->language))
		{
			$langlink = '&amp;filter.languages=' . $item->language;
		}
		if ($menu_order)
		{
			switch ($menu_order)
			{
				case 2:
					$order = 'ASC';
					break;
				case 1:
					$order = 'DESC';
					break;
			}
		}
		else
		{
			$order = $params->get('landing_default_order', 'ASC');
		}
		// Compute view access permissions.
		$groups = implode(',', $user->getAuthorisedViewLevels());

		$query = $db->getQuery(true);
		$query->select('distinct a.*')
			->from('#__bsms_teachers a')
			->select('b.access')
			->innerJoin('#__bsms_studies b on a.id = b.teacher_id')
			->where('b.language in (' . $language . ')')
			->where('a.list_show = 1 and a.published = 1')
			->where('b.access IN (' . $groups . ')')
			->where('b.published = 1')
			->where('a.landing_show > 0')
			->order('a.ordering, a.teachername ' . $order);
		$db->setQuery($query);

		$tresult = $db->loadObjectList();
		$count   = count($tresult);
		$t       = 0;
		$i       = 0;

		if ($count > 0)
		{
			$teacher = "\n" . '<table class="table landing_table" width="100%"><tr>';
			$showdiv = 0;

			switch ($teacheruselimit)
			{
				case 0:
					foreach ($tresult as $b)
					{

						if ($t >= $limit)
						{
							if ($showdiv < 1)
							{
								if ($i == 1)
								{
									$teacher .= "\n\t\t" . '<td  class="landing_td"></td>' . "\n\t\t" . '<td class="landing_td"></td>';
									$teacher .= "\n\t" . '</tr>';
								}
								if ($i == 2)
								{
									$teacher .= "\n\t\t" . '<td  class="landing_td"></td>';
									$teacher .= "\n\t" . '</tr>';
								}

								$teacher .= "\n" . '</table>';
								$teacher .= "\n\t" . '<div id="showhideteachers" style="display:none;"> <!-- start show/hide teacher div-->';
								$teacher .= "\n" . '<table width="100%" class="table landing_table"><tr>';

								$i       = 0;
								$showdiv = 1;
							}
						}
						$teacher .= "\n\t\t" . '<td class="landing_td">';

						if ($params->get('linkto') == 0)
						{
							$teacher .= '<a href="' . JRoute::_('index.php?option=com_biblestudy&amp;view=sermons&amp;t=' . $template)
								. '&amp;filter_teacher=' . $b->id
								. $langlink . '&amp;filter_book=0&amp;filter_series=0&amp;filter_topic=0&amp;filter_location=0&amp;filter_year=0&amp;filter_messagetype=0">';
						}
						else
						{

							$teacher .= '<a href="' . JRoute::_('index.php?option=com_biblestudy&amp;view=teacher&id=' . $b->id . $langlink . '&t=' . $template) . '">';
						}
						$teacher .= $b->teachername;

						$teacher .= '</a>';

						$teacher .= '</td>';
						$i++;
						$t++;

						if ($i == 3 && $t != $limit && $t != $count)
						{
							$teacher .= "\n\t" . '</tr><tr>';
							$i = 0;
						}
						elseif ($i == 3 || $t == $count || $t == $limit)
						{
							$teacher .= "\n\t" . '</tr>';
							$i = 0;
						}
					}
					if ($i == 1)
					{
						$teacher .= "\n\t\t" . '<td  class="landing_td"></td>' . "\n\t\t" . '<td class="landing_td"></td>';
					}

					if ($i == 2)
					{
						$teacher .= "\n\t\t" . '<td  class="landing_td"></td>';
					}

					$teacher .= "\n" . '</table>' . "\n";

					if ($showdiv == 1)
					{

						$teacher .= "\n\t" . '</div> <!-- close show/hide teacher div-->';
						$showdiv = 2;
					}
					$teacher .= '<div class="landing_separator"></div>';
					break;

				case 1:

					$teacher = '<div class="landingtable" style="display:inline;">';

					foreach ($tresult as $b)
					{
						if ($b->landing_show == 1)
						{
							$teacher .= '<div class="landingrow">';

							if ($params->get('linkto') == 0)
							{
								$teacher .= '<div class="landingcell"><a class="landinglink="'
									. JRoute::_('index.php?option=com_biblestudy&amp;view=sermons&amp;t=' . $template)
									. '&amp;filter_teacher=' . $b->id
									. '&amp;filter_book=0&amp;filter_series=0&amp;filter_topic=0&amp;filter_location=0&amp;filter_year=0&amp;filter_messagetype=0">';
							}
							else
							{

								$teacher .= '<div class="landingcell"><a class="landinglink" href="'
									. JRoute::_('index.php?option=com_biblestudy&amp;view=teacher&amp;id=' . $b->id . '&amp;t=' . $template) . '">';
							}
							$teacher .= $b->teachername;

							$teacher .= '</a></div></div>';
						}
					}
					$teacher .= '</div>';
					$teacher .= '<div id="showhideteachers" style="display:none;">';

					foreach ($tresult as $b)
					{

						if ($b->landing_show == 2)
						{
							$teacher .= '<div class="landingrow">';

							if ($params->get('linkto') == 0)
							{
								$teacher .= '<div class="landingcell"><a class="landinglink" href="'
									. JRoute::_('index.php?option=com_biblestudy&amp;view=sermons&amp;t=' . $template)
									. '&amp;filter_teacher=' . $b->id
									. '&amp;filter_book=0&amp;filter_series=0&amp;filter_topic=0&amp;filter_location=0&amp;filter_year=0&amp;filter_messagetype=0">';
							}
							else
							{

								$teacher .= '<div class="landingcell"><a class="landinglink" href="'
									. JRoute::_('index.php?option=com_biblestudy&amp;view=teacher&amp;id=' . $b->id . '&amp;t=' . $template) . '">';
							}
							$teacher .= $b->teachername;

							$teacher .= '</a></div></div>';
						}
					}

					$teacher .= '</div>';
					$teacher .= '<div class="landing_separator"></div>';
					break;
			}
		}
		else
		{
			$teacher = '<div class="landing_separator"></div>';
		}

		return $teacher;
	}

	/**
	 * Get Series for LandingPage
	 *
	 * @param   Joomla\Registry\Registry  $params  Item Params
	 * @param   int                       $id      ID
	 *
	 * @return string
	 *
	 * @todo look like $numRows was not defined not sure if needed. TOM
	 */
	public function getSeriesLandingPage($params, $id)
	{
		$mainframe = JFactory::getApplication();
		$user      = JFactory::getUser();
		$db        = JFactory::getDBO();
		$order     = 'ASC';
		$series    = null;
		$seriesid  = null;
		$numRows   = null;

		$template = $params->get('serieslisttemplateid', 1);
		$limit    = $params->get('landingserieslimit');

		if (!$limit)
		{
			$limit = 10000;
		}
		$seriesuselimit = $params->get('landingseriesuselimit', 0);
		$menu           = $mainframe->getMenu();
		$item           = $menu->getActive();
		$registry       = new Registry;

		if (isset($item->prams))
		{
			$registry->loadString($item->params);
			$m_params   = $registry;
			$language   = $db->quote($item->language) . ',' . $db->quote('*');
			$menu_order = $m_params->get('series_order');
		}
		else
		{
			$language   = $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*');
			$menu_order = null;
		}
		if ($menu_order)
		{
			switch ($menu_order)
			{
				case 2:
					$order = 'ASC';
					break;
				case 1:
					$order = 'DESC';
					break;
			}
		}
		else
		{
			$order = $params->get('landing_default_order', 'ASC');
		}
		// Compute view access permissions.
		$groups = implode(',', $user->getAuthorisedViewLevels());

		$query = $db->getQuery(true);
		$query->select('distinct a.*')
			->from('#__bsms_series a')
			->select('b.access')
			->innerJoin('#__bsms_studies b on a.id = b.series_id')
			->where('a.language in (' . $language . ')')
			->where('b.access IN (' . $groups . ')')
			->where('b.published = 1')
			->order('a.series_text ' . $order);
		$db->setQuery($query);

		$items = $db->loadObjectList();
		$count = count($items);

		if ($count != 0)
		{
			switch ($seriesuselimit)
			{
				case 0:
					$series = "\n" . '<table class="table landing_table" width="100%" >';

					$t = 0;
					$i = 0;

					$series .= "\n\t" . '<tr>';
					$showdiv = 0;

					foreach ($items as &$b)
					{
						if ($t >= $limit)
						{
							if ($showdiv < 1)
							{
								if ($i == 1)
								{
									$series .= "\n\t\t" . '<td  class="landing_td"></td>' . "\n\t\t" . '<td class="landing_td"></td>';
									$series .= "\n\t" . '</tr>';
								}
								if ($i == 2)
								{
									$series .= "\n\t\t" . '<td  class="landing_td"></td>';
									$series .= "\n\t" . '</tr>';
								}

								$series .= "\n" . '</table>';
								$series .= "\n\t" . '<div id="showhideseries" style="display:none;"> <!-- start show/hide series div-->';
								$series .= "\n" . '<table width="100%" class="table landing_table"><tr>';

								$i       = 0;
								$showdiv = 1;
							}
						}
						$series .= "\n\t\t" . '<td class="landing_td">';

						if ($params->get('series_linkto') == '0')
						{
							$series .= '<a href="index.php?option=com_biblestudy&amp;view=sermons&amp;filter_series=' . $b->id
								. '&amp;filter_book=0&amp;filter_teacher=0'
								. '&amp;filter_topic=0&amp;filter_location=0&amp;filter_year=0&amp;filter_messagetype=0&amp;t='
								. $template . '">';
						}
						else
						{
							$series .= '<a href="index.php?option=com_biblestudy&amp;view=seriesdisplay&amp;id=' . $b->id . '&amp;t=' . $template . '">';
						}

						$series .= $b->series_text;

						$series .= '</a>';

						$series .= '</td>';

						$i++;
						$t++;

						if ($i == 3 && $t != $limit && $t != $count)
						{
							$series .= "\n\t" . '</tr><tr>';
							$i = 0;
						}
						elseif ($i == 3 || $t == $count || $t == $limit)
						{
							$series .= "\n\t" . '</tr>';
							$i = 0;
						}
					}
					if ($i == 1)
					{
						$series .= "\n\t\t" . '<td  class="landing_td"></td>' . "\n\t\t" . '<td class="landing_td"></td>';
					}

					if ($i == 2)
					{
						$series .= "\n\t\t" . '<td  class="landing_td"></td>';
					}

					$series .= "\n" . '</table>' . "\n";

					if ($showdiv == 1)
					{
						$series .= "\n\t" . '</div> <!-- close show/hide series div-->';
						$showdiv = 2;
					}
					$series .= '<div class="landing_separator"></div>';

					break;

				case 1:
					$series = '<div class="landingtable" style="display:inline;">';

					foreach ($items as $b)
					{
						if ($b->landing_show == 1)
						{
							$series .= '<div class="landingrow">';

							if ($params->get('series_linkto') == '0')
							{
								$series .= '<div class="landingcell"><a href="index.php?option=com_biblestudy&amp;view=sermons&amp;filter_series='
									. $b->id . '&amp;filter_book=0&amp;filter_teacher=0'
									. '&amp;filter_topic=0&amp;filter_location=0&amp;filter_year=0&amp;filter_messagetype=0&amp;t=' . $template . '">';
							}
							else
							{
								$series .= '<div class="landingcell"><a href="index.php?option=com_biblestudy&amp;view=seriesdisplay&amp;id='
									. $b->id . '&amp;t=' . $template . '">';
							}

							$series .= $numRows;
							$series .= $b->series_text;

							$series .= '</a></div></div>';
						}
					}
					$series .= '</div>';
					$series .= '<div id="showhideseries" style="display:none;">';

					foreach ($items as $b)
					{

						if ($b->landing_show == 2)
						{
							$series .= '<div class="landingrow">';

							if ($params->get('series_linkto') == '0')
							{
								$series .= '<div class="landingcell"><a href="index.php?option=com_biblestudy&amp;view=sermons&amp;filter_series='
									. $b->id . '&amp;filter_book=0&amp;filter_teacher=0'
									. '&amp;filter_topic=0&amp;filter_location=0&amp;filter_year=0&amp;filter_messagetype=0&amp;t=' . $template . '">';
							}
							else
							{
								$series .= '<div class="landingcell"><a href="index.php?option=com_biblestudy&amp;view=seriesdisplay&amp;id='
									. $b->id . '&amp;t=' . $template . '">';
							}

							$series .= $numRows;
							$series .= $b->series_text;

							$series .= '</a></div></div>';
						}
					}

					$series .= '</div>';
					$series .= '<div class="landing_separator"></div>';
					break;
			}
		}
		else
		{
			$series = '<div class="landing_separator"></div>';
		}

		return $series;
	}

	/**
	 * Get Years for Landing Page
	 *
	 * @param   Joomla\Registry\Registry  $params  Item Params
	 * @param   int                       $id      Item ID
	 *
	 * @return string
	 */
	public function getYearsLandingPage($params, $id)
	{
		$mainframe = JFactory::getApplication();
		$db        = JFactory::getDBO();
		$user      = JFactory::getUser();
		$input     = new JInput;
		$order     = 'ASC';
		$year      = null;
		$teacherid = null;
		$template  = $params->get('studieslisttemplateid');
		$limit     = $params->get('landingyearslimit');

		if (!$limit)
		{
			$limit = 10000;
		}
		$menu     = $mainframe->getMenu();
		$item     = $menu->getActive();
		$registry = new Registry;

		if (isset($item->params))
		{
			$registry->loadString($item->params);
			$m_params   = $registry;
			$language   = $db->quote($item->language) . ',' . $db->quote('*');
			$menu_order = $m_params->get('years_order');
		}
		else
		{
			$language   = $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*');
			$menu_order = null;
		}
		if ($menu_order)
		{
			switch ($menu_order)
			{
				case 2:
					$order = 'ASC';
					break;
				case 1:
					$order = 'DESC';
					break;
			}
		}
		else
		{
			$order = $params->get('landing_default_order', 'ASC');
		}
		// Compute view access permissions.
		$groups = implode(',', $user->getAuthorisedViewLevels());

		$query = $db->getQuery(true);
		$query->select('distinct year(studydate) as theYear')
			->from('#__bsms_studies')
			->where('language in (' . $language . ')')
			->where('access IN (' . $groups . ')')
			->where('published = 1')
			->order('year(studydate) ' . $order);
		$db->setQuery($query);

		$tresult = $db->loadObjectList();
		$count   = count($tresult);
		$t       = 0;
		$i       = 0;

		if ($count > 0)
		{
			$year    = "\n" . '<table class="table landing_table" width="100%"><tr>';
			$showdiv = 0;

			foreach ($tresult as &$b)
			{
				if ($t >= $limit)
				{
					if ($showdiv < 1)
					{
						if ($i == 1)
						{
							$year .= "\n\t\t" . '<td class="landing_td"></td>' . "\n\t\t" . '<td class="landing_td"></td>';
							$year .= "\n\t" . '</tr>';
						}
						if ($i == 2)
						{
							$year .= "\n\t\t" . '<td  class="landing_td"></td>';
							$year .= "\n\t" . '</tr>';
						}

						$year .= "\n" . '</table>';
						$year .= "\n\t" . '<div id="showhideyears" style="display:none;"> <!-- start show/hide years div-->';
						$year .= "\n" . '<table width="100%" class="table landing_table"><tr>';

						$i       = 0;
						$showdiv = 1;
					}
				}
				$year .= "\n\t\t" . '<td class="landing_td">';

				$year .= '<a href="index.php?option=com_biblestudy&amp;view=sermons&amp;filter_year='
					. $b->theYear . '&amp;filter_teacher=0&amp;filter_series=0&amp;filter_topic=0&amp;filter_location=0&amp;'
					. 'filter_book=0&amp;filter_messagetype=0&amp;t='
					. $template . '">';

				$year .= $b->theYear;

				$year .= '</a>';

				$year .= '</td>';
				$i++;
				$t++;

				if ($i == 3 && $t != $limit && $t != $count)
				{
					$year .= "\n\t" . '</tr><tr>';
					$i = 0;
				}
				elseif ($i == 3 || $t == $count || $t == $limit)
				{
					$year .= "\n\t" . '</tr>';
					$i = 0;
				}
			}
			if ($i == 1)
			{
				$year .= "\n\t\t" . '<td  class="landing_td"></td>' . "\n\t\t" . '<td class="landing_td"></td>';
			}
			if ($i == 2)
			{
				$year .= "\n\t\t" . '<td  class="landing_td"></td>';
			}

			$year .= "\n" . '</table>' . "\n";

			if ($showdiv == 1)
			{

				$year .= "\n\t" . '</div> <!-- close show/hide years div-->';
				$showdiv = 2;
			}
			$year .= '<div class="landing_separator"></div>';
		}
		else
		{

			$year = '';
		}

		return $year;
	}

	/**
	 * Get Topics for LandingPage
	 *
	 * @param   Joomla\Registry\Registry  $params  Item Params
	 * @param   int                       $id      ID
	 *
	 * @return string
	 */
	public function getTopicsLandingPage($params, $id)
	{
		$mainframe = JFactory::getApplication();
		$user      = JFactory::getUser();
		$db        = JFactory::getDBO();
		$input     = new JInput;
		$order     = 'ASC';
		$topic     = null;
		$teacherid = null;
		$template  = $params->get('studieslisttemplateid');
		$limit     = $params->get('landingtopicslimit');

		if (!$limit)
		{
			$limit = 10000;
		}
		$t        = $input->get('t', 1, 'int');
		$menu     = $mainframe->getMenu();
		$item     = $menu->getActive();
		$registry = new Registry;

		if (isset($item->prams))
		{
			$registry->loadString($item->params);
			$m_params   = $registry;
			$language   = $db->quote($item->language) . ',' . $db->quote('*');
			$menu_order = $m_params->get('topics_order');
		}
		else
		{
			$language   = $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*');
			$menu_order = null;
		}
		if ($menu_order)
		{
			switch ($menu_order)
			{
				case 2:
					$order = 'ASC';
					break;
				case 1:
					$order = 'DESC';
					break;
			}
		}
		else
		{
			$order = $params->get('landing_default_order', 'ASC');
		}
		// Compute view access permissions.
		$groups = implode(',', $user->getAuthorisedViewLevels());

		$query = $db->getQuery('true');
		$query->select('DISTINCT #__bsms_studies.access as access, #__bsms_topics.id, #__bsms_topics.topic_text, #__bsms_topics.params AS topic_params')
			->from('#__bsms_studies')
			->join('LEFT', '#__bsms_studytopics ON #__bsms_studies.id = #__bsms_studytopics.study_id')
			->join('LEFT', '#__bsms_topics ON #__bsms_topics.id = #__bsms_studytopics.topic_id')
			->where('#__bsms_topics.published = 1')
			->where('#__bsms_studies.published = 1')
			->order('#__bsms_topics.topic_text ' . $order)
			->where('#__bsms_studies.language in (' . $language . ')')
			->where('#__bsms_studies.access IN (' . $groups . ')');
		$db->setQuery($query);

		$tresult = $db->loadObjectList();
		$count   = count($tresult);
		$t       = 0;
		$i       = 0;

		if ($count > 0)
		{
			$topic   = "\n" . '<table class="table landing_table" width="100%"><tr>';
			$showdiv = 0;

			foreach ($tresult as &$b)
			{
				if ($t >= $limit)
				{
					if ($showdiv < 1)
					{
						if ($i == 1)
						{
							$topic .= "\n\t\t" . '<td  class="landing_td"></td>' . "\n\t\t" . '<td class="landing_td"></td>';
							$topic .= "\n\t" . '</tr>';
						}
						if ($i == 2)
						{
							$topic .= "\n\t\t" . '<td  class="landing_td"></td>';
							$topic .= "\n\t" . '</tr>';
						}

						$topic .= "\n" . '</table>';
						$topic .= "\n\t" . '<div id="showhidetopics" style="display:none;"> <!-- start show/hide topics div-->';
						$topic .= "\n" . '<table width="100%" class="table landing_table"><tr>';

						$i       = 0;
						$showdiv = 1;
					}
				}
				$topic .= "\n\t\t" . '<td class="landing_td">';
				$topic .= '<a href="index.php?option=com_biblestudy&amp;view=sermons&amp;filter_topic=' . $b->id . '&amp;filter_teacher=0'
					. '&amp;filter_series=0&amp;filter_location=0&amp;filter_book=0&amp;filter_year=0&amp;filter_messagetype=0&amp;t=' . $template . '">';
				$trans = new JBSMTranslated;
				$topic .= $trans->getTopicItemTranslated($b);

				$topic .= '</a>';

				$topic .= '</td>';
				$i++;
				$t++;

				if ($i == 3 && $t != $limit && $t != $count)
				{
					$topic .= "\n\t" . '</tr><tr>';
					$i = 0;
				}
				elseif ($i == 3 || $t == $count || $t == $limit)
				{
					$topic .= "\n\t" . '</tr>';
					$i = 0;
				}
			}
			if ($i == 1)
			{
				$topic .= "\n\t\t" . '<td  class="landing_td"></td>' . "\n\t\t" . '<td class="landing_td"></td>';
			}
			if ($i == 2)
			{
				$topic .= "\n\t\t" . '<td  class="landing_td"></td>';
			}

			$topic .= "\n" . '</table>' . "\n";

			if ($showdiv == 1)
			{

				$topic .= "\n\t" . '</div> <!-- close show/hide topics div-->';
				$showdiv = 2;
			}
			$topic .= '<div class="landing_separator"></div>';
		}
		else
		{
			$topic = '<div class="landing_separator"></div>';
		}

		return $topic;
	}

	/**
	 * Get MessageType for Landing Page
	 *
	 * @param   Joomla\Registry\Registry  $params  Item Params
	 * @param   int                       $id      ID
	 *
	 * @return string
	 */
	public function getMessageTypesLandingPage($params, $id)
	{
		$mainframe   = JFactory::getApplication();
		$db          = JFactory::getDBO();
		$user        = JFactory::getUser();
		$input       = new JInput;
		$input       = new JInput;
		$messagetype = null;
		$order       = 'ASC';
		$teacherid   = null;
		$template    = $params->get('studieslisttemplateid', 1);
		$limit       = $params->get('landingmessagetypeslimit');

		if (!$limit)
		{
			$limit = 10000;
		}
		$messagetypeuselimit = $params->get('landingmessagetypeuselimit', 0);
		$menu                = $mainframe->getMenu();
		$item                = $menu->getActive();
		$registry            = new Registry;

		if (isset($item->prams))
		{
			$registry->loadString($item->params);
			$m_params   = $registry;
			$language   = $db->quote($item->language) . ',' . $db->quote('*');
			$menu_order = $m_params->get('messagetypes_order');
		}
		else
		{
			$language   = $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*');
			$menu_order = null;
		}
		if ($language == '*' || !$language)
		{
			$langlink = '';
		}
		elseif ($language != '*' && isset($item->language))
		{
			$langlink = '&amp;filter.languages=' . $item->language;
		}
		if ($menu_order)
		{
			switch ($menu_order)
			{
				case 2:
					$order = 'ASC';
					break;
				case 1:
					$order = 'DESC';
					break;
			}
		}
		else
		{
			$order = $params->get('landing_default_order', 'ASC');
		}
		// Compute view access permissions.
		$groups = implode(',', $user->getAuthorisedViewLevels());

		$query = $db->getQuery(true);
		$query->select('distinct a.*')
			->from('#__bsms_message_type a')
			->select('b.access AS study_access')
			->innerJoin('#__bsms_studies b on a.id = b.messagetype')
			->where('b.language in (' . $language . ')')
			->where('b.access IN (' . $groups . ')')
			->where('b.published = 1')
			->where('a.landing_show > 0')
			->order('a.message_type ' . $order);
		$db->setQuery($query);

		$tresult = $db->loadObjectList();
		$count   = count($tresult);
		$t       = 0;
		$i       = 0;

		if ($count > 0)
		{
			switch ($messagetypeuselimit)
			{
				case 0:
					$messagetype = "\n" . '<table class="table landing_table" width="100%"><tr>';
					$showdiv     = 0;

					foreach ($tresult as &$b)
					{
						if ($t >= $limit)
						{
							if ($showdiv < 1)
							{
								if ($i == 1)
								{
									$messagetype .= "\n\t\t" . '<td  class="landing_td"></td>' . "\n\t\t" . '<td class="landing_td"></td>';
									$messagetype .= "\n\t" . '</tr>';
								}
								if ($i == 2)
								{
									$messagetype .= "\n\t\t" . '<td  class="landing_td"></td>';
									$messagetype .= "\n\t" . '</tr>';
								}

								$messagetype .= "\n" . '</table>';
								$messagetype .= "\n\t" . '<div id="showhidemessagetypes" style="display:none;"> <!-- start show/hide messagetype div-->';
								$messagetype .= "\n" . '<table width="100%" class="table landing_table"><tr>';

								$i       = 0;
								$showdiv = 1;
							}
						}
						$messagetype .= "\n\t\t" . '<td class="landing_td">';

						$messagetype .= '<a href="index.php?option=com_biblestudy&amp;view=sermons&amp;filter_messagetype=' . $b->id
							. '&amp;filter_book=0&amp;filter_teacher=0&amp;filter_series=0&amp;filter_topic=0&amp;filter_location=0&amp;filter_year=0&amp;t='
							. $template . '">';

						$messagetype .= $b->message_type;

						$messagetype .= '</a>';

						$messagetype .= '</td>';

						$i++;
						$t++;

						if ($i == 3 && $t != $limit && $t != $count)
						{
							$messagetype .= "\n\t" . '</tr><tr>';
							$i = 0;
						}
						elseif ($i == 3 || $t == $count || $t == $limit)
						{
							$messagetype .= "\n\t" . '</tr>';
							$i = 0;
						}
					}

					if ($i == 1)
					{
						$messagetype .= "\n\t\t" . '<td  class="landing_td"></td>' . "\n\t\t" . '<td class="landing_td"></td>';
					}

					if ($i == 2)
					{
						$messagetype .= "\n\t\t" . '<td  class="landing_td"></td>';
					}

					$messagetype .= "\n" . '</table>' . "\n";

					if ($showdiv == 1)
					{

						$messagetype .= "\n\t" . '</div> <!-- close show/hide messagetype div-->';
						$showdiv = 2;
					}
					$messagetype .= '<div class="landing_separator"></div>';
					break;

				case 1:
					$messagetype = '<div class="landingtable" style="display:inline;">';

					foreach ($tresult as $b)
					{
						if ($b->landing_show == 1)
						{
							$messagetype .= '<div class="landingrow">';
							$messagetype .= '<div class="landingcell">
							<a class="landinglink" href="index.php?option=com_biblestudy&amp;view=sermons&amp;filter_messagetype='
								. $b->id . '&amp;filter_book=0&amp;filter_teacher=0&amp;filter_series=0&amp;filter_topic=0&amp;filter_location=0&amp;filter_year=0&amp;t='
								. $template . '">';
							$messagetype .= $b->message_type;
							$messagetype .= '</a></div>';
							$messagetype .= '</div>';
						}
					}

					$messagetype .= '</div>';
					$messagetype .= '<div id="showhidemessagetypes" style="display:none;">';

					foreach ($tresult as $b)
					{
						if ($b->landing_show == 2)
						{
							$messagetype .= '<div class="landingrow">';
							$messagetype .= '<div class="landingcell">
							<a class="landinglink" href="index.php?option=com_biblestudy&amp;view=sermons&amp;filter_messagetype=' . $b->id
								. '&amp;filter_book=0&amp;filter_teacher=0&amp;filter_series=0&amp;filter_topic=0&amp;filter_location=0&amp;filter_year=0&amp;t='
								. $template . '">';
							$messagetype .= $b->message_type;
							$messagetype .= '</a></div>';
							$messagetype .= '</div>';
						}
					}

					$messagetype .= '</div>';
					$messagetype .= '<div class="landing_separator"></div>';
					break;
			}
		}
		else
		{
			$messagetype = '<div class="landing_separator"></div>';
		}

		return $messagetype;
	}

	/**
	 * Get Books for Landing Page.
	 *
	 * @param   Joomla\Registry\Registry  $params  Item Params
	 *
	 * @return string
	 */
	public function getBooksLandingPage($params)
	{

		$user     = JFactory::getUser();
		$db       = JFactory::getDBO();
		$order    = 'ASC';
		$book     = null;
		$template = $params->get('studieslisttemplateid');
		$limit    = $params->get('landingbookslimit');

		if (!$limit)
		{
			$limit = 10000;
		}
		$app      = JFactory::getApplication();
		$menu     = $app->getMenu();
		$item     = $menu->getActive();
		$registry = new Registry;

		if (isset($item->prams))
		{
			$registry->loadString($item->params);
			$m_params   = $registry;
			$language   = $db->quote($item->language) . ',' . $db->quote('*');
			$menu_order = $m_params->get('books_order');
		}
		else
		{
			$language   = $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*');
			$menu_order = null;
		}
		if ($language == '*' || !$language)
		{
			$langlink = '';
		}
		elseif ($language != '*' && isset($item->language))
		{
			$langlink = '&amp;filter.languages=' . $item->language;
		}
		if ($menu_order)
		{
			switch ($menu_order)
			{
				case 2:
					$order = 'ASC';
					break;
				case 1:
					$order = 'DESC';
					break;
			}
		}
		else
		{
			$order = $params->get('landing_default_order', 'ASC');
		}
		// Compute view access permissions.
		$groups = $user->getAuthorisedViewLevels();
		$groups = array_unique($groups);
		$groups = implode(',', $groups);
		$query = $db->getQuery(true);
		$query->select('distinct a.*')
			->from('#__bsms_books a')
			->select('b.access AS access')
			->innerJoin('#__bsms_studies b on a.booknumber = b.booknumber')
			->where('b.language in (' . $language . ')')
			->where('b.access IN (' . $groups . ')')
			->where('b.published = 1')
			->order('a.booknumber ' . $order)
			->group('a.bookname');
		$db->setQuery($query);

		$tresult = $db->loadObjectList();
		$count   = count($tresult);
		$t       = 0;
		$i       = 0;

		if ($count > 0)
		{
			$book    = "\n" . '<table class="table landing_table" width="100%" ><tr>';
			$showdiv = 0;

			foreach ($tresult as &$b)
			{
				if ($t >= $limit)
				{
					if ($showdiv < 1)
					{
						if ($i == 1)
						{
							$book .= "\n\t\t" . '<td  class="landing_td"></td>' . "\n\t\t" . '<td class="landing_td"></td>';
							$book .= "\n\t" . '</tr>';
						}
						if ($i == 2)
						{
							$book .= "\n\t\t" . '<td  class="landing_td"></td>';
							$book .= "\n\t" . '</tr>';
						}

						$book .= "\n" . '</table>';
						$book .= "\n\t" . '<div id="showhidebooks" style="display:none;"> <!-- start show/hide book div-->';
						$book .= "\n" . '<table width="100%" class="table landing_table"><tr>';

						$i       = 0;
						$showdiv = 1;
					}
				}
				$book .= "\n\t\t" . '<td class="landing_td">';
				$book .= '<a href="index.php?option=com_biblestudy&amp;view=sermons&amp;filter_book=' . $b->booknumber
					. '&amp;filter_teacher=0&amp;filter_series=0&amp;filter_topic=0&amp;filter_location=0&amp;filter_year=0&amp;filter_messagetype=0&amp;t='
					. $template . '">';

				$book .= JText::sprintf($b->bookname);

				$book .= '</a>';

				$book .= '</td>';
				$i++;
				$t++;

				if ($i == 3 && $t != $limit && $t != $count)
				{
					$book .= "\n\t" . '</tr><tr>';
					$i = 0;
				}
				elseif ($i == 3 || $t == $count || $t == $limit)
				{
					$book .= "\n\t" . '</tr>';
					$i = 0;
				}
			}
			if ($i == 1)
			{
				$book .= "\n\t\t" . '<td  class="landing_td"></td>' . "\n\t\t" . '<td class="landing_td"></td>';
			}
			if ($i == 2)
			{
				$book .= "\n\t\t" . '<td  class="landing_td"></td>';
			}

			$book .= "\n" . '</table>' . "\n";

			if ($showdiv == 1)
			{
				$book .= "\n\t" . '</div> <!-- close show/hide books div-->';
			}
			$book .= '<div class="landing_separator"></div>';
		}
		else
		{
			$book = '<div class="landing_separator"></div>';
		}

		return $book;
	}
}
