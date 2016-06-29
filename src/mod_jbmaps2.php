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
$mapmousescroll		= $params->get('mapmousescroll') == 1 ? 'true' : 'false';
$markerinfobehaviour= $params->get('markerinfobehaviour', 'click');
$markerdata			= $params->get('markerdata', '');
$mapcustomstyle		= $params->get('mapcustomstyle', '');
$mapcustommapname	= $params->get('mapcustommapname', '');
$maptrafficlayer	= $params->get('maptrafficlayer', 0);
$maptransitlayer	= $params->get('maptransitlayer', 0);
$mapbicyclinglayer	= $params->get('mapbicyclinglayer', 0);
$mapkmllayer		= $params->get('mapkmllayer', 0);
$apikey             = $params->get('apikey', '');
$loadmapsapi		= $params->get('loadmapsapi', 1);
$trigger			= $params->get('trigger', '');
$triggerdelay		= $params->get('triggerdelay', '0');
$dnsprefetch		= $params->get('dnsprefetch', '1');


//add px if no units are added
$mapwidth = is_numeric($mapwidth) ? $mapwidth.'px' : $mapwidth;
$mapheight = is_numeric($mapheight) ? $mapheight.'px' : $mapheight;

//add dns-prefetch links
if ($dnsprefetch !== '0') {
	$document->addHeadLink( '//maps.gstatic.com', 'dns-prefetch', 'rel' );
	$document->addHeadLink( '//maps.google.com', 'dns-prefetch', 'rel' );
	$document->addHeadLink( '//maps.googleapis.com', 'dns-prefetch', 'rel' );
	$document->addHeadLink( '//mt0.googleapis.com', 'dns-prefetch', 'rel' );
	$document->addHeadLink( '//mt1.googleapis.com', 'dns-prefetch', 'rel' );
}

//add the main script
if ($loadmapsapi) {
    $apikey = ($apikey === '') ? '' : '&key=' . $apikey;
	$mapsScript = '//maps.google.com/maps/api/js?language=' . $languageCode . $apikey;
	$document->addScript($mapsScript);
}

require JModuleHelper::getLayoutPath('mod_jbmaps2', $params->get('layout', 'default'));
?>