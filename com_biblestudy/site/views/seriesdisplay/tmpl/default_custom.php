<?php
/**
 * Default Custom
 *
 * @package    BibleStudy.Site
 * @copyright  (C) 2007 - 2013 Joomla Bible Study Team All rights reserved
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.JoomlaBibleStudy.org
 * */
// No Direct Access
defined('_JEXEC') or die;

$mainframe = JFactory::getApplication();
$input     = new JInput;
$option    = $input->get('option', '', 'cmd');
$document  = JFactory::getDocument();
$params    = $this->params;
$url       = $this->params->get('stylesheet');

if ($url)
{
	$document->addStyleSheet($url);
}
$jview = new JViewLegacy;
$jview->loadHelper('serieslist');
$JBSMSerieslist = new JBSMSerieslist;
$t              = $this->params->get('serieslisttemplateid');

if (!$t)
{
	$t = $input->get('t', 1, 'int');
}
?>
<form action="<?php echo str_replace("&", "&amp;", $this->request_url); ?>" method="post" name="adminForm">
    <div id="biblestudy" class="noRefTagger"> <!-- This div is the container for the whole page -->
		<?php
		echo $JBSMSerieslist->getSeriesDetailsExp($this->items, $this->params, $this->admin_params, $this->template);
		?>
        <table class="table table-striped bslisttable"> <?php
			$studies = $JBSMSerieslist->getSeriesstudiesExp($this->items->id, $this->params, $this->admin_params, $this->template);
			echo $listing;
			echo $studies;
			?></table>
		<?php
		if ($this->params->get('series_list_return') > 0)
		{
			?>
            <table class="table table-striped">
                <tr class="seriesreturnlink">
                    <td>
						<?php echo '<a href="' . JRoute::_('index.php?option=com_biblestudy&view=seriesdisplays&t=' . $t)
						. '"><< ' . JText::_('JBS_SER_RETURN_SERIES_LIST') . '</a> | <a href="'
						. JRoute::_('index.php?option=com_biblestudy&view=sermons&filter_series=' . $this->items->id . '&t=' . $t)
						. '">' . JText::_('JBS_CMN_SHOW_ALL') . ' ' . JText::_('JBS_SER_STUDIES_FROM_THIS_SERIES') . ' >>'
						. '</a>';
						?>
                    </td>
                </tr>
            </table>
			<?php
		}
		?>
    </div>
    <!--end of bspagecontainer div-->
    <input name="option" value="com_biblestudy" type="hidden">
    <input name="task" value="" type="hidden">
    <input name="boxchecked" value="0" type="hidden">
    <input name="controller" value="seriesdisplay" type="hidden">
</form>
