<?php
//No access
defined( '_JEXEC' ) or die;
/**
 * Helper class for Hello World! module
 * 
 * @package    Joomla.Tutorials
 * @subpackage Modules
 * @link http://docs.joomla.org/J3.x:Creating_a_simple_module/Developing_a_Basic_Module
 * @license        GNU/GPL, see LICENSE.php
 * mod_helloworld is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
class modHbScheduleHelper
{
    /**
     * Retrieves the hello message
     *
     * @param array $params An object containing the module parameters
     * @access public
     */   
    
    public static function getTeam($teamkey)
    {
        // getting further Information of the team
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->qn('hb_mannschaft'));
        $query->where($db->qn('kuerzel').' = '.$db->q($teamkey));
        $db->setQuery($query);
        $team = $db->loadObject();
		
        //display and convert to HTML when SQL error
        if (is_null($posts=$db->loadRowList())) 
        {
            $jAp->enqueueMessage(nl2br($db->getErrorMsg()),'error');
            return;
        }
		if (empty($team)){
			$team = new stdClass();
			$team->mannschaft = 'Mannschaft';
			$team->liga = 'Liga';
			$team->kuerzel = '';
			$team->nameKurz = '';
		}
        return $team;
    }
    
	private static function getSeason()
    {
		$year = strftime('%Y');
		if (strftime('%m') < 8) {
			$year = $year-1;
		}
		
		$season = $year."-".($year+1);
		//echo __FILE__.'('.__LINE__.'):<pre>';print_r($season);echo'</pre>';
		return $season;
    }
	
    public static function getSchedule( $team )
    {
        //echo __FILE__.__LINE__.'<pre>'; print_r($team); echo '</pre>';
		// getting schedule of the team from the DB
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*, '.
                'IF('.$db->qn('heim').' = '.$db->q($team->nameKurz).',1,0) as heimspiel, '.
                'CASE '.
                'WHEN '.$db->qn('toreHeim').' > '.$db->qn('toreGast').' THEN 1 '.
                'WHEN '.$db->qn('toreHeim').' < '.$db->qn('toreGast').' THEN 2 '.
                'WHEN ('.$db->qn('toreHeim').' = '.$db->qn('toreGast').') AND'.
                    $db->qn('toreHeim').' IS NOT NULL THEN 0 '.
                'ELSE NULL '.
                'END AS ergebnis'
                );
        $query->from($db->qn('hb_spiel'));
        $query->leftJoin($db->qn('hb_spielbericht').' USING ('.$db->qn('spielIdHvw').')');
        $query->where($db->qn('Kuerzel').' = '.$db->q($team->kuerzel));
        $query->where('('.$db->qn('heim').' = '.$db->q($team->nameKurz).' OR '.
                    $db->qn('gast').' = '.$db->q($team->nameKurz).')');		
		$query->where('hb_spiel.'.$db->qn('saison').' = '.$db->q(self::getSeason()));
        $query->order($db->qn('datumZeit'));
        $db->setQuery($query);
        $schedule = $db->loadObjectList();
        //echo "<pre>"; print_r($schedule); echo "</pre>";
        //display and convert to HTML when SQL error
        if (is_null($posts=$db->loadRowList())) 
        {
                $jAp->enqueueMessage(nl2br($db->getErrorMsg()),'error');
                return;
        }
        $schedule = self::addBackground($schedule);
        $schedule = self::addResult($schedule);
		$schedule = self::addHighlightNextGame($schedule);
        return $schedule;
    }
    
    public static function getReportNr($team)
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('COUNT(*)');
        $query->from($db->qn('hb_spielbericht'));
        $query->innerJoin($db->qn('hb_spiel').' USING ('.$db->qn('spielIdHvw').')');
        $query->where($db->qn('Kuerzel').' = '.$db->q($team->kuerzel));
        $db->setQuery($query);
        $recaps = $db->loadResult();
        //echo "<p>recaps</p><pre>"; print_r($recaps); echo "</pre>";
    }
    
    
    protected static function addBackground ($schedule)
    {
        $background = false;
        foreach ($schedule as $row)
		{
            // switch color of background
            $background = !$background;
            // check value of background
            switch ($background) 
            {
                case true: 
                    $row->background = 'odd'; 
                    break;
                case false: 
                    $row->background = 'even'; 
                    break;
            }
        }
        return $schedule;
    }
	
	protected static function addHighlightNextGame ($schedule)
    {
        $highlighted = false;
        foreach ($schedule as $row)
		{
            if (time() <= strtotime($row->datumZeit) && !$highlighted) {
                $row->highlight = true; 
				$highlighted = true;
            } else {
				$row->highlight = false; 
			}
        }
        return $schedule;
    }
    
    protected static function addResult ($schedule)
    {
        foreach ($schedule as $row)
	{
            if (($row->heimspiel && $row->ergebnis == 1) ||
                    (!$row->heimspiel && $row->ergebnis == 2)) {
                $row->ampel = " win";
            }
            elseif (($row->heimspiel && $row->ergebnis == 2)||
                    (!$row->heimspiel && $row->ergebnis == 1)) {
                $row->ampel = " loss";
            }
            elseif ($row->ergebnis == 0) {
                $row->ampel = " tie";
            }
            else {
                $row->ampel = "";
            }
        }
        return $schedule;
    }
    
	public static function getHeadline ($option, $team)
    {
        switch ($option)
		{
			case 'title':
				$headline = JText::_('MOD_HBSCHEDULE_SCHEDULE');
				break;
			case 'not':
				$headline = '';
				break;
			case 'titleandteam':
			default:
				$headline = JText::_('MOD_HBSCHEDULE_SCHEDULE').' - '.
					$team->mannschaft;
				break;
		}
        return $headline;
    }
}














