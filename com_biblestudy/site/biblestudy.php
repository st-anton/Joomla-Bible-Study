<?php
/**
 * Core BibleStudy Site File
 *
 * @package    BibleStudy.Site
 * @copyright  (C) 2007 - 2014 Joomla Bible Study Team All rights reserved
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.JoomlaBibleStudy.org
 * */
// No Direct Access
defined('_JEXEC') or die;

// Include dependencies
JLoader::register('JBSMRoute', JPATH_COMPONENT . '/helpers/route.php');
/**
 * Bible Study Core Defines
 */
require_once JPATH_ADMINISTRATOR . '/components/com_biblestudy/lib/defines.php';

JLoader::discover('JBSM', BIBLESTUDY_PATH_LIB);
JLoader::discover('JBSM', BIBLESTUDY_PATH_ADMIN_LIB);
JLoader::discover('JBSM', BIBLESTUDY_PATH_HELPERS);
JLoader::discover('JBSM', BIBLESTUDY_PATH_ADMIN_HELPERS);
JHtml::addIncludePath(BIBLESTUDY_PATH_ADMIN_HELPERS . '/html/');

if (version_compare(PHP_VERSION, BIBLESTUDY_MIN_PHP, '<'))
{
	throw new Exception(JText::_('JERROR_ERROR') . JText::sprintf('JBS_CMN_PHP_ERROR', BIBLESTUDY_MIN_PHP), 404);
}

// load language file out of administrator folder
// if phrase is not found in specific language file, load english language file:
$language = JFactory::getLanguage();
$language->load('com_biblestudy', JPATH_COMPONENT_ADMINISTRATOR, 'en-GB', true);
$language->load('com_biblestudy', JPATH_COMPONENT_ADMINISTRATOR, null, true);

$controller = JControllerLegacy::getInstance('Biblestudy');
$controller->execute(JFactory::getApplication()->input->get('task', '', 'cmd'));
$controller->redirect();
