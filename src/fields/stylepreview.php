<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');

class JFormFieldStylePreview extends JFormField {

	public function getInput() {
return '<div id="stylepreview" style="width:600px;height:400px"></div>';
}
}