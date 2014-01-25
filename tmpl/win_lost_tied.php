<?php 
defined( '_JEXEC' ) or die( 'Restricted access' );


$document =& JFactory::getDocument();
$document->addStyleSheet(JURI::base() . 'modules/mod_hbschedule/css/hbschedule.css');
$document->addStyleSheet(JURI::base() . 'modules/mod_hbschedule/css/default.css');


if ($scheduleTableExists)
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
			echo 'Spielplan - '.$team->team;
			break;
	}
	echo '</h3>';
	
	if ($posLeague == 'above') echo '<p>Spielklasse: '.$team->completeLeague.' ('.$team->league.')</p>';
	
	$background = false;
	
	echo "\n\t<table class=\"HBschedule HBhighlight\">\n";
	echo "\t\t<thead>\n";
	echo "\t\t<tr><th colspan=\"3\">Wann</th><th>Halle</th><th>Heim</th><th></th><th>Gast</th><th colspan=\"3\">Ergebnis</th>";
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
		// format date
		$date = strftime('%d.%m.%Y', strtotime($row->Datum));
	
		$resultColor = "";
		if (!empty($row->ToreHeim) && !empty($row->ToreGast))
		{
				
			// check which team is geislingen
			if (in_array(trim($row->Heim), $teamNames))
			{
				$goals_geislingen = $row->ToreHeim;
				$goals_opponent = $row->ToreGast;
			}
			else
			{
				$goals_geislingen = $row->ToreGast;
				$goals_opponent = $row->ToreHeim;
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
		echo "<td>{$row->Tag}</td><td>{$date}</td><td>".substr($row->Zeit,0,5)." Uhr</td>";
		echo "<td><a href=\"LINK ZU HALLE\">{$row->Halle}</a></td>";
		echo "<td".markHomeInSchedule($row->Heim, $teamNames, true).">{$row->Heim}</td><td>-</td>";
		echo "<td".markHomeInSchedule($row->Gast, $teamNames, true).">{$row->Gast}</td>";
		if ($row->Bemerkung == "abge..")
		{
		echo "<td colspan=\"3\">abgesagt</td></tr>";
		}
			else
			{
			echo "<td".markHomeInSchedule($row->Heim, $teamNames, true).">{$row->ToreHeim}</td><td>:</td>";
			echo "<td".markHomeInSchedule($row->Gast, $teamNames, true).">{$row->ToreGast}</td>";
			}
			// result marker
			if (!empty($row->ToreHeim) && !empty($row->ToreGast)) echo "<td class=\"resultsymbol{$resultColor}\"><img src=\"".JURI::root()."modules/mod_hbschedule/images/".trim($resultColor).".gif\"/></td>";
		else echo "<td></td>";
		// game reports
			if ($recaps > 0) {
			echo "<td>";
			if (!empty($row->Berichte)) {
			echo "<a href=\"".strtolower($teamkey)."berichte.php#{$row->SpielNR}\">";
			echo "<img src=\"".JURI::root()."modules/mod_hbschedule/images/page_white_text.png\" title=\"zum Spielbericht\" alt=\"zum Spielbericht\"/>";
				}
			echo "</td>";
			}
			echo "</td></tr>\n";
	}
	echo "\t\t</tbody>\n\n";
	echo "\t</table>\n\n";
	
	if ($posLeague == 'underneath') echo '<p>Spielklasse: '.$team->completeLeague.' ('.$team->league.')</p>';	// color the line of result
}
	