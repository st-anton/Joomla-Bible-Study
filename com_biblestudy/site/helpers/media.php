<?php
/**
 * @package    BibleStudy.Site
 * @copyright  (C) 2007 - 2011 Joomla Bible Study Team All rights reserved
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.JoomlaBibleStudy.org
 * */
// No Direct Access
defined('_JEXEC') or die;
//require_once (JPATH_ROOT . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_biblestudy' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'biblestudy.images.class.php');
JLoader::register('jbsImages', dirname(__FILE__) . '/lib/biblestudy.images.class.php');
JLoader::register('JBSMListing', BIBLESTUDY_PATH_LIB . '/biblestudy.listing.class.php');
JLoader::discover('JBSM', BIBLESTUDY_PATH_LIB . '/elements.php');


/**
 * class for Media Helper
 *
 * @package  BibleStudy.Site
 * @since    8.0.0
 */
class JBSMMediaHelper
{
	/**
	 * Get Media
	 *
	 * @param   int  $id  ID
	 *
	 * @return object
	 */
	public function getMedia($id)
	{
		$database = JFactory::getDBO();

		$query_media = 'SELECT #__bsms_mediafiles.*,'
				. ' #__bsms_servers.id AS ssid, #__bsms_servers.server_path AS spath,'
				. ' #__bsms_folders.id AS fid, #__bsms_folders.folderpath AS fpath,'
				. ' #__bsms_media.id AS mid, #__bsms_media.media_image_path AS impath, #__bsms_media.media_image_name AS imname, #__bsms_media.path2 AS path2,'
				. ' #__bsms_media.media_alttext AS malttext,'
				. ' #__bsms_mimetype.id AS mtid, #__bsms_mimetype.mimetext'
				. ' FROM #__bsms_mediafiles'
				. ' LEFT JOIN #__bsms_media ON (#__bsms_media.id = #__bsms_mediafiles.media_image)'
				. ' LEFT JOIN #__bsms_servers ON (#__bsms_servers.id = #__bsms_mediafiles.server)'
				. ' LEFT JOIN #__bsms_folders ON (#__bsms_folders.id = #__bsms_mediafiles.path)'
				. ' LEFT JOIN #__bsms_mimetype ON (#__bsms_mimetype.id = #__bsms_mediafiles.mime_type)'
				. ' WHERE #__bsms_mediafiles.study_id = ' . $id . ' AND #__bsms_mediafiles.published = 1 ORDER BY ordering ASC, #__bsms_mediafiles.mime_type ASC';

		$database->setQuery($query_media);
		$media = $database->loadObjectList('id');

		return $media;
	}

	/**
	 * Get Internal Player
	 *
	 * @param   object  $media         Media Info
	 * @param   object  $params        Item Params
	 * @param   object  $admin_params  Admin Params
	 *
	 * @return string
	 */
	public function getInternalPlayer($media, $params, $admin_params)
	{

		// Convert parameter fields to objects.
		$registry = new JRegistry;
		$registry->loadString($media->params);
		$itemparams = $registry;

		$Itemid = $params->get('detailstemplateid', 1);
		$images = new jbsImages();
		$image  = $images->getMediaImage($media->path2, $media->impath);


		$idfield  = '#__bsms_mediafiles.id';
		$filesize = JBSMElements::getFilesize($media->size);
		$duration = JBSMElements::getDuration($params, $media);
		$mimetype = $media->mimetext;
		$src      = JURI::base() . $image->path;
		$height   = $image->height;
		$width    = $image->width;
		$ispath   = 0;
		$mime     = '';
		$path1    = JBSMElements::getFilepath($media->id, $idfield, $mime);

		$player_width = $params->get('player_width', 290);
		$media1_link  =
				'<script language="javascript" type="text/javascript" src="' . JURI::base() . 'media/com_biblestudy/player/jwplayer.js"></script>
		    <object type="application/x-shockwave-flash" data="' . JURI::base() . 'media/com_biblestudy/player/player.swf" id="audioplayer' . $media->id . '" height="24" width="' . $params->get('player_width', 290) . '">
		    <param name="movie" value="' . JURI::base() . 'media/com_biblestudy/player/player.swf" />
		    <param name="FlashVars" value="playerID=audioplayer' . $media->id . '&soundFile=' . $path1 . '" />
		    <param name="quality" value="high" />
		    <param name="menu" value="false" />
		    <param name="wmode" value="transparent" />
		    </object> ';

		return $media1_link;
	}

	/**
	 * Get Download Link
	 *
	 * @param   object  $media         Media Info
	 * @param   object  $params        Item Params
	 * @param   object  $admin_params  Admin Prams
	 *
	 * @return string
	 *
	 * @FIXME look like things are broken here.
	 */
	public function getDownloadLink($media, $params, $admin_params)
	{
		// Convert parameter fields to objects.
		$registry = new JRegistry;
		$registry->loadString($media->params);
		$itemparams = $registry;
		$Itemid     = $params->get('detailstemplateid', 1);
		$images     = new jbsImages;
		$image      = $images->getMediaImage($media->path2, $media->impath);


		$database = JFactory::getDBO();

		$database->setQuery('SELECT * FROM #__bsms_admin WHERE id = 1');
		$database->query();
		$admin = $database->loadObjectList();


		$d_image        = ($admin[0]->params->default_download_image);
		$images         = new jbsImages;
		$download_tmp = $images->getMediaImage($admin[0]->params->default_download_image, $media = null);

		$download_image = $download_tmp->path;

		$idfield  = '#__bsms_mediafiles.id';
		$filesize = JBSMElements::getFilesize($media->size);
		$duration = JBSMElements::getDuration($params, $media);
		$mimetype = $media->mimetext;
		$src      = JURI::base() . $image->path;
		$height   = $image->height;
		$width    = $image->width;
		$ispath   = 0;
		$mime     = '';
		$path1    = JBSMElements::getFilepath($media->id, $idfield, $mime);

		$link_type = $media->link_type;

		if ($link_type > 0)
		{
			$width  = $download_tmp->width;
			$height = $download_tmp->height;

			$out = '';

			if ($compat_mode == 0)
			{
				$out .= '<a href="index.php?option=com_biblestudy&amp;id=' . $media->id . '&amp;view=sermons&amp;controller=sermons&amp;task=download">';
			}
			else
			{
				$out .= '<a href="http://joomlabiblestudy.org/router.php?file=' . $media->spath . $media->fpath . $media->filename .
						'&amp;size=' . $media->size . '">';
			}

			$out .= '<img src="' . $d_path . '" alt="' . JText::_('JBS_MED_DOWNLOAD') . '" height="' . $height . '" width="' . $width
					. '" title="' . JText::_('JBS_MED_DOWNLOAD') . '" /></a>';

			return $out;
		}
		return false;
	}

