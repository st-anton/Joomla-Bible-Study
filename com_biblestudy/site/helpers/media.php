<?php
/**
 * Part of Joomla BibleStudy Package
 *
 * @package    BibleStudy.Admin
 * @copyright  2007 - 2013 Joomla Bible Study Team All rights reserved
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.JoomlaBibleStudy.org
 * */
// No Direct Access
defined('_JEXEC') or die;

use Joomla\Registry\Registry;

/**
 * Joomla! Bible Study Media class.
 *
 * @since  7.0.0
 */
class JBSMMedia
{

	/**
	 * Return Fluid Media row
	 *
	 * @param   Object                    $media     Media info
	 * @param   Joomla\Registry\Registry  $params    Params
	 * @param   TableTemplate             $template  Template Table
	 *
	 * @return string
	 */
	public function getFluidMedia($media, $params, $template)
	{
		$registory = new Registry;
		$registory->loadString($media->smedia);
		$media->smedia = $registory;

		$registory = new Registry;
		$registory->loadString($media->params);
		$media->params = $registory;

		$registory = new Registry;
		$registory->loadString($media->sparams);
		$media->sparams = $registory;

		if ($media->params->get('media_image'))
		{
			$mediaimage = $media->params->get('media_image');
		}
		else
		{
			$mediaimage = 'media/com_biblestudy/images/speaker24.png';
		}
		$image      = $this->useJImage($mediaimage, $media->params->get('media_text'));
		$player     = self::getPlayerAttributes($params, $media);
		$playercode = self::getPlayerCode($params, $player, $image, $media);
		$mediafile  = self::getFluidDownloadLink($media, $params, $template, $playercode);

		if ($params->get('show_filesize') > 0 && isset($media))
		{
			$mediafile = '<div style="display:inline;">' . $mediafile .
				'<div style="font-size: 0.6em;display:inline;position:relative;margin-bottom:15px;padding-right:2px;">' .
				self::getFluidFilesize($media, $params) . '</div></div>';
		}

		return $mediafile;
	}

	/**
	 * Use JImage to create images
	 *
	 * @param   string  $path  Path to file
	 * @param   string  $alt   Accessibility string
	 *
	 * @return bool|string
	 *
	 * @since 9.0.0
	 */
	public function useJImage($path, $alt = 'link')
	{
		if (!$path)
		{
			return false;
		}
		$image = new JImage;

		try
		{
			$return = $image->getImageFileProperties($path);
		}
		catch (Exception $e)
		{
			return $alt;
		}

		$imagereturn = '<img src="' . JURI::base() . $path . '" alt="' . $alt . '" ' . $return->attributes .
			' width="' . $return->width . '" height="' . $return->height . '">';

		return $imagereturn;
	}

