<?php
//don't allow other scripts to grab and execute our file
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

//This is the parameter we get from our xml file above
//echo __FILE__.'('.__LINE__.'):<pre>';print_r($params);echo'</pre>';
$posLeague = $params->get('posLeague');
$headlineOption = $params->get('headline');
$indicator = $params->get('indicator');
$reports = $params->get('reports');

// get parameter from component menu item
$menuitemid = JRequest::getInt('Itemid');
if ($menuitemid)
{
	$menu = JFactory::getApplication()->getMenu();
	$menuparams = $menu->getParams( $menuitemid );
}
$teamkey = $menuparams->get('teamkey');
$timezone = $menuparams->get('timezone'); //true: user-time, false:server-time
$dateformat = $menuparams->get('dateformat', true); //true: user-time, false:server-time

// Include the syndicate functions only once
require_once dirname(__FILE__).'/helper.php';

$team = modHbScheduleHelper::getTeam($teamkey);
$schedule = modHbScheduleHelper::getSchedule($team);
$tallyReports = modHbScheduleHelper::getReportNr($team);
if ($tallyReports < 1) {
	$reports = false;
}
//echo "<pre>"; print_r($schedule); echo "</pre>";
$headline = modHbScheduleHelper::getHeadline($headlineOption, $team);

//Returns the path of the layout file
require JModuleHelper::getLayoutPath('mod_hbschedule', $params->get('layout', 'default'));
