<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');

class JFormFieldMarkers extends JFormField {

	public function getInput() {
return '<div id="markers">Marker fields will appear here after adding a marker by clicking on the map</div>';
}
}