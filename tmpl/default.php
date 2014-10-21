<?php 
defined( '_JEXEC' ) or die( 'Restricted access' );

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base().'modules/mod_hbschedule/css/hbschedule.css');
$document->addStyleSheet(JURI::base().'modules/mod_hbschedule/css/default.css');

//echo "<pre>"; print_r($rows); echo "</pre>";

if (count($schedule)>0)
{
	//echo "<p>".JText::_('DESC_MODULE')."</p>";
	
	// Headline
	if (!empty($headline))
        {
            echo '<h3>'.$headline.'</h3>';
        }
	
	if ($posLeague == 'above') {
            echo '<p>'.JText::_('MOD_HBSCHEDULE_LEAGUE').': '.
                    $team->liga.' ('.$team->ligaKuerzel.')</p>';
        }
        
	
	
	echo "\n\t<table class=\"HBschedule HBhighlight\">\n";
	echo "\t\t<thead>\n";
	echo "\t\t<tr><th colspan=\"3\">Wann</th><th>Halle</th>"
                . "<th class=\"rightalign\">Heim</th><th></th>"
                . "<th class=\"leftalign\">Gast</th>"
                . "<th colspan=\"3\">Ergebnis</th>";
	echo "<th> </th>";
	//if ($recaps > 0) echo "<th> </th>";
	echo "</tr>\n";
	echo "\t\t</thead>\n\n";
	
	
	echo "\t\t<tbody>\n";
	foreach ($schedule as $row)
	{
		
		
	
		// row in HBschedule table
		echo "\t\t\t<tr class=\"{$row->background}{$row->ampel}\">";
		echo "<td class=\"wann leftalign\">";
		echo JHtml::_('date', $row->datum, 'D', false);
		echo "</td>";
		echo "<td class=\"wann leftalign\">";
		echo JHtml::_('date', $row->datum, 'd.m.y', false);
		echo "</td>";
		echo "<td class=\"wann leftalign\">".substr($row->uhrzeit,0,5)." Uhr</td>";
		echo "<td><a href=\"LINK ZU HALLE\">{$row->hallenNr}</a></td>";
		echo "<td class=\"rightalign";
                if ($row->heimspiel) echo ' heim';
                echo "\">{$row->heim}</td><td>-</td>";
		echo "<td class=\"leftalign";
                if (!$row->heimspiel) echo ' heim';
                echo "\">{$row->gast}</td>";
		if ($row->bemerkung == "abge..")
		{
		echo "<td colspan=\"3\">abgesagt</td></tr>";
		}
			else
			{
			echo "<td class=\"rightalign".
                                //markHeimInSpielplan($row->heim, $team->name).
                                "\">{$row->toreHeim}</td><td>:</td>";
			echo "<td class=\"leftalign".
                                //markHeimInSpielplan($row->gast, $team->name).
                                "\">{$row->toreGast}</td>";
			}
			// result marker
			if (!empty($row->toreHeim) && !empty($row->toreGast)) echo "<td class=\"resultsymbol{$row->ampel}\"><img src=\"".JURI::root()."modules/mod_hbschedule/images/".trim($row->ampel).".gif\"/></td>";
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
	
	if ($posLeague == 'underneath') echo '<p>Spielklasse: '.$team->liga.' ('.$team->ligaKuerzel.')</p>';
}
	