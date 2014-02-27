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
 
require JModuleHelper::getLayoutPath('mod_jbmaps2', $params->get('layout', 'default'));
?>