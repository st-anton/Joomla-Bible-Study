<?php

/**
 * @version     $Id: form.php 1330 2011-01-06 08:01:38Z genu $
 * @package     com_biblestudy
 * @license     GNU/GPL
 */
//No Direct Access
defined('_JEXEC') or die();

require_once (JPATH_ROOT . DS .  'components' . DS . 'com_biblestudy' . DS . 'lib' . DS . 'biblestudy.defines.php');
if (JOOMLA_VERSION == '5') {
    echo $this->loadTemplate('15');
} else {
    echo $this->loadTemplate('16');
}
?>
