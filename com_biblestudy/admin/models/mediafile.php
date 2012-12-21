<?php

/**
 * Madel for MediaFile Admin
 *
 * @package BibleStudy.Admin
 * @copyright (C) 2007 - 2011 Joomla Bible Study Team All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link    http://www.JoomlaBibleStudy.org
 * */
// No Direct Access
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * MediaFile model class
 *
 * @package BibleStudy.Admin
 * @since   7.0.0
 */
class BiblestudyModelMediafile extends JModelAdmin
{


	/**
	 * Admin
	 *
	 * @var string
	 */
	var $_admin;

	/**
	 * @var    string  The prefix to use with controller messages.
	 * @since  1.6
	 */
	protected $text_prefix = 'COM_BIBLESTUDY';

	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param       array   $data   An array of input data.
	 * @param       string  $key    The name of the key for the primary key.
	 *
	 * @return      boolean
	 * @since       1.6
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		// Check specific edit permission then general edit permission.
		return JFactory::getUser()->authorise('core.edit', 'com_biblestudy.mediafile.' . ((int) isset($data[$key]) ? $data[$key] : 0)) or parent::allowEdit($data, $key);
	}

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param    object    $record    A record object.
	 *
	 * @return    boolean    True if allowed to delete the record. Defaults to the permission set in the component.
	 * @since    1.6
	 */
	protected function canDelete($record)
	{
		if (!empty($record->id)) {
			if ($record->state != -2) {
				return;
			}
			$user = JFactory::getUser();

			return $user->authorise('core.delete', 'com_biblestudy.mediafile.' . (int) $record->id);
		}
	}

	/**
	 * Method to move a mediafile listing
	 *
	 * @param string $direction
	 *
	 * @access    public
	 * @return    boolean    True on success
	 * @since    1.5
	 */
	public function move($direction)
	{
		$row = & $this->getTable();
		if (!$row->load($this->_id)) {
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		if (!$row->move($direction, ' study_id = ' . (int) $row->study_id . ' AND published >= 0 ')) {
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		return true;
	}

	/**
	 * Overrides the JModelAdmin save routine in order to implode the podcast_id
	 *
	 * @param array $data
	 *
	 * @return  <Boolean> True on sucessfull save
	 * @since   7.0
	 */
	public function save($data)
	{
		if (parent::save($data)) {
			//Implode only if they selected at least one podcast. Otherwise just clear the podcast_id field
			$data['podcast_id'] = empty($data['podcast_id']) ? '' : implode(',', $data['podcast_id']);
			//This code could be uncommented and would remove spaces from filename
			//$data['filename'] = str_replace(' ','_',$data['filename']);
			// Remove starting and traling spaces
			$data['filename'] = trim($data['filename']);

			return true;
		} else {
			return false;
		}
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @param    JTable    $table
	 *
	 * @return    void
	 * @since    1.6
	 */
	protected function prepareTable($table)
	{
	}

	/**
	 * Returns a Table object, always creating it.
	 *
	 * @param    type      The table type to instantiate
	 * @param    string    A prefix for the table class name. Optional.
	 * @param    array     Configuration array for model. Optional.
	 *
	 * @return    JTable    A database object
	 */
	public function getTable($type = 'Mediafile', $prefix = 'Table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Get the form data
	 *
	 * @param array   $data
	 * @param boolean $loadData
	 *
	 * @return boolean|object
	 * @since 7.0
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_biblestudy.mediafile', 'mediafile', array(
			'control'   => 'jform',
			'load_data' => $loadData
		));
		if (empty($form)) {
			return false;
		}
		$jinput = JFactory::getApplication()->input;

		// The front end calls this model and uses a_id to avoid id clashes so we need to check for that first.
		if ($jinput->get('a_id')) {
			$id = $jinput->get('a_id', 0);
		} // The back end uses id so we use that the rest of the time and set it to 0 by default.
		else {
			$id = $jinput->get('id', 0);
		}

		$user = JFactory::getUser();

		// Check for existing article.
		// Modify the form based on Edit State access controls.
		if ($id != 0 && (!$user->authorise('core.edit.state', 'com_biblestudy.mediafile.' . (int) $id))
				|| ($id == 0 && !$user->authorise('core.edit.state', 'com_biblestudy'))
		) {
			// Disable fields for display.
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('state', 'disabled', 'true');

			// Disable fields while saving.
			// The controller has already verified this is an article you can edit.
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('state', 'filter', 'unset');

		}

		return $form;
	}

	/**
	 * Batch Player changes for a group of mediafiles.
	 *
	 * @param   string  $value     The new value matching a player.
	 * @param   array   $pks       An array of row IDs.
	 * @param   array   $contexts  An array of item contexts.
	 *
	 * @return  boolean  True if successful, false otherwise and internal error is set.
	 *
	 * @since   2.5
	 */
	protected function batchPlayer($value, $pks, $contexts)
	{
		// Set the variables
		$user  = JFactory::getUser();
		$table = $this->getTable();

		foreach ($pks as $pk) {
			if ($user->authorise('core.edit', $contexts[$pk])) {
				$table->reset();
				$table->load($pk);
				$table->player = (int) $value;

				if (!$table->store()) {
					$this->setError($table->getError());

					return false;
				}
			} else {
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));

				return false;
			}
		}

		// Clean the cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Batch popup changes for a group of media files.
	 *
	 * @param   string  $value     The new value matching a client.
	 * @param   array   $pks       An array of row IDs.
	 * @param   array   $contexts  An array of item contexts.
	 *
	 * @return  boolean  True if successful, false otherwise and internal error is set.
	 *
	 * @since   2.5
	 */
	protected function batchPopup($value, $pks, $contexts)
	{
		// Set the variables
		$user  = JFactory::getUser();
		$table = $this->getTable();

		foreach ($pks as $pk) {
			if ($user->authorise('core.edit', $contexts[$pk])) {
				$table->reset();
				$table->load($pk);
				$table->popup = (int) $value;

				if (!$table->store()) {
					$this->setError($table->getError());

					return false;
				}
			} else {
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));

				return false;
			}
		}

		// Clean the cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Batch popup changes for a group of media files.
	 *
	 * @param   string  $value     The new value matching a client.
	 * @param   array   $pks       An array of row IDs.
	 * @param   array   $contexts  An array of item contexts.
	 *
	 * @return  boolean  True if successful, false otherwise and internal error is set.
	 *
	 * @since   2.5
	 */
	protected function batchMediatype($value, $pks, $contexts)
	{
		// Set the variables
		$user  = JFactory::getUser();
		$table = $this->getTable();

		foreach ($pks as $pk) {
			if ($user->authorise('core.edit', $contexts[$pk])) {
				$table->reset();
				$table->load($pk);
				$table->media_image = (int) $value;

				if (!$table->store()) {
					$this->setError($table->getError());

					return false;
				}
			} else {
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));

				return false;
			}
		}

		// Clean the cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Batch popup changes for a group of media files.
	 *
	 * @param   string  $value     The new value matching a client.
	 * @param   array   $pks       An array of row IDs.
	 * @param   array   $contexts  An array of item contexts.
	 *
	 * @return  boolean  True if successful, false otherwise and internal error is set.
	 *
	 * @since   2.5
	 */
	protected function batchlink_type($value, $pks, $contexts)
	{
		// Set the variables
		$user  = JFactory::getUser();
		$table = $this->getTable();

		foreach ($pks as $pk) {
			if ($user->authorise('core.edit', $contexts[$pk])) {
				$table->reset();
				$table->load($pk);
				$table->link_type = (int) $value;

				if (!$table->store()) {
					$this->setError($table->getError());

					return false;
				}
			} else {
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));

				return false;
			}
		}

		// Clean the cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Batch popup changes for a group of media files.
	 *
	 * @param   string  $value     The new value matching a client.
	 * @param   array   $pks       An array of row IDs.
	 * @param   array   $contexts  An array of item contexts.
	 *
	 * @return  boolean  True if successful, false otherwise and internal error is set.
	 *
	 * @since   2.5
	 */
	protected function batchMimetype($value, $pks, $contexts)
	{
		// Set the variables
		$user  = JFactory::getUser();
		$table = $this->getTable();

		foreach ($pks as $pk) {
			if ($user->authorise('core.edit', $contexts[$pk])) {
				$table->reset();
				$table->load($pk);
				$table->mime_type = (int) $value;

				if (!$table->store()) {
					$this->setError($table->getError());

					return false;
				}
			} else {
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));

				return false;
			}
		}

		// Clean the cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Method to perform batch operations on an item or a set of items.
	 *
	 * @param   array   $commands   An array of commands to perform.
	 * @param   array   $pks        An array of item ids.
	 * @param   array   $contexts   An array of item contexts.
	 *
	 * @return    boolean     Returns true on success, false on failure.
	 *
	 * @since    2.5
	 */
	public function batch($commands, $pks, $contexts)
	{
		// Sanitize user ids.
		$pks = array_unique($pks);
		JArrayHelper::toInteger($pks);

		// Remove any values of zero.
		if (array_search(0, $pks, true)) {
			unset($pks[array_search(0, $pks, true)]);
		}

		if (empty($pks)) {
			$this->setError(JText::_('JGLOBAL_NO_ITEM_SELECTED'));

			return false;
		}

		$done = false;


		if (strlen($commands['player']) > 0) {
			if (!$this->batchPlayer($commands['player'], $pks, $contexts)) {
				return false;
			}

			$done = true;
		}
		if (strlen($commands['link_type']) > 0) {
			if (!$this->batchlink_type($commands['link_type'], $pks, $contexts)) {
				return false;
			}

			$done = true;
		}
		if (strlen($commands['mimetype']) > 0) {
			if (!$this->batchMimetype($commands['mimetype'], $pks, $contexts)) {
				return false;
			}

			$done = true;
		}

		if (strlen($commands['mediatype']) > 0) {
			if (!$this->batchMediatype($commands['mediatype'], $pks, $contexts)) {
				return false;
			}

			$done = true;
		}
		if (strlen($commands['popup']) > 0) {
			if (!$this->batchPopup($commands['popup'], $pks, $contexts)) {
				return false;
			}

			$done = true;
		}

		if (!$done) {
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_INSUFFICIENT_BATCH_INFORMATION'));

			return false;
		}

		// Clear the cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Load Form Data
	 *
	 * @return array
	 * @since   7.0
	 */
	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_biblestudy.edit.mediafile.data', array());
		if (empty($data)) {
			$data             = $this->getItem();
			$data->podcast_id = explode(',', $data->podcast_id);

		}
		return $data;
	}

	/**
	 * Auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return    void
	 * @since    3.0
	 */
	protected function preprocessForm(JForm $form, $data, $group = 'content')
	{
		parent::preprocessForm($form, $data, $group);
	}

	/**
	 * Custom clean the cache of com_biblestudy and biblestudy modules
	 *
	 * @param string $group
	 * @param int    $client_id
	 *
	 * @since    1.6
	 */
	protected function cleanCache($group = null, $client_id = 0)
	{
		parent::cleanCache('com_biblestudy');
		parent::cleanCache('mod_biblestudy');
	}
}