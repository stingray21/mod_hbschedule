<?php
//don't allow other scripts to grab and execute our file
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

//This is the parameter we get from our xml file above
$checkForGameReports = $params->get('checkForGameReports');
$posLeague = $params->get('posLeague');
$headline = $params->get('headline');

// get parameter from component menu item
$menuitemid = JRequest::getInt('Itemid');
if ($menuitemid)
{
	$menu = JSite::getMenu();
	$menuparams = $menu->getParams( $menuitemid );
}
$kuerzel = $menuparams->get('teamkey');

//$posLeague = $menuparams->get('posLeague');
//$headline = $menuparams->get('headline');

// Include the syndicate functions only once
require_once dirname(__FILE__).'/helper.php';

//Returns the path of the layout file
require JModuleHelper::getLayoutPath('mod_hbschedule', $params->get('layout', 'default'));

