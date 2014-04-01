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
		marker".$module->id." = new google.maps.Marker({
			position: position,
			markerid: markers".$module->id."[i][0],
			title: markers".$module->id."[i][3],
			icon: markers".$module->id."[i][4],
			shadow: markers".$module->id."[i][5],
			windowcontent: markers".$module->id."[i][6],
			map: map".$module->id."
		});
console.debug(marker".$module->id.");";
if ($markerinfobehaviour === 'mouseover') {
	$script .= "google.maps.event.addListener(marker".$module->id.", 'mouseover', (function(marker".$module->id.", i) {
		return function() {
			if (this.windowcontent !== '') {
        if (infowindow".$module->id.".getMap(this)) {
          infowindow".$module->id.".close(map".$module->id.", marker".$module->id.");
        }else{
          infowindow".$module->id.".setContent(this.windowcontent);
          infowindow".$module->id.".open(map".$module->id.", marker".$module->id.");
        };
      };
			if (this.windowcontent !== '') {
				infowindow".$module->id.".setContent(this.windowcontent);
				infowindow".$module->id.".open(map".$module->id.", marker".$module->id.");
			};
		}
	})(marker".$module->id.", i));

google.maps.event.addListener(marker".$module->id.", 'mouseout', function() { 
	infowindow".$module->id.".close();
});
";
} else {
	$script .= "google.maps.event.addListener(marker".$module->id.", 'click', (function(marker".$module->id.", i) {
		return function() {
			if (this.windowcontent !== '') {
				if (infowindow".$module->id.".getMap(this)) {
					infowindow".$module->id.".close(map".$module->id.", marker".$module->id.");
				}else{
					infowindow".$module->id.".setContent(this.windowcontent);
					infowindow".$module->id.".open(map".$module->id.", marker".$module->id.");
				};
			};
		}
	})(marker".$module->id.", i));
";
}
$script .= "}";
}

if ($mapcustomstyle) {
	$script .= "map".$module->id.".setOptions({styles: styles".$module->id."});";
}

if ($maptilt) {
	$script .= "map".$module->id.".setTilt(".$maptilt.");";
}
if ($mapheading) {
	$script .= "map".$module->id.".setHeading(".$mapheading.");";
}


if ($mapweatherlayer) {
	$script .= "var weatherLayer".$module->id." = new google.maps.weather.WeatherLayer({
		temperatureUnits: ".$mapweatherunits.",
		windSpeedUnits: ".$mapwindunits.",
		labelColor: ".$mapweatherlabels."
	});
weatherLayer".$module->id.".setMap(map".$module->id.");
";
}
if ($mapcloudlayer) {
	$script .= "var cloudLayer".$module->id." = new google.maps.weather.CloudLayer();
	cloudLayer".$module->id.".setMap(map".$module->id.");
	";
}
if ($maptrafficlayer) {
	$script .= "var trafficLayer".$module->id." = new google.maps.TrafficLayer();
	trafficLayer".$module->id.".setMap(map".$module->id.");
	";
}
if ($maptransitlayer) {
	$script .= "var transitLayer".$module->id." = new google.maps.TransitLayer();
	transitLayer".$module->id.".setMap(map".$module->id.");
	";
}
if ($mapbicyclinglayer) {
	$script .= "var bikeLayer".$module->id." = new google.maps.BicyclingLayer();
	bikeLayer".$module->id.".setMap(map".$module->id.");
	";
}
if ($mapkmllayer) {
	$script .= "var kmlLayer".$module->id." = new google.maps.KmlLayer({
		url: '".$mapkmllayer."'
	});
kmlLayer".$module->id.".setMap(map".$module->id.");
";
}
$script .= "}";

$script .= "google.maps.event.addDomListener(window, 'load', initialize".$module->id.");";

$document->addStyleDeclaration($mapcss);
$document->addScriptDeclaration($script);
?>
<div id="jbmaps2-<?php echo $module->id; ?>"<?php if($moduleclass_sfx) {echo ' class="'.$moduleclass_sfx.'"';}; ?>></div>