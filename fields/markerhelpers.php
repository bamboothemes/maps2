<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');

class JFormFieldMarkerHelpers extends JFormField {

	public function getInput() {
		return '<div id="markerhelpers"></div>';
	}
}