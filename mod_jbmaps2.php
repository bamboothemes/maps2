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
$markerinfobehaviour= $params->get('markerinfobehaviour', 'click');
$markerdata			= $params->get('markerdata', '');
$mapcustomstyle		= $params->get('mapcustomstyle', '');
$mapcustommapname	= $params->get('mapcustommapname', '');
//$					= $params->get('', '');

//check which controls should be on and off
$allmapcontrols = array('zoomControl','panControl','mapTypeControl','scaleControl','streetViewControl','rotateControl','overviewMapControl');
$controls = '';
if ($mapcontrols) { //we have at least one selected so turn each on or off as needed
	foreach ($allmapcontrols as $control) {
		if (in_array($control, $mapcontrols)) {
			$controls .= $control.':1,';
		} else {
			$controls .= $control.':0,';
		}
	}
} else { //turn off all controls if there are none selected
	foreach ($allmapcontrols as $control) {
		$controls .= $control.':0,';
	}
}

$mapcss = '#jbmaps2-'.$module->id.'{width:'.$mapwidth.';height:'.$mapheight.';max-width:100%}.gmnoprint img {max-width: none;}';

//check for custom styles
$styles = '';
if ($mapcustomstyle) {
	$styles = "var styles".$module->id." = ".$mapcustomstyle.";";
}

$script = "
function initialize".$module->id."() {";
//add cutom style
if ($mapcustomstyle) {
	$script .= $styles;
}
$script .= "	var mapOptions".$module->id." = {
	center: new google.maps.LatLng(".$mapcentlat.", ".$mapcentlon."),
	mapTypeId: google.maps.MapTypeId.".$maptype.",
	".$controls."
	draggable: ".$mapdraggable.",
	zoom: ".$mapzoom."
};
var map".$module->id." = new google.maps.Map(document.getElementById('jbmaps2-".$module->id."'), mapOptions".$module->id.");";

if ($markerdata && $markerdata !== '[]') {
	$script .= "var infowindow".$module->id." = new google.maps.InfoWindow();
	var markers".$module->id." = ".$markerdata.";
	for( i = 0; i < markers".$module->id.".length; i++ ) {
		var position = new google.maps.LatLng(markers".$module->id."[i][1], markers".$module->id."[i][2]);
		marker = new google.maps.Marker({
			position: position,
			title: markers".$module->id."[i][3],
			icon: markers".$module->id."[i][4],
			shadow: markers".$module->id."[i][5],
			windowcontent: markers".$module->id."[i][6],
			map: map".$module->id."
		});";
if ($markerinfobehaviour === 'mouseover') {
	$script .= "google.maps.event.addListener(marker, 'mouseover', (function(marker, i) {
		return function() {
			if (this.windowcontent !== '') {
				infowindow".$module->id.".setContent(this.windowcontent);
				infowindow".$module->id.".open(map".$module->id.", marker);
			};
		}
	})(marker, i));

google.maps.event.addListener(marker, 'mouseout', function() { 
	infowindow".$module->id.".close();
});
";
} else {
	$script .= "google.maps.event.addListener(marker, 'click', (function(marker, i) {
		return function() {
			if (this.windowcontent !== '') {
				infowindow".$module->id.".setContent(this.windowcontent);
				infowindow".$module->id.".open(map".$module->id.", marker);
			};
		}
	})(marker, i));
";
}
$script .= "}";
}

if ($mapcustomstyle) {
	$script .= "map".$module->id.".setOptions({styles: styles".$module->id."});";
}

$script .= "
map".$module->id.".setTilt(".$maptilt.");
map".$module->id.".setHeading(".$mapheading.");
}";

$script .= "google.maps.event.addDomListener(window, 'load', initialize".$module->id.");";

$document->addStyleDeclaration($mapcss);
$document->addScriptDeclaration($script);

//require JModuleHelper::getLayoutPath('mod_jbmaps2', $params->get('layout', 'default'));
?>
<div id="jbmaps2-<?php echo $module->id; ?>"<?php if($moduleclass_sfx) {echo ' class="'.$moduleclass_sfx.'"';}; ?>></div>
