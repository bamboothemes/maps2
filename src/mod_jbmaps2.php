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

$document = JFactory::getDocument();
//get the correct language code for the map
$languages = JLanguageHelper::getLanguages('lang_code');
$languageCode = $languages[ $lang->getTag() ]->sef;

//module params
$moduleclass_sfx	= $params->get('moduleclass_sfx');
$mapwidth			= $params->get('mapwidth', '100%');
$mapheight			= $params->get('mapheight', '400px');
$mapcontrols		= $params->get('mapcontrols', '');
$mapzoom			= $params->get('mapzoom', '5');
$maptilt			= $params->get('maptilt', 0);
$mapheading			= $params->get('mapheading', 0);
$mapcentlat			= $params->get('mapcentlat', '54.525961');
$mapcentlon			= $params->get('mapcentlon', '15.255119');
$maptype			= $params->get('maptype', 'ROADMAP');
$mapdraggable		= $params->get('mapdraggable') == 1 ? 'true' : 'false';
$markerinfobehaviour= $params->get('markerinfobehaviour', 'click');
$markerdata			= $params->get('markerdata', '');
$mapcustomstyle		= $params->get('mapcustomstyle', '');
$mapcustommapname	= $params->get('mapcustommapname', '');
$mapweatherlayer	= $params->get('mapweatherlayer', 0);
$mapweatherunits	= $params->get('mapweatherunits', 'google.maps.weather.TemperatureUnit.CELSIUS');
$mapwindunits		= $params->get('mapwindunits', 'google.maps.weather.WindSpeedUnit.METERS_PER_SECOND');
$mapweatherlabels	= $params->get('mapweatherlabels') == 0 ? 'null' : $params->get('mapweatherlabels');
$mapcloudlayer		= $params->get('mapcloudlayer', 0);
$maptrafficlayer	= $params->get('maptrafficlayer', 0);
$maptransitlayer	= $params->get('maptransitlayer', 0);
$mapbicyclinglayer	= $params->get('mapbicyclinglayer', 0);
$mapkmllayer		= $params->get('mapkmllayer', 0);
$trigger			= $params->get('trigger', '');
$triggerdelay		= $params->get('triggerdelay', '0');
//$			= $params->get('', '');
//$			= $params->get('', '');
//$			= $params->get('', '');
//$			= $params->get('', '');

//add the main script
$mapsScript = '//maps.google.com/maps/api/js?sensor=false&amp;libraries=weather&amp;language='.$languageCode;
$document->addScript($mapsScript);

require JModuleHelper::getLayoutPath('mod_jbmaps2', $params->get('layout', 'default'));
?>