	/**
	 * Set up Player Attributes
	 *
	 * @param   Joomla\Registry\Registry  $params  Params
	 * @param   object                    $media   Media info
	 *
	 * @return object
	 */
	public function getPlayerAttributes($params, $media)
	{

		$player               = new stdClass;
		$player->playerwidth  = $params->get('player_width');
		$player->playerheight = $params->get('player_height');

		if ($params->get('playerheight'))
		{
			$player->playerheight = $params->get('playerheight');
		}
		if ($params->get('playerwidth'))
		{
			$player->playerwidth = $params->get('playerwidth');
		}

		/**
		 * @desc Players - from Template:
		 * First we check to see if in the template the user has set to use the internal player for all media. This can be overridden by itemparams
		 * popuptype = whether AVR should be window or lightbox (handled in avr code)
		 * internal_popup = whether direct or internal player should be popup/new window or inline
		 * From media file:
		 * player 0 = direct, 1 = internal, 2 = AVR, 3 = AV 7 = legacy internal player (from JBS 6.2.2)
		 * internal_popup 0 = inline, 1 = popup, 2 = global settings
		 *
		 * Get the $player->player: 0 = direct, 1 = internal, 2 = AVR (no longer supported),
		 * 3 = All Videos or JPlayer, 4 = Docman, 5 = article, 6 = Virtuemart, 7 = legacy player, 8 = embed code
		 * $player->type 0 = inline, 1 = popup/new window 3 = Use Global Settings (from params)
		 * In 6.2.3 we changed inline = 2
		 */
		$player->player   = 0;
		$item_mediaplayer = $media->params->get('player');

		// Check to see if the item player is set to 100 - that means use global settings which comes from $params
		if ($item_mediaplayer == 100)
		{
			// Player is set from the $params
			$player->player = $params->get('media_player', '0');
		}
		else
		{

			/* In this case the item has a player set for it, so we use that instead. We also need to change the old player
					type of 3 to 2 for all videos reloaded which we don't support */

			$player->player = ($media->params->get('player')) ? $media->params->get('player') : "0";
		}
		if ($player->player == 3)
		{
			$player->player = 2;
		}

		if ($params->get('docMan_id') != 0)
		{
			$player->player = 4;
		}
		if ($params->get('article_id') > 0)
		{
			$player->player = 5;
		}
		if ($params->get('virtueMart_id') > 0)
		{
			$player->player = 6;
		}

		$player->type = 1;

		// This is the global parameter set in Template Display settings
		$param_playertype = $params->get('internal_popup');

		if (!$param_playertype)
		{
			$param_playertype = 1;
		}
		$item_playertype = $params->get('popup');

		if ($param_playertype)
		{
			$player->type = $param_playertype;
		}

		switch ($item_playertype)
		{
			case 3:
				$player->type = $param_playertype;
				break;

			case 2:
				$player->type = 2;
				break;

			case 1:
				$player->type = 1;
				break;
		}

		return $player;
	}

