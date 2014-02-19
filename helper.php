<?php
//No access
defined( '_JEXEC' ) or die;

//Add database instance
$db = JFactory::getDBO();
$jAp = JFactory::getApplication();

// getting further Information of the team
$query = $db->getQuery(true);
$query->select('*');
$query->from($db->quoteName('hb_mannschaft'));
$query->where($db->quoteName('kuerzel').' = '.$db->quote($kuerzel));
$db->setQuery($query);
$team = $db->loadObject();

//display and convert to HTML when SQL error
if (is_null($posts=$db->loadRowList())) 
{
	$jAp->enqueueMessage(nl2br($db->getErrorMsg()),'error');
	return;
}

$query = $db->getQuery(true);
$query->select('COUNT(*)');
$query->from($db->quoteName(array('hb_spiel','hb_spielbericht')));
$query->where($db->quoteName('Kuerzel').' = '.$db->Quote($team->kuerzel).' AND '.
				$db->quoteName('hb_spiel').'.'.$db->quoteName('spielIDhvw').' = '.$db->quoteName('hb_spielbericht').'.'.$db->quoteName('spielIDhvw'));
$db->setQuery($query);
$recaps = $db->loadResult();
//echo "<pre>"; print_r($recaps); echo "</pre>";


// getting schedule of the team from the DB
$query = $db->getQuery(true);
$query->select('*');
$query->from($db->quoteName('hb_spiel'));
$query->leftJoin($db->quoteName('hb_spielbericht').' USING ('.$db->quoteName('spielIDhvw').')');
$query->where($db->quoteName('Kuerzel').' = '.$db->Quote($team->kuerzel));
$query->order($db->quoteName(array('datum', 'uhrzeit')));
//echo nl2br($query);//die; //see resulting query
$db->setQuery($query);
$rows = $db->loadObjectList();
//echo "<pre>"; print_r($rows); echo "</pre>";
//display and convert to HTML when SQL error
if (is_null($posts=$db->loadRowList())) 
{
	$jAp->enqueueMessage(nl2br($db->getErrorMsg()),'error');
	return;
}



function markHomeInSchedule($team, $hometeam, $class = false)
	{
		if (strcmp(trim($team), trim($hometeam)) == 0)
		{
			if ($class == true)
			{
				return " class=\"heim\"";
			}
			return " heim";
		}
		return '';
	}
