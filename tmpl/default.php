<?php 
defined( '_JEXEC' ) or die( 'Restricted access' );



JHtml::stylesheet('mod_hbpartnerclub/default.css', array(), true);


//echo __FILE__.'('.__LINE__.'):<pre>';print_r($schedule);echo'</pre>';

if (count($schedule)>0)
{
	//echo "<p>".JText::_('DESC_MODULE')."</p>";
	
	echo (!empty($headline)) ?  '<h3>'.$headline.'</h3>'."\n\n" : '';
	
	if ($posLeague == 'above') {
		echo '<p>'.JText::_('MOD_HBSCHEDULE_LEAGUE').': '.
				$team->liga.' ('.$team->ligaKuerzel.')</p>';
	} 
	?>
	<table>
		<thead>
			<tr>
				<th colspan="3"><?php echo JText::_('MOD_HBSCHEDULE_WHEN')?></th>
				<th><?php echo JText::_('MOD_HBSCHEDULE_GYM')?></th>
                <th><?php echo JText::_('MOD_HBSCHEDULE_HOMETEAM')?></th>
				<th></th>
                <th><?php echo JText::_('MOD_HBSCHEDULE_AWAYTEAM')?></th>
				<th colspan="<?php echo $indicator ? 4 : 3;?>"><?php echo JText::_('MOD_HBSCHEDULE_RESULT')?></th>
				<?php echo $reports ? '<th> </th>' : '';?>
			</tr>
		</thead>
		
	
		<tbody>
	<?php
	// highlight next game flag
	$highlight = false;
	foreach ($schedule as $row)
	{
		?>
			<tr<?php echo $row->highlight ? ' class= "highlighted"' : '';?>>
				<td><?php echo JHtml::_('date', $row->datum, 'D', $timezone);?></td>
				<td><?php echo JHtml::_('date', $row->datum, 'd.m.y', $timezone);?></td>
				<td><?php echo JHtml::_('date', $row->uhrzeit, 'H:i', $timezone);
					echo ' '.JText::_('MOD_HBSCHEDULE_TIMEUNIT');?></td>
				<td><a href="./index.php/hallen#<?php echo $row->hallenNr?>"><?php echo $row->hallenNr?></a></td>
				<td class="<?php echo $row->heimspiel ? ' ownTeam' : '';?>"><?php echo $row->heim;?></td>
				<td>-</td>
				<td class="<?php echo !$row->heimspiel ? ' ownTeam' : '';?>"><?php echo $row->gast;?></td>
                <?php
				if ($row->bemerkung == "abge..") {
					echo '<td colspan="3">'.JText::_('MOD_HBSCHEDULE_CANCELED').'</td></tr>';
				} else {
					?>
				<td class="<?php echo $row->heimspiel ? ' ownTeam' : '';?>"><?php echo $row->toreHeim;?></td>
				<td>:</td>
				<td class="<?php echo !$row->heimspiel ? ' ownTeam' : '';?>"><?php echo $row->toreGast;?></td>
				<?php
				}
				if ($indicator)	
				{
					echo '<td>';
					if (!empty($row->toreHeim) && !empty($row->toreGast)){
						echo '<span class="'.$row->ampel.'"></span>';
					}
					echo '</td>';
				}
				// game reports
				if ($reports) 
				{
					echo "<td>";
					if (!empty($row->berichte)) {
						echo "<a href=\"".strtolower($teamkey).
								"berichte.php#{$row->spielIdHvw}\">";
						echo '<img src="'.JURI::root()
							. 'modules/mod_hbschedule/images/page_white_text.png"'
							. ' title="'.JText::_('MOD_HBSCHEDULE_TO_REPORT').'" alt="'.JText::_('MOD_HBSCHEDULE_TO_REPORT').'"/>';
					}
					echo "</td>";
				}
				?>
			</tr>
		<?php
	}
	?>
		</tbody>
	</table>
<?php
	
	if ($posLeague == 'underneath') {
		echo '<p>'.JText::_('MOD_HBSCHEDULE_LEAGUE').': '.
				$team->liga.' ('.$team->ligaKuerzel.')</p>';
	}
}
	