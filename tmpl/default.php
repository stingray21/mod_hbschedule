<?php 
defined( '_JEXEC' ) or die( 'Restricted access' );

// set date/time
date_default_timezone_set('Europe/Berlin');
setlocale (LC_TIME, 'de_DE');

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base() . 'modules/mod_hbschedule/css/hbschedule.css');
$document->addStyleSheet(JURI::base() . 'modules/mod_hbschedule/css/default.css');

//echo "<pre>"; print_r($rows); echo "</pre>";

if (count($rows)>0)
{
	//echo "<p>".JText::_('DESC_MODULE')."</p>";
	
	// Headline
	echo '<h3>';
	switch ($headline)
	{
		case 'title':
			echo 'Spielplan';
			break;
		case 'not':
			break;
		case 'titleandteam':
		default:
			echo 'Spielplan - '.$mannschaft->mannschaft;
			break;
	}
	echo '</h3>';
	
	if ($posLeague == 'above') echo '<p>Spielklasse: '.$mannschaft->liga.' ('.$mannschaft->ligaKuerzel.')</p>';
	
	$background = false;
	
	echo "\n\t<table class=\"HBschedule HBhighlight\">\n";
	echo "\t\t<thead>\n";
	echo "\t\t<tr><th colspan=\"3\">Wann</th><th>Halle</th><th class=\"rightalign\">Heim</th><th></th><th class=\"leftalign\">Gast</th><th colspan=\"3\">Ergebnis</th>";
	echo "<th> </th>";
	if ($recaps > 0) echo "<th> </th>";
	echo "</tr>\n";
	echo "\t\t</thead>\n\n";
	
	
	echo "\t\t<tbody>\n";
	foreach ($rows as $row)
	{
		// switch color of background
		$background = !$background;
		// check value of background
		switch ($background) {
			case true: $backgroundColor = 'odd'; break;
			case false: $backgroundColor = 'even'; break;
		}
		
		$resultColor = "";
		if (!empty($row->toreHeim) && !empty($row->toreGast))
		{
				
			// check which team is geislingen
			if ($row->heim == $mannschaft->name)
			{
				$goals_geislingen = $row->toreHeim;
				$goals_opponent = $row->toreGast;
			}
			else
			{
				$goals_geislingen = $row->toreGast;
				$goals_opponent = $row->toreHeim;
			}
			if ($goals_geislingen > $goals_opponent)
			{
				$resultColor = " won";
			}
			elseif ($goals_geislingen < $goals_opponent)
			{
				$resultColor = " lost";
			}
			else
			{
				$resultColor = " tied";
			}
		}
	
		// row in HBschedule table
		echo "\t\t\t<tr class=\"{$backgroundColor}{$resultColor}\">";
		echo "<td class=\"wann leftalign\">".strftime('%a', strtotime($row->datum))."</td>";
		echo "<td class=\"wann leftalign\">".strftime('%d.%m.%Y', strtotime($row->datum))."</td>";
		echo "<td class=\"wann leftalign\">".substr($row->uhrzeit,0,5)." Uhr</td>";
		echo "<td><a href=\"LINK ZU HALLE\">{$row->hallenNummer}</a></td>";
		echo "<td class=\"rightalign".markHeimInSpielplan($row->heim, $mannschaft->name)."\">{$row->heim}</td><td>-</td>";
		echo "<td class=\"leftalign".markHeimInSpielplan($row->gast, $mannschaft->name)."\">{$row->gast}</td>";
		if ($row->bemerkung == "abge..")
		{
		echo "<td colspan=\"3\">abgesagt</td></tr>";
		}
			else
			{
			echo "<td class=\"rightalign".markHeimInSpielplan($row->heim, $mannschaft->name)."\">{$row->toreHeim}</td><td>:</td>";
			echo "<td class=\"leftalign".markHeimInSpielplan($row->gast, $mannschaft->name)."\">{$row->toreGast}</td>";
			}
			// result marker
			if (!empty($row->toreHeim) && !empty($row->toreGast)) echo "<td class=\"resultsymbol{$resultColor}\"><img src=\"".JURI::root()."modules/mod_hbschedule/images/".trim($resultColor).".gif\"/></td>";
		else echo "<td></td>";
		// game reports
			if ($recaps > 0) {
			echo "<td>";
			if (!empty($row->berichte)) {
			echo "<a href=\"".strtolower($teamkey)."berichte.php#{$row->SpielNR}\">";
			echo "<img src=\"".JURI::root()."modules/mod_hbschedule/images/page_white_text.png\" title=\"zum Spielbericht\" alt=\"zum Spielbericht\"/>";
				}
			echo "</td>";
			}
			echo "</td></tr>\n";
	}
	echo "\t\t</tbody>\n\n";
	echo "\t</table>\n\n";
	
	if ($posLeague == 'underneath') echo '<p>Spielklasse: '.$mannschaft->liga.' ('.$mannschaft->ligaKuerzel.')</p>';
}
	