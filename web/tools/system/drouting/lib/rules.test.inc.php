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

  $groupid = $_POST['groupid'];
  $prefix = $_POST['prefix'];
  $time_recurrence = $_POST['time_recurrence'];
  $dtstart_day = $_POST['dtstart_day'];
  $dtstart_month = $_POST['dtstart_month'];
  $dtstart_year = $_POST['dtstart_year'];
  $dtstart_hour = $_POST['dtstart_hour'];
  $dtstart_minute = $_POST['dtstart_minute'];
  $dtstart_second = $_POST['dtstart_second'];
  $duration = $_POST['duration'];
  $duration_weeks = $_POST['duration_weeks'];
  $duration_days = $_POST['duration_days'];
  $duration_hours = $_POST['duration_hours'];
  $duration_minutes = $_POST['duration_minutes'];
  $duration_seconds = $_POST['duration_seconds'];
  $frequency = $_POST['frequency'];
  $daily_interval = $_POST['daily_interval'];
  $weekly_interval = $_POST['weekly_interval'];
  $weekly_byday = $_POST['weekly_byday'];
  $monthly_interval = $_POST['monthly_interval'];
  $monthly_bymonthday = $_POST['monthly_bymonthday'];
  $monthly_byday = $_POST['monthly_byday'];
  $yearly_interval = $_POST['yearly_interval'];
  $yearly_byday = $_POST['yearly_byday'];
  $yearly_byweekno = $_POST['yearly_byweekno'];
  $yearly_bymonthday = $_POST['yearly_bymonthday'];
  $yearly_byyearday = $_POST['yearly_byyearday'];
  $yearly_bymonth = $_POST['yearly_bymonth'];
  $bound = $_POST['bound'];
  $until_year = $_POST['until_year'];
  $until_month = $_POST['until_month'];
  $until_day = $_POST['until_day'];
  $until_hour = $_POST['until_hour'];
  $until_minute = $_POST['until_minute'];
  $until_second = $_POST['until_second'];
  $priority = $_POST['priority'];
  $routeid = $_POST['routeid'];
  $gwlist = $_POST['gwlist'];
  $gwlist_value = $_POST['gwlist_value'];
  $gw_weight = $_POST['gw_weight'];
  $car_weight = $_POST['car_weight'];
  $list_sort = $_POST['list_sort'];
  $description = $_POST['description'];

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
