<?php
//No access
defined( '_JEXEC' ) or die;

// set date/time
date_default_timezone_set('Europe/Berlin');
setlocale (LC_TIME, 'de_DE');

//Add database instance
$db = JFactory::getDBO();
$jAp = JFactory::getApplication();

// getting further Information of the team
$query = $db->getQuery(true);
$query->select('*');
$query->from($db->quoteName('aaa_mannschaft'));
$query->where($db->quoteName('kuerzel').' = '.$db->quote($kuerzel));
$db->setQuery($query);
$mannschaft = $db->loadObject();

//display and convert to HTML when SQL error
if (is_null($posts=$db->loadRowList())) 
{
	$jAp->enqueueMessage(nl2br($db->getErrorMsg()),'error');
	return;
}

$query = $db->getQuery(true);
$query->select('COUNT(*)');
$query->from($db->quoteName(array('aaa_spiel','aaa_spielbericht')));
$query->where($db->quoteName('Kuerzel').' = '.$db->Quote($mannschaft->kuerzel).' AND '.
				$db->quoteName('aaa_spiel').'.'.$db->quoteName('spielIDhvw').' = '.$db->quoteName('aaa_spielbericht').'.'.$db->quoteName('spielIDhvw'));
$db->setQuery($query);
$recaps = $db->loadResult();
echo "<pre>"; print_r($recaps); echo "</pre>";


// getting schedule of the team from the DB
$query = $db->getQuery(true);
$query->select('*');
$query->from($db->quoteName('aaa_spiel'));
$query->leftJoin($db->quoteName('aaa_spielbericht').' USING ('.$db->quoteName('spielIDhvw').')');
$query->where($db->quoteName('Kuerzel').' = '.$db->Quote($mannschaft->kuerzel));
$query->order($db->quoteName(array('datum', 'uhrzeit')));
echo nl2br($query);//die; //see resulting query
$db->setQuery($query);
$rows = $db->loadObjectList();
echo "<pre>"; print_r($rows); echo "</pre>";
//display and convert to HTML when SQL error
if (is_null($posts=$db->loadRowList())) 
{
	$jAp->enqueueMessage(nl2br($db->getErrorMsg()),'error');
	return;
}



function markHeimInSpielplan($mannschaft, $heim, $class = false)
	{
		if (strcmp(trim($mannschaft), trim($heim)) == 0)
		{
			if ($class == true)
			{
				return " class=\"heim\"";
			}
			return " heim";
		}
		return '';
	}