	/**
	 * Setup Player Code.
	 *
	 * @param   Joomla\Registry\Registry  $params  Params are the merged of system and items.
	 * @param   object                    $player  Player code
	 * @param   String                    $image   Image info
	 * @param   object                    $media   Media
	 *
	 * @return string
	 */
	public function getPlayerCode($params, $player, $image, $media)
	{
		$input       = new JInput;
		$template    = $input->get('t', '1', 'int');

		// Here we get more information about the particular media file
		$filesize = self::getFluidFilesize($media, $params);
		$duration = self::getFluidDuration($media, $params);
		$path     = $media->params->get('filename');

		if (!isset($media->malttext))
		{
			$media->malttext = '';
		}
		if (!substr_count($path, '://') && !substr_count($path, '//'))
		{
			$protocol = $params->get('protocol', '//');
			$path     = $protocol . $media->sparams->get('path') . $path;
		}

		switch ($player->player)
		{

			case 0: // Direct

				switch ($player->type)
				{

					case 2: // New window

						$playercode = '<a href="' . $path . '" onclick="window.open(\'index.php?option=com_biblestudy&amp;view=popup&amp;close=1&amp;mediaid=' .
							$media->id . '\',\'newwindow\',\'width=100, height=100,menubar=no, status=no,location=no,toolbar=no,scrollbars=no\'); return true;" title="' .
							$media->malttext . ' - ' . $media->comment . ' ' . $duration . ' '
							. $filesize . '" target="' . $media->special . '">' . $image . '</a>';

						return $playercode;
						break;

					case 1: // Popup window
						$playercode = "<a href=\"#\" onclick=\"window.open('index.php?option=com_biblestudy&amp;player=0&amp;view=popup&amp;t="
							. $template . "&amp;mediaid=" . $media->id . "&amp;tmpl=component', 'newwindow','width=" . $player->playerwidth . ",height=" .
							$player->playerheight . "'); return false\">" . $image . "</a>";
						break;
				}

				/** @var $playercode string */

				return $playercode;
				break;

			case 7:
			case 1: // Internal
				switch ($player->type)
				{
					case 3: // Squeezebox view
						JHtml::_('fancybox.framework', true, true);
						$playercode = '<a href="' . $path . '" class="fancybox fancybox_jwplayer" rel="width="' .
							$player->playerwidth . '" height="' . $player->playerheight .
							'">' . $image . '</a>';

						return $playercode;
						break;

					case 2: // Inline
						JHtmlJwplayer::framework();
						$playercode = JHtmlJwplayer::render($media, $media->id, $params);
						break;

					case 1: // Popup
						// Add space for popup window
						$player->playerwidth  = $player->playerwidth + 20;
						$player->playerheight = $player->playerheight + $params->get('popupmargin', '50');
						$playercode           = "<a href=\"#\" onclick=\"window.open('index.php?option=com_biblestudy&amp;player=1&amp;view=popup&amp;t="
							. $template . "&amp;mediaid=" . $media->id . "&amp;tmpl=component', 'newwindow', 'width=" . $player->playerwidth . ",height=" .
							$player->playerheight . "'); return false\">" . $image . "</a>";
						break;
				}

				/** @var $playercode string */

				return $playercode;
				break;

			case 2: // All Videos Reloaded
			case 3:
				switch ($player->type)
				{
					case 1: // This goes to the popup view
						$playercode = "<a href=\"#\" onclick=\"window.open('index.php?option=com_biblestudy&amp;view=popup&amp;player=3&amp;t=" . $template .
							"&amp;mediaid=" . $media->id . "&amp;tmpl=component', 'newwindow','width=" . $player->playerwidth . ",height="
							. $player->playerheight . "'); return false\">" . $image . "</a>";
						break;

					case 2: // This plays the video inline
						$mediacode  = $this->getAVmediacode($media->mediacode, $media);
						$playercode = JHTML::_('content.prepare', $mediacode);
						break;
				}

				/** @var $playercode string */

				return $playercode;
				break;

			case 4: // Docman
				$playercode = $this->getDocman($media, $image);

				return $playercode;
				break;

			case 5: // Article
				$playercode = $this->getArticle($media, $image);

				return $playercode;
				break;

			case 6: // Virtuemart
				$playercode = $this->getVirtuemart($media, $params, $image);

				return $playercode;
				break;

			case 8: // Embed code
				$playercode = "<a href=\"#\" onclick=\"window.open('index.php?option=com_biblestudy&amp;view=popup&amp;player=8&amp;t=" . $template .
					"&amp;mediaid=" . $media->id . "&amp;tmpl=component', 'newwindow','width=" . $player->playerwidth . ",height="
					. $player->playerheight . "'); return false\">" . $image . "</a>";

				return $playercode;
				break;
		}

		return false;
	}

	/**
	 * return $table
	 *
	 * @param   Object                    $media   Media info
	 * @param   Joomla\Registry\Registry  $params  Params
	 *
	 * @return null|string
	 */
	public function getFluidFilesize($media, $params)
	{
		$file_size = '';
		$filesize  = '';

		if (!isset($media->smedia->size))
		{
			$table = null;

			return $table;
		}
		switch ($media->size)
		{
			case $media->size < 1024 :
				$file_size = $media->size . ' ' . 'Bytes';
				break;
			case $media->size < 1048576 :
				$file_size = $media->size / 1024;
				$file_size = number_format($file_size, 0);
				$file_size = $file_size . ' ' . 'KB';
				break;
			case $media->size < 1073741824 :
				$file_size = $media->size / 1024;
				$file_size = $file_size / 1024;
				$file_size = number_format($file_size, 1);
				$file_size = $file_size . ' ' . 'MB';
				break;
			case $media->size > 1073741824 :
				$file_size = $media->size / 1024;
				$file_size = $file_size / 1024;
				$file_size = $file_size / 1024;
				$file_size = number_format($file_size, 1);
				$file_size = $file_size . ' ' . 'GB';
				break;
		}
		switch ($params->get('show_filesize'))
		{
			case 1:
				$filesize = $file_size;
				break;
			case 2:
				$filesize = $media->comment;
				break;
			case 3:
				if ($media->comment)
				{
					$filesize = $media->comment;
				}
				else
				{
					($filesize = $file_size);
				}
				break;
		}

		return $filesize;
	}