	/**
	 * Get Media File
	 *
	 * @param   object  $media         Media info
	 * @param   object  $params        Item Params
	 * @param   Object  $admin_params  Admin Params
	 *
	 * @return string
	 *
	 * @FIXME look like there is problems with this.
	 */
	public function getMediaFile($media, $params, $admin_params)
	{
		$images = new jbsImages();
		$image  = $images->getMediaImage($media->path2, $media->impath);

		// Convert parameter fields to objects.
		$registry = new JRegistry;
		$registry->loadString($media->params);
		$itemparams = $registry;
		$Itemid     = $params->get('detailstemplateid', 1);


		$database = JFactory::getDBO();

		$database->setQuery('SELECT * FROM #__bsms_admin WHERE id = 1');
		$database->query();
		$admin = $database->loadObjectList();


		$d_image = ($admin[0]->params->default_download_image);
		$images  = new jbsImages();

		$download_tmp = $images->getMediaImage($admin[0]->params->default_download_image, $media = null);

		$download_image = $download_tmp->path;

		$idfield  = '#__bsms_mediafiles.id';
		$filesize = JBSMElements::getFilesize($media->size);
		$duration = JBSMElements::getDuration($params, $row);
		$mimetype = $media->mimetext;
		$src      = JURI::base() . $image->path;
		$height   = $image->height;
		$width    = $image->width;
		$ispath   = 0;
		$mime     = '';
		$path1    = JBSMElements::getFilepath($media->id, $idfield, $mime);

		$media_link = '<div class="bsms_mediafile"><a href="' . $path1 . '" title="' . $media->malttext . ' - ' . $media->comment . ' ' . $duration . ' '
				. $filesize . '" target="' . $media->special . '"><img src="' . $d_path
				. '" alt="' . $media->malttext . ' - ' . $media->comment . ' - ' . $duration . ' ' . $filesize . '" width="' . $width
				. '" height="' . $height . '" border="0" /></a></div>';

		return $media_link;
	}

	/**
	 * Get Type Icon
	 *
	 * @param object $media
	 * @param object $params
	 * @param object $admin_params
	 *
	 * @return string
	 */
	public function getTypeIcon($media, $params, $admin_params)
	{
		// Convert parameter fields to objects.
		$registry = new JRegistry;
		$registry->loadString($media->params);
		$itemparams = $registry;

		$Itemid = $params->get('detailstemplateid', 1);
		$images = new jbsImages();
		$image  = $images->getMediaImage($media->path2, $media->impath);

		$idfield  = '#__bsms_mediafiles.id';
		$filesize = JBSMElements::getFilesize($media->size);
		$duration = JBSMElements::getDuration($params, $row);
		$mimetype = $media->mimetext;
		$src      = JURI::base() . $image->path;
		$height   = $image->height;
		$width    = $image->width;
		$ispath   = 0;
		$mime     = '';
		$path1    = JBSMElements::getFilepath($media->id, $idfield, $mime);

		$media_link = '<img src="' . $src
				. '" alt="' . $media->malttext . ' - ' . $media->comment . ' - ' . $duration . ' ' . $filesize . '" width="' . $width
				. '" height="' . $height . '" border="0" />';

		return $media_link;
	}

	/**
	 * Get PDF
	 *
	 * @param   object  $row           Media Info
	 * @param   object  $params        Item Params
	 * @param   object  $admin_params  Admin Params
	 *
	 * @return string
	 */
	public function getPDF($row, $params, $admin_params)
	{
		// PDF View
		$url                = 'index.php?option=com_biblestudy&amp;view=sermon&amp;id=' . $row->id . '&amp;format=pdf';
		$status             = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no';
		$text               = JHTML::_('image.site', 'pdf24.png', '/media/com_biblestudy/images/', null, null, JText::_('JBS_MED_PDF'), 'border=0');
		$attribs['title']   = JText::_('JBS_MED_PDF');
		$attribs['onclick'] = "window.open(this.href,'win2','" . $status . "'); return false;";
		$attribs['rel']     = 'nofollow';
		$link               = JHTML::_('link', JRoute::_($url), $text, $attribs);

		return $link;
	}

	/**
	 * Get Media For List
	 *
	 * @param   object  $row           Media Info
	 * @param   object  $params        Item Params
	 * @param   object  $admin_params  Admin Params
	 *
	 * @deprecated since version 7.0.4
	 */
	public function getMediaForList($row, $params, $admin_params)
	{
		die('function getMediaForList() deprecated since version 7.0.4');
	}
}