<?php
/**
 * @version     $Id: default.php 2025 2011-08-28 04:08:06Z genu $
 * @package BibleStudy
 * @Copyright (C) 2007 - 2011 Joomla Bible Study Team All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.JoomlaBibleStudy.org
 * */
//No Direct Access
defined('_JEXEC') or die;
require_once (JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_biblestudy' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'biblestudy.defines.php');
JHtml::_('script', 'system/multiselect.js', false, true);
$user = JFactory::getUser();
$userId = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$canOrder = $user->authorise('core.edit.state');
$saveOrder = $listOrder == 'share.ordering';
?>
<form
    action="<?php echo JRoute::_('index.php?option=com_biblestudy&view=shares'); ?>"
    method="post" name="adminForm" id="adminForm">
    <fieldset id="filter-bar">
        <div class="filter-select fltrt">

            <select name="filter_published" class="inputbox"
                    onchange="this.form.submit()">
                <option value="">

                    <?php echo JText::_('JOPTION_SELECT_PUBLISHED'); ?></option>




                <?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true); ?>
            </select>
        </div>
    </fieldset>
    <div id="editcell">
        <table class="adminlist">
            <thead>
                <tr>
                    <th width="20"><input type="checkbox" name="toggle" value=""
                                          onclick="checkAll(<?php echo count($this->items); ?>);" />
                    </th>
                    <th width="20" align="center">
                        <?php echo JHtml::_('grid.sort', 'JBS_CMN_PUBLISHED', 'share.publish', $listDirn, $listOrder); ?>
                    </th>
                    <th width="10%">
                        <?php echo JHtml::_('grid.sort', 'JBS_CMN_ORDERING', 'share.ordering', $listDirn, $listOrder); ?>
                        <?php if ($canOrder && $saveOrder) : ?>
                            <?php echo JHtml::_('grid.order', $this->items, 'filesave.png', 'share.saveorder'); ?>
                        <?php endif; ?>
                    </th>
                    <th>
                        <?php echo JText::_('JBS_CMN_IMAGE'); ?>
                    </th>
                    <th>
                        <?php echo JHtml::_('grid.sort', 'JBS_SHR_SOCIAL_NETWORK', 'share.name', $listDirn, $listOrder); ?>
                    </th>
                </tr>
            </thead>




            <?php
            $n = count($this->items);
            foreach ($this->items as $i => $item) :
                $ordering = ($listOrder == 'share.ordering');
                $params = new JParameter($item->params);
                $link = JRoute::_('index.php?option=com_biblestudy&task=share.edit&id=' . (int) $item->id);
                ?>
                <tr class="row<?php echo $i % 2; ?>">
                    <td width="20">
                        <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                    </td>
                    <td width="20" align="center">
                        <?php echo JHtml::_('jgrid.published', $item->published, $i, 'shares.', true, 'cb', '', ''); ?>
                    </td>
                    <td class="order">
                        <?php if ($listDirn == 'asc') : ?>
                            <span><?php echo $this->pagination->orderUpIcon($i, ($item->id == @$this->items[$i - 1]->id), 'share.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
                            <span><?php echo $this->pagination->orderDownIcon($i, $n, ($this->pagination->total == @$this->items[$i + 1]->id), 'share.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
                        <?php elseif ($listDirn == 'desc') : ?>
                            <span><?php echo $this->pagination->orderUpIcon($i, ($item->id == @$this->items[$i - 1]->id), 'share.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
                            <span><?php echo $this->pagination->orderDownIcon($i, $n, ($this->pagination->total == @$this->items[$i + 1]->id), 'share.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
                        <?php endif; ?>
                        <?php $disabled = $saveOrder ? '' : 'disabled="disabled"'; ?>
                        <input type="text" name="order[]" size="5" value="<?php echo $item->ordering; ?>" <?php echo $disabled ?> class="text-area-order" />
                    </td>
                    <td width="60" align="left">
                        <?php echo '<img src="' . JURI::root() . $params->get('shareimage') . '">'; ?>
                    </td>
                    <td>
                        <a href="<?php echo $link; ?>"><?php echo $item->name; ?></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <tfoot>
                <tr><td colspan="10"> <?php echo $this->pagination->getListFooter(); ?> </td></tr></tfoot>
        </table>
    </div>
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
    <?php echo JHtml::_('form.token'); ?>

</form>