	/**
	 * Get duration
	 *
	 * @param   Object                    $row     Table Row info
	 * @param   Joomla\Registry\Registry  $params  Params
	 *
	 * @return null|string
	 */
	public function getFluidDuration($row, $params)
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
	 * Return AVMedia Code.
	 *
	 * @param   string  $mediacode  Media string
	 * @param   object  $media      Media info
	 *
	 * @return string
	 */
	public function getAVmediacode($mediacode, $media)
	{
		$bracketpos   = strpos($mediacode, '}');
		$bracketend   = strpos($mediacode, '{', $bracketpos);
		$dashposition = strpos($mediacode, '-', $bracketpos);
		$isonlydash   = substr_count($mediacode, '}-{');

		if ($isonlydash)
		{
			$mediacode = substr_replace($mediacode, 'http://' . $media->spath . $media->fpath . $media->filename, $dashposition, 1);
		}
		elseif ($dashposition)
		{
			$mediacode = substr_replace($mediacode, $media->spath . $media->fpath . $media->filename, $bracketend - 1, 1);
		}

		return $mediacode;
	}

	/**
	 * Return Docman Media
	 *
	 * @param   object  $media  Media
	 * @param   string  $image  Image
	 *
	 * @return string
	 */
	public function getDocman($media, $image)
	{
		$url = 'com_docman';

		$getmenu  = JFactory::getApplication();
		$menuItem = $getmenu->getMenu()->getItems('component', $url, true);
		$Itemid   = $menuItem->id;
		$docman   = '<a href="index.php?option=com_docman&amp;view=document&amp;slug=' .
			$media->docMan_id . '&amp;Itemid=' . $Itemid . '" alt="' . $media->malttext . ' - ' . $media->comment .
			'" target="' . $media->special . '">' . $image . '</a>';

		return $docman;
	}

	/**
	 * Return Articles.
	 *
	 * @param   object  $media  Media
	 * @param   string  $image  Image
	 *
	 * @return string
	 */
	public function getArticle($media, $image)
	{
		$article = '<a href="index.php?option=com_content&amp;view=article&amp;id=' . $media->article_id . '"
                 alt="' . $media->malttext . ' - ' . $media->comment . '" target="' . $media->special . '">' . $image . '</a>';

		return $article;
	}

	/**
	 * Set up Virtumart if Vertumart is installed.
	 *
	 * @param   object                    $media   Media
	 * @param   Joomla\Registry\Registry  $params  Item Params
	 * @param   string                    $image   Image
	 *
	 * @return string
	 */
	public function getVirtuemart($media, $params, $image)
	{
		$vm = '<a href="index.php?option=com_virtuemart&amp;view=productdetails&amp;virtuemart_product_id=' . $media->virtueMart_id . '"
                alt="' . $media->malttext . ' - ' . $media->comment . '" target="' . $media->special . '">' . $image . '</a>';

		return $vm;
	}

