<?php defined('_JEXEC') or die('Restricted access'); ?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Details' ); ?></legend>

		<table class="admintable">
        <tr>
        <td width="100" class="key"><label for="published"><?php echo JText::_( 'JBS_CMN_PUBLISHED' ); ?></label></td>
        <td > <?php echo $this->lists['published'];
		?>
          </td>
      </tr>
      <tr>
        <td width="100" class="key"><label for="list_show"><?php echo JText::_( 'Show on List View' ); ?></label></td>
        <td > <?php echo $this->lists['list_show'];
		?>
          </td>
      </tr>
      <tr>
        <td width="100" align="right" class="key">
				<label for="ordering">
                               <?php echo JText::_( 'Ordering' ); ?>
				</label>
				</td>
                <td>
				<?php echo $this->lists['ordering']; ?>
			</td>

		<tr>
			<td width="100" align="right" class="key">
				<label for="teacher">
                              <?php echo JText::_( 'Teacher' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="teachername" id="teachername" size="32" maxlength="250" value="<?php echo $this->teacheredit->teachername;?>" />
			</td>
        </tr>
        <tr>
        <td width="100" align="right" class="key">
				<label for="title">
                                <?php echo JText::_( 'Title' ); ?>
				</label>
			</td>
            <td>
            	<input class="text_area" type="text" name="title" id="title" size="50" maxlength="50" value="<?php echo $this->teacheredit->title;?>" />
            </td>
        </tr>
        <tr><td class="key"><?php echo JText::_('Choose a Large Image');?></td><td><?php echo $this->lists['teacher_image']; echo '  '.JText::_('Current folder').': '.$this->directory.' -  <a  href="index.php?option=com_biblestudy&view=admin&layout=form" target="_blank">'.JText::_('Set default folder here').'</a>';?><br /><?php echo JText::_('This field will be used instead of below if image selected');?></td>
      </tr>
       <tr><td valign="top" class="key">
                              <?php echo JText::_( 'Teacher Image' ); ?>
           </td>
    <td> <?php  ?>
    <img <?php if(empty($this->teacheredit->teacher_image)){echo ('style="display: none;"');}?> id="imgteacher_image" src="<?php echo '../images/'.$this->admin_params->get('teachers_imagefolder', 'stories').'/'.$this->teacheredit->teacher_image;?>" name="teacherImage">
    <?php
	?>
    </td>

    </tr>
        <tr><td class="key"><?php echo JText::_('Choose a Thumbnail');?></td><td><?php echo $this->lists['teacher_thumbnail']; echo '  '.JText::_('Current folder').': '.$this->directory.' -  <a  href="index.php?option=com_biblestudy&view=admin&layout=form" target="_blank">'.JText::_('Set default folder here').'</a>';?><br /><?php echo JText::_('This field will be used instead of below if image selected');?></td>
      </tr>
       <tr><td valign="top" class="key">
                              <?php echo JText::_( 'Teacher Thumbnail' ); ?>
           </td>
    <td> <?php  ?>
    <img <?php if(empty($this->teacheredit->teacher_thumbnail)){echo ('style="display: none;"');}?> id="imgteacher_thumbnail" src="<?php echo '../images/'.$this->admin_params->get('teachers_imagefolder', 'stories').'/'.$this->teacheredit->teacher_thumbnail;?>" name="teacherThumb">
    <?php
	?>
    </td>

    </tr>
        <tr>
        <td width="100" align="right" class="key">
				<label for="image">
                               <?php echo JText::_( 'Large Image full URL' ); ?>
				</label>
			</td>
            <td>
            	<input class="text_area" type="text" name="image" id="image" size="150" maxlength="150" value="<?php echo $this->teacheredit->image;?>" />
            </td>
        </tr>
        <tr>
        <td width="100" align="right" class="key">
				<label for="imageh">
                               <?php echo JText::_( 'Image Height' ); ?>
				</label>
			</td>
            <td>
            	<input class="text_area" type="text" name="imageh" id="imageh" size="5" maxlength="5" value="<?php echo $this->teacheredit->imageh;?>" />
            </td>
        </tr>
        <tr>
        <td width="100" align="right" class="key">
				<label for="imagew">
                               <?php echo JText::_( 'Image Width' ); ?>
				</label>
			</td>
            <td>
            	<input class="text_area" type="text" name="imagew" id="imagew" size="5" maxlength="5" value="<?php echo $this->teacheredit->imagew;?>" />
            </td>
        </tr>
        <tr>
        <td width="100" align="right" class="key">
				<label for="phone">
                              <?php echo JText::_( 'Phone' ); ?>
				</label>
			</td>
        	<td>
            	<input class="text_area" type="text" name="phone" id="phone" size="50" maxlength="50" value="<?php echo $this->teacheredit->phone;?>" />
            </td>
        </tr>
         <tr>
         <td width="100" align="right" class="key">
				<label for="email">
                               <?php echo JText::_( 'Email or Link to Contact Page' ); ?>
				</label>
			</td>
        	<td>
            	<input class="text_area" type="text" name="email" id="email" size="100" maxlength="100" value="<?php echo $this->teacheredit->email;?>" />
            </td>
        </tr>
         <tr>
         <td width="100" align="right" class="key">
				<label for="website">
                              <?php echo JText::_( 'Website' ); ?>
				</label>
			</td>
        	<td>
            	<input class="text_area" type="text" name="website" id="website" size="100" maxlength="100" value="<?php echo $this->teacheredit->website;?>" />
            </td>
        </tr>
         <tr>
         <td width="100" align="right" class="key">
				<label for="short">
                              <?php echo JText::_( 'Short Description for List Page' ); ?>
				</label>
			</td>
        	<td><textarea class="text_area" name="short" cols="150" rows="4" id="short" ><?php echo $this->teacheredit->short;?></textarea>
            </td>
        </tr>
        <tr>
         <td width="100" align="right" class="key">
				<label for="thumb">
                               <?php echo JText::_( 'Thumbnail full URL' ); ?>
				</label>
			</td>
        	<td>
            	<input class="text_area" type="text" name="thumb" id="thumb" size="100" maxlength="100" value="<?php echo $this->teacheredit->thumb;?>" />
            </td>
        </tr>
        <tr>
         <td width="100" align="right" class="key">
				<label for="thumbh">
                               <?php echo JText::_( 'Thumbnail Height' ); ?>
				</label>
			</td>
        	<td>
            	<input class="text_area" type="text" name="thumbh" id="thumbh" size="3" maxlength="3" value="<?php echo $this->teacheredit->thumbh;?>" />
            </td>
        </tr>
        <tr>
         <td width="100" align="right" class="key">
				<label for="thumbw">
                               <?php echo JText::_( 'Thumbnail Width' ); ?>
				</label>
			</td>
        	<td>
            	<input class="text_area" type="text" name="thumbw" id="thumbw" size="3" maxlength="3" value="<?php echo $this->teacheredit->thumbw;?>" />
            </td>
        </tr>
		 <tr>
         <td width="100" align="right" class="key">
				<label for="information">
                              <?php echo JText::_( 'Other Information' ); ?>
				</label>
			</td>
        	<td>
            	<?php echo $this->editor->display('information', $this->teacheredit->information, '100%', '400', '70', '15'); ?>
            </td>
        </tr>
	</table>
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_biblestudy" />
<input type="hidden" name="id" value="<?php echo $this->teacheredit->id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="teacheredit" />
<input type="hidden" name="catid" value="1" />
</form>
