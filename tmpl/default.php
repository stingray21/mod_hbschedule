<?php 
defined( '_JEXEC' ) or die( 'Restricted access' );


$document =& JFactory::getDocument();
$document->addStyleSheet(JURI::base() . 'modules/mod_hbschedule/css/hbschedule.css');
$document->addStyleSheet(JURI::base() . 'modules/mod_hbschedule/css/default.css');

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
	echo "\t\t<tr><th colspan=\"3\">Wann</th><th>Halle</th><th>Heim</th><th></th><th>Gast</th><th colspan=\"3\">Ergebnis</th>";
	if ($recaps > 0) echo "<th> </th>";
	echo "</tr>\n";
	echo "\t\t</thead>\n\n";
	
	
	echo "\t\t<tbody>\n";
	foreach ($rows as $row) {
		// switch color of background
		$background = !$background;
		// check value of background
		switch ($background) {
			case true: $backgroundColor = 'odd'; break;
			case false: $backgroundColor = 'even'; break;
			}
		// format date
		$date = strftime('%d.%m.%Y', strtotime($row->datum));
		
		
		// row in HBschedule table
		echo "\t\t\t<tr class=\"{$backgroundColor}\">";
		echo "<td>{$row->Tag}{$resultColor}</td><td>{$date}</td><td>".substr($row->Zeit,0,5)." Uhr</td>";
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
	
	if ($posLeague == 'underneath') echo '<p>Spielklasse: '.$mannschaft->liga.' ('.$mannschaft->ligaKuerzel.')</p>';
	
}