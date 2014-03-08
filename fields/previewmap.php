<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');

class JFormFieldPreviewMap extends JFormField {

	protected $type = 'City';

        // getLabel() left out

	public function getInput() {
		$document = JFactory::getDocument();
		$document->addStyleDeclaration('.gmnoprint img {max-width: none;}#jform_params_mapcustomstyle{height:300px;width:400px}#map{margin-left:-180px;padding-right:180px}label#jform_params_previewmap-lbl,#map{clear:both}');		
		$document->addScript('http://maps.google.com/maps/api/js?sensor=false');
		$document->addScript(JURI::root(true). '/modules/mod_jbmaps2/js/admin.js');

return '<div id="map" style="height:300px"></div><div id="jbmaps2-maptype"></div>';
}
}