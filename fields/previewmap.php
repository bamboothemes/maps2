<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');

class JFormFieldPreviewMap extends JFormField {

	public function getInput() {
		$document = JFactory::getDocument();
		$document->addStyleDeclaration('.gmnoprint img {max-width: none;}#jform_params_mapcustomstyle{height:300px;width:400px}label#jform_params_previewmap-lbl,#map{clear:both}');		
		$document->addScript('http://maps.google.com/maps/api/js?sensor=false');
		$document->addScript(JURI::root(true). '/modules/mod_jbmaps2/js/jquery.gomap-1.3.2.min.js');
		$document->addScript(JURI::root(true). '/modules/mod_jbmaps2/js/admin.js');
		$document->addScript(JURI::root(true). '/modules/mod_jbmaps2/js/bootbox.min.js');

return '<div id="map" style="height:300px;width:500px"></div><div id="jbmaps2-notices"></div>';
}
}