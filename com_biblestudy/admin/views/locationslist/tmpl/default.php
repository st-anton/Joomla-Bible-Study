<?php defined('_JEXEC') or die('Restricted access');
require_once (JPATH_ADMINISTRATOR  .DS. 'components' .DS. 'com_biblestudy' .DS. 'lib' .DS. 'biblestudy.defines.php');
 ?>
<form action="<?php echo JRoute::_('index.php?option=com_biblestudy&view=locationslist'); ?>" method="post" name="adminForm" id="adminForm">
    <div id="editcell">
        <table class="adminlist">
            <thead>
                <tr>
                    <th width="20">
                        <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
                    </th>
                    <th width="20" align="center">
                        <?php echo JText::_('JBS_CMN_PUBLISHED'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('JBS_LOC_LOCATION_NAME'); ?>
                    </th>
                </tr>
            </thead>
            <?php
                        foreach ($this->items as $i => $item) :
                            $link = JRoute::_('index.php?option=com_biblestudy&task=locationsedit.edit&id=' . (int) $item->id);
            ?>
                            <tr class="row<?php echo $i % 2; ?>">
                                <td width="20">
                    <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                        </td>
                        <td width="20" align="center">
                    <?php echo JHtml::_('jgrid.published', $item->published, $i, 'locationslist.', true, 'cb', '', ''); ?>
                        </td>
                        <td>
                            <a href="<?php echo $link; ?>"><?php echo $item->location_text; ?></a>
                        </td>
                    </tr>
            <?php endforeach; ?>
        </table>
    </div>
                    <input type="hidden" name="task" value=""/>
                    <input type="hidden" name="boxchecked" value="0"/>
                    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
                    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
    <?php echo JHtml::_('form.token'); ?>
</form>
