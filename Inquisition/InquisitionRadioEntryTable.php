<?php

require_once 'Swat/SwatFormField.php';
require_once 'Swat/SwatEntry.php';
require_once 'Swat/SwatYUI.php';
require_once 'Swat/SwatRadioTable.php';

/**
 * A custom radio table with text entries
 *
 * @package   Inquisition
 * @copyright 2011 silverorange
 */
class InquisitionRadioEntryTable extends SwatRadioTable
{
	// {{{ private properties

	private $entry_option_values = array();

	// }}}
	// {{{ public function __construct()

	/**
	 * Creates a new radiolist
	 *
	 * @see SwatWidget::__construct()
	 */
	public function __construct($id = null)
	{
		parent::__construct($id);

		$this->addJavaScript(
			'packages/inquisition/javascript/inquisition-radio-entry-table.js',
			Inquisition::PACKAGE_ID);

		$yui = new SwatYUI(array('dom', 'event'));
		$this->html_head_entry_set->addEntrySet($yui->getHtmlHeadEntrySet());
	}

	// }}}
	// {{{ public function process()

	/**
	 * Processes this radio list
	 */
	public function process()
	{
		parent::process();

		if ($this->hasEntry($this->value) &&
			$this->getEntryValue($this->value) === null) {
			$message = 'The selected option requires a value to be entered.';

			$this->addMessage(new SwatMessage($message, 'error'));
		}
	}

	// }}}
	// {{{ public function getEntryValue()

	public function getEntryValue($option_value)
	{
		$value = null;

		if ($this->hasEntry($option_value))
			$value = $this->getCompositeWidget('entry_'.$option_value)->value;

		return $value;
	}

	// }}}
	// {{{ public function setEntryValue()

	public function setEntryValue($option_value, $text)
	{
		if ($this->hasEntry($option_value))
			$this->getCompositeWidget('entry_'.$option_value)->value = $text;
	}

	// }}}
	// {{{ public function setEntryOption()

	public function setEntryOption($value)
	{
		$this->entry_option_values[] = $value;
	}

	// }}}
	// {{{ public function hasEntry()

	public function hasEntry($value)
	{
		return in_array($value, $this->entry_option_values);
	}

	// }}}
	// {{{ public function display()

	public function display()
	{
		parent::display();
		Swat::displayInlineJavaScript($this->getInlineJavaScript());
	}

	// }}}
	// {{{ protected function displayOptionLabel()

	protected function displayOptionLabel(SwatOption $option)
	{
		parent::displayOptionLabel($option);

		if ($this->hasEntry($option->value))
			$this->getCompositeWidget('entry_'.$option->value)->display();
	}

	// }}}
	// {{{ protected function createCompositeWidgets()

	protected function createCompositeWidgets()
	{
		parent::createCompositeWidgets();

		foreach ($this->entry_option_values as $value) {
			$entry = new SwatEntry($this->id.'_entry_'.$value);
			$entry->maxlength = 255;
			$this->addCompositeWidget($entry, 'entry_'.$value);
		}
	}

	// }}}
	// {{{ protected function getInlineJavaScript()

	protected function getInlineJavaScript()
	{
		return sprintf(
			"var %s_obj = new InquisitionRadioEntryTable(%s);",
			$this->id,
			SwatString::quoteJavaScriptString($this->id));
	}

	// }}}
}

?>
