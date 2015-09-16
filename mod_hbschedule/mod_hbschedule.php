<?php
//don't allow other scripts to grab and execute our file
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

//This is the parameter we get from our xml file above
//echo __FILE__.'('.__LINE__.'):<pre>';print_r($params);echo'</pre>';
$posLeague = $params->get('posLeague');
$headlineOption = $params->get('headline');
$indicator = (boolean) $params->get('indicator', false);
$reports = (boolean) $params->get('reports', false);
$timezone = (boolean) $params->get('timezone', false); //true: user-time, false:server-time
//echo __FILE__.'('.__LINE__.'):<pre>'.$timezone.' -> '.gettype($timezone).'</pre>';
$dateformat = $params->get('dateformat', true); 

// get parameter from component menu item
$menuitemid = JRequest::getInt('Itemid');
if ($menuitemid)
{
	$menu = JFactory::getApplication()->getMenu();
	$menuparams = $menu->getParams( $menuitemid );
}
$teamkey = $menuparams->get('teamkey');
//echo __FILE__.'('.__LINE__.'):<pre>'.$teamkey.'</pre>';

// Include the syndicate functions only once
require_once dirname(__FILE__).'/helper.php';

$team = modHbscheduleHelper::getTeam($teamkey);
//echo __FILE__.'('.__LINE__.'):<pre>';print_r($team);echo'</pre>';
$schedule = modHbscheduleHelper::getSchedule($team);
$tallyReports = modHbscheduleHelper::getReportNr($team);
if ($tallyReports < 1) {
	$reports = false;
}
//echo "<pre>"; print_r($schedule); echo "</pre>";
$headline = modHbscheduleHelper::getHeadline($headlineOption, $team);

//Returns the path of the layout file
require JModuleHelper::getLayoutPath('mod_hbschedule', $params->get('layout', 'default'));
