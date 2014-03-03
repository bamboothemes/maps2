<?php
/**
 * @package		JB Maps2
 * @subpackage	JB Maps2
 * @author		Joomla Bamboo - design@joomlabamboo.com
 * @copyright 	Copyright (c) 2014 Joomla Bamboo. All rights reserved.
 * @license		GNU General Public License version 2 or later
 * @version		1.0.0
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Include the syndicate functions only once
require_once( dirname(__FILE__).'/helper.php' );

$document = JFactory::getDocument();
//get the correct language code for the map
$languages = JLanguageHelper::getLanguages('lang_code');
$languageCode = $languages[ $lang->getTag() ]->sef;

//add the main script
$document->addScript('http://maps.google.com/maps/api/js?sensor=false&language='.$languageCode);

//module params
$moduleclass_sfx	= $params->get('moduleclass_sfx');
$mapwidth			= $params->get('mapwidth', '100%');
$mapheight			= $params->get('mapheight', '400px');
$mapcontrols		= $params->get('mapcontrols', '');
$mapzoom			= $params->get('mapzoom', '5');
$maptilt			= $params->get('maptilt', '0');
$mapheading			= $params->get('mapheading', '0');
$mapcentlat			= $params->get('mapcentlat', '54.525961');
$mapcentlon			= $params->get('mapcentlon', '15.255119');
$maptype			= $params->get('maptype', 'ROADMAP');
$mapdraggable		= $params->get('mapdraggable') == 1 ? 1 : 0;
$mapcustomstyle		= $params->get('mapcustomstyle', '');
$mapcustommapname	= $params->get('mapcustommapname', '');
//$					= $params->get('', '');

//check which controls should be on and off
$allmapcontrols = array('zoomControl','panControl','mapTypeControl','scaleControl','streetViewControl','rotateControl','overviewMapControl');
$controls = '';
if ($mapcontrols) {
	foreach ($allmapcontrols as $control) {
		if (in_array($control, $mapcontrols)) {
			$controls .= $control.':1,';
		} else {
			$controls .= $control.':0,';
		}
	}
}

$mapcss = '#jbmaps2-'.$module->id.'{width:'.$mapwidth.';height:'.$mapheight.';max-width:100%}.gmnoprint img {max-width: none;}';

//check for custom styles
$styles = '';
if ($mapcustomstyle && $mapcustommapname) {
	$styles = "var styles".$module->id." = ".$mapcustomstyle."; var styledMap".$module->id." = new google.maps.StyledMapType(styles".$module->id.", {name: '".$mapcustommapname."'});";
}

$script = "
function initialize".$module->id."() {";
//add cutom style
if ($mapcustomstyle && $mapcustommapname) {
	$script .= $styles;
}
$script .= "	var mapOptions".$module->id." = {
	center: new google.maps.LatLng(".$mapcentlat.", ".$mapcentlon."),";
	//replace map type if custom styles
	if ($mapcustomstyle && $mapcustommapname) {
		$script .= "mapTypeControlOptions: {
			mapTypeIds: [google.maps.MapTypeId.".$maptype.", 'map_style".$module->id."']
		},";
	} else {
		$script .= "mapTypeId: google.maps.MapTypeId.".$maptype.",";
	}

	$script .= $controls."
	draggable: ".$mapdraggable.",
	zoom: ".$mapzoom."
};
var map".$module->id." = new google.maps.Map(document.getElementById('jbmaps2-".$module->id."'), mapOptions".$module->id.");";
//associate custom style with map if needed
if ($mapcustomstyle && $mapcustommapname) {
	$script .= "map".$module->id.".mapTypes.set('map_style".$module->id."', styledMap".$module->id.");
	map".$module->id.".setMapTypeId('map_style".$module->id."');";
}
$script .= "
map".$module->id.".setTilt(".$maptilt.");
map".$module->id.".setHeading(".$mapheading.");
}
google.maps.event.addDomListener(window, 'load', initialize".$module->id.");
";
$document->addStyleDeclaration($mapcss);
$document->addScriptDeclaration($script);

//require JModuleHelper::getLayoutPath('mod_jbmaps2', $params->get('layout', 'default'));
?>
<div id="jbmaps2-<?php echo $module->id; ?>"<?php if($moduleclass_sfx) {echo ' class="'.$moduleclass_sfx.'"';}; ?>></div>
