<?php
/*
 * Copyright (C) 2011 OpenSIPS Project
 *
 * This file is part of opensips-cp, a free Web Control Panel Application for 
 * OpenSIPS SIP server.
 *
 * opensips-cp is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * opensips-cp is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

  extract($_POST);
if (!empty($lists)) $gwlist=$lists;
  $form_valid=true;
  if ($form_valid)
   if ($groupid=="") {
                      $form_valid=false;
                      $form_error="- invalid <b>Group ID</b> field -";
                     }
  if ($form_valid)
   if ($priority=="") {
                       $form_valid=false;
                       $form_error="- invalid <b>Priority</b> field -";
                      }
  if ($form_valid)
   if (!is_numeric($priority)) {
                                $form_valid=false;
                                $form_error="- <b>Priority</b> field must be numeric -";
                               }
  if ($form_valid)
   if ($priority<0) {
                     $form_valid=false;
                     $form_error="- <b>Priority</b> field must be a positive number -";
                    }
  if ($form_valid)
   if (strpos($routeid," ")!== false) {
                      $form_valid=false;
                      $form_error="- invalid <b>Route ID</b> field -";
                     }
  if ($form_valid)
   if ($routeid<0) {
                    $form_valid=false;
                    $form_error="- <b>Route ID</b> field must be a positive number -";
                   }
                     
   if (isset($frequency)){
   
   if ($frequency=="daily") {
   	
   		$interval = $daily_interval ;
   
   }

   
   if ($frequency=="weekly") {
   	
   		$interval = $weekly_interval ;
   		$byday = $weekly_byday ;	
   
   }
   
      
   if ($frequency=="monthly") {
   	
   		$interval = $monthly_interval ;
   		$bymonthday = $monthly_bymonthday ;
   		$byday = $monthly_byday ;
   }
   

    if ($frequency=="yearly") {
   	
   		$interval = $yearly_interval ;
   		$byday = $yearly_byday ;
   		$byweekno = $yearly_byweekno ;
   		$bymonthday = $yearly_bymonthday ;
   		$byyearday = $yearly_byyearday ;
    	$bymonth = $yearly_bymonth ;
    
    }
   
   }
   
   
  if ($form_valid) {
                    // make $groupid
                    if (substr($groupid,strlen($groupid)-1,1)==",") $groupid=substr($groupid,0,strlen($groupid)-1);
                    $groupid_array=explode(",",$groupid);
                    sort($groupid_array);
                    $groupid="";
                    for($i=0;$i<sizeof($groupid_array);$i++)
                     if ($groupid=="") $groupid=$groupid_array[$i];
                      else $groupid.=",".$groupid_array[$i];
                    // make $gwlist
                    if (substr($gwlist,strlen($gwlist)-1,1)==";") $gwlist=substr($gwlist,0,strlen($gwlist)-1);
                    if (substr($gwlist,strlen($gwlist)-1,1)==",") $gwlist=substr($gwlist,0,strlen($gwlist)-1);
                    
                    // make $timerec
                    if ($time_recurrence==0) $timerec="";
                    else
                    
                    {
                     
                    		$timerec=$dtstart_year.$dtstart_month.$dtstart_day."T".$dtstart_hour.$dtstart_minute.$dtstart_second;
                     
                    		if ($duration==0) {
                     		
                     			$timerec.="|".$duration ; 
                     			if ($bound!=0) $timerec.="||".$until_year.$until_month.$until_day."T".$until_hour.$until_minute.$until_second;
                     
                    		}
                     
                      else {

                   
                		   $timerec.="|P";
                            
							if (($duration_weeks!=0) || ($duration_days!=0))
                            {
                    
                             if ($duration_weeks!=0) $timerec.=intval($duration_weeks)."W";
                             if ($duration_days!=0) $timerec.=intval($duration_days)."D";
                            }
                            if (($duration_hours!=0) || ($duration_minutes!=0) || ($duration_seconds!=0))
                            {
                             $timerec.="T";
                             if ($duration_hours!=0) $timerec.=intval($duration_hours)."H";
                             if ($duration_minutes!=0) $timerec.=intval($duration_minutes)."M";
                             if ($duration_seconds!=0) $timerec.=intval($duration_seconds)."S";
                            }
                            $timerec.="|".$frequency;
                            if ($bound==0) $timerec.="|";
                             else $timerec.="|".$until_year.$until_month.$until_day."T".$until_hour.$until_minute.$until_second;
                            $timerec.="|".$interval;
                            if ($byday=="") $timerec.="|";
                             else $timerec.="|".$byday;
                            if ($bymonthday=="") $timerec.="|";
                             else $timerec.="|".$bymonthday;
                            if ($byyearday=="") $timerec.="|";
                             else $timerec.="|".$byyearday;
                            if ($byweekno=="") $timerec.="|";
                             else $timerec.="|".$byweekno;
                            if ($bymonth=="") $timerec.="|";
                             else $timerec.="|".$bymonth;
                           }
                     
                    }
                    $sql="select * from ".$table." where groupid=? and prefix=? and timerec=? and priority=? and routeid=? and gwlist=?";
		    $stm=$link->prepare($sql);
		    if ($stm === false) {
		    	die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
		    }
		    $stm->execute( array($groupid,$prefix,$timerec,$priority,$routeid,$gwlist) );
		    $result = $stm->fetchAll(PDO::FETCH_ASSOC);
                    $data_rows=count($result);
                    if (($data_rows>0) && ($result[0]['ruleid']!=$_GET['id']))
                    {
                     $form_valid=false;
                     $form_error="- this is already a valid rule -";
                    }
                   }

?>