	/**
	 * Return download link
	 *
	 * @param   Object                    $media       Media
	 * @param   Joomla\Registry\Registry  $params      Params
	 * @param   TableTemplate             $template    Template ID
	 * @param   string                    $playercode  Player Code
	 *
	 * @return string
	 */
	public function getFluidDownloadLink($media, $params, $template, $playercode)
	{
		$table        = '';
		$downloadlink = '';

		if ($params->get('default_download_image'))
		{
			$admin_d_image = $params->get('default_download_image');
		}
		else
		{
			$admin_d_image = null;
		}
		$d_image = ($admin_d_image ? $admin_d_image : 'media/com_biblestudy/images/download.png');

		$download_image = $this->useJImage($d_image, JText::_('JBS_MED_DOWNLOAD'));

		if ($media->params->get('link_type'))
		{
			$link_type = $media->params->get('link_type');
		}
		else
		{
			$link_type = $media->smedia->get('link_type');
		}
		if ($link_type > 0)
		{
			$compat_mode = $params->get('compat_mode');

			if ($compat_mode == 0)
			{
				$downloadlink = '<a href="index.php?option=com_biblestudy&amp;mid=' .
					$media->id . '&amp;view=sermons&amp;task=download">';
			}
			else
			{
				$downloadlink = '<a href="http://joomlabiblestudy.org/router.php?file=' .
					$media->params->get('filename') . '&amp;size=' . $media->params->get('size') . '">';
			}

			// Check to see if they want to use a popup
			if ($params->get('useterms') > 0)
			{

				$downloadlink = '<a class="modal" href="index.php?option=com_biblestudy&amp;view=terms&amp;tmpl=component&amp;layout=modal&amp;compat_mode='
					. $compat_mode . '&amp;mid=' . $media->id . '&amp;t=' . $template->id . '" rel="{handler: \'iframe\', size: {x: 640, y: 480}}">';
			}
			$downloadlink .= $download_image . '</a>';
		}

		switch ($link_type)
		{
			case 0:
				$table .= $playercode;
				break;

			case 1:
				$table .= $playercode . $downloadlink;
				break;

			case 2:
				$table .= $downloadlink;
				break;
		}

		return $table;
	}

	/**
	 * Update Hit count for plays.
	 *
	 * @param   int  $id  ID to apply the hit to.
	 *
	 * @return boolean
	 */
	public function hitPlay($id)
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->update('#__bsms_mediafiles')
			->set('plays = plays + 1')
			->where('id = ' . $db->q($id));
		$db->setQuery($query);

		if ($db->execute())
		{
			return true;
		}

		return false;
	}

	/**
	 * Get Media info Row2
	 *
	 * @param   int  $id  ID of Row
	 *
	 * @return object|boolean
	 */
	public function getMediaRows2($id)
	{
		// We use this for the popup view because it relies on the media file's id rather than the study_id field above
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('#__bsms_mediafiles.*, #__bsms_servers.params AS sparams,'
			. ' s.studyintro, s.media_hours, s.media_minutes, s.series_id,'
			. ' s.media_seconds, s.studytitle, s.studydate, s.teacher_id, s.booknumber, s.chapter_begin, s.chapter_end, s.verse_begin,'
			. ' s.verse_end, t.teachername, t.teacher_thumbnail, t.teacher_image, t.thumb, t.image, t.id as tid, s.id as sid, s.studyintro,'
			. ' se.id as seriesid, se.series_text, se.series_thumbnail')
			->from('#__bsms_mediafiles')
			->leftJoin('#__bsms_servers ON (#__bsms_servers.id = #__bsms_mediafiles.server_id)')
			->leftJoin('#__bsms_studies AS s ON (s.id = #__bsms_mediafiles.study_id)')
			->leftJoin('#__bsms_teachers AS t ON (t.id = s.teacher_id)')
			->leftJoin('#__bsms_series as se ON (s.series_id = se.id)')
			->where('#__bsms_mediafiles.id = ' . (int) $id)
			->where('#__bsms_mediafiles.published = ' . 1)
			->where('#__bsms_mediafiles.language in (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')')
			->order('ordering asc');
		$db->setQuery($query);
		$media = $db->loadObject();

		if ($media)
		{
			$reg = new Registry;
			$reg->loadString($media->sparams);
			$params = $reg->toObject();
			if ($params->path)
			{
				$media->spath = $params->path;
			}
			else
			{
				($media->spath = '');
			}
			return $media;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Function to get File Size
	 *
	 * @param   string  $file_size  Size in bytes
	 *
	 * @return null|string
	 *
	 * @deprecate 9.0.0 This is replace by getFluidFilesize
	 */
	public function getFilesize($file_size)
	{
		JFactory::getApplication()->enqueueMessage('Use JBSMedia->getFluidFilesize');
		return false;
	}

}
