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



function fmt_binary($x, $numbits, $retbit) {
        // Convert to binary
        $bin = decbin($x);
        $bin = substr(str_repeat(0,$numbits),0,$numbits - strlen($bin)) . $bin;
        // Split into x 4-bits long
        $rtnval = '';
        for ($x = 0; $x < $numbits/4; $x++) {
            $rtnval .= ' ' . substr($bin,$x*4,4);
        }
    	// Get rid of first space.
	    return ltrim($rtnval[$retbit]);
} 


function get_gwlist()
{
//include("db_connect.php");
 global $link;
 global $config;
 $index = 0;
 $values = array();
 $sql="select * from ".get_settings_value("table_gateways")." order by gwid asc";
 $stm = $link->prepare($sql);
 if ($stm===FALSE) {
	die('Failed to issue query ['.$sql.'], error message : ' . $link->errorInfo()[2]);
 }
 $stm->execute( array() );
 $resultset = $stm->fetchAll(PDO::FETCH_ASSOC);
 for($i=0;count($resultset)>$i;$i++)
 {
  $values[$index][0] = $resultset[$i]['gwid'];
  $values[$index][1] = $resultset[$i]['address'];
  $values[$index][2] = $resultset[$i]['description'];
  $index++;
 }
 return($values);
}

function print_gwlist()
{
 $array_values = get_gwlist();
 $start_index = 0;
 $end_index = sizeof($array_values);
?>
 <select name="gwlist_value" id="gwlist_value" size="1" class="dataSelect" style="width:326!important; margin-left:1px;margin-top:2px;">
 <?php
  for ($i=$start_index;$i<$end_index;$i++){
   if (strlen($array_values[$i][2]) < 25) 
   	$desc = $array_values[$i][2];
   else 
   	$desc = substr($array_values[$i][2], 0, 25) . "...";
   echo('<option value="'.$array_values[$i][0].'"> (#'.$array_values[$i][0].') '.$array_values[$i][1].' / '.$desc.'</option>');
  }
 ?>
 </select>
<?php
}

function print_object($obj_name, $start_value, $end_value, $select_value)
{
?>
 <select name="<?=$obj_name?>" id="<?=$obj_name?>" size="1" class="dataSelect">
 <?php
  for ($i=$start_value;$i<=$end_value;$i++)
  {
   if ($i<10) $value="0".$i;
    else $value=$i;
   if ($value==$select_value) $selected="selected";
    else $selected="";
   echo('<option value="'.$value.'" '.$selected.'>'.$value.'</option>');
  }
 ?>
 </select>
<?php
}

function print_date_time($obj_name)
{
 print_object($obj_name."_day",1,31,date("d")); echo("<b>/</b>");
 print_object($obj_name."_month",1,12,date("m")); echo("<b>/</b>");
 print_object($obj_name."_year",date("Y"),date("Y")+25,date("Y")); echo("&nbsp;&nbsp;");
 print_object($obj_name."_hour",0,23,0); echo("<b>:</b>");
 print_object($obj_name."_minute",0,59,0); echo("<b>:</b>");
 print_object($obj_name."_second",0,59,0);
}

function print_interval($obj_name)
{
 print_object($obj_name."_weeks",0,53,0); echo(" Weeks");
 print_object($obj_name."_days",0,6,0); echo(" Days &nbsp;<b>/</b>&nbsp;&nbsp;");
 print_object($obj_name."_hours",0,24,1); echo("<b>:</b>");
 print_object($obj_name."_minutes",0,59,0); echo("<b>:</b>");
 print_object($obj_name."_seconds",0,59,0);
}

function parse_timerec_main($time_string)
{
 if ($time_string!="") echo("Custom Time Recurrence.");
  else echo("No Time Recurrence.");
}

function parse_timerec($time_string, $page_id)
{
 
 if ($time_string=="") {
                        echo("Not Defined");
                        return;
                       }
 
 if ($page_id==1) echo("<br>");
 $buffer=explode("|",$time_string);
 
 if (sizeof($buffer)>0) $dtstart=$buffer[0];
  else $dtstart=null;
 if (sizeof($buffer)>1) $duration=$buffer[1];
  else $duration=null;
 if (sizeof($buffer)>2) $freq=$buffer[2];
  else $freq=null;
 if (sizeof($buffer)>3) $until=$buffer[3];
  else $until=null;
 if (sizeof($buffer)>4) $interval=$buffer[4];
  else $interval=null;
 if (sizeof($buffer)>5) $byday=$buffer[5];
  else $byday=null;
 if (sizeof($buffer)>6) $bymonthday=$buffer[6];
  else $bymonthday=null;
 if (sizeof($buffer)>7) $byyearday=$buffer[7];
  else $byyearday=null;
 if (sizeof($buffer)>8) $byweekno=$buffer[8];
  else $byweekno=null;
 if (sizeof($buffer)>9) $bymonth=$buffer[9];
  else $bymonth=null;
  
 echo("<b>&nbsp;&middot;&nbsp;</b>Start of interval: ".substr($dtstart,9,2).":".substr($dtstart,11,2).":".substr($dtstart,13,2)." ".substr($dtstart,6,2)."/".substr($dtstart,4,2)."/".substr($dtstart,0,4)."<br>");
 
 if (!$duration) {
                  echo("<b>&nbsp;&middot;&nbsp;</b>Duration of interval: Forever<br>");
                  if ($until) echo("<b>&nbsp;&middot;&nbsp;</b>Bound of recurrence: ".substr($until,9,2).":".substr($until,11,2).":".substr($until,13,2)." ".substr($until,6,2)."/".substr($until,4,2)."/".substr($until,0,4)."<br>");
                   else echo("<b>&nbsp;&middot;&nbsp;</b>Bound of recurrence: None<br>");
                  return;
                 }
 else {
       $values=array();
       $k=0;
       for($i=0;$i<strlen($duration);$i++)
       {
        $char=$duration[$i];
        if (($char!="P") && ($char!="T")) {
                                           if (is_numeric($char)) $values[$k][0].=$char;
                                            else {
                                                  $values[$k][1]=$char;
                                                  $k++;
                                                 }
                                          }
       }
       echo("<b>&nbsp;&middot;&nbsp;</b>Duration of interval: ");
       $duration_text="";
       for($i=0;$i<$k;$i++)
       {
        if ($duration_text=="") $duration_text=(intval($values[$i][0])." ");
         else $duration_text.=", ".(intval($values[$i][0])." ");
        if (($values[$i][1]=="W") && ($values[$i][0]==1)) $duration_text.="Week";
        if (($values[$i][1]=="W") && ($values[$i][0]>1)) $duration_text.="Weeks";
        if (($values[$i][1]=="D") && ($values[$i][0]==1)) $duration_text.="Day";
        if (($values[$i][1]=="D") && ($values[$i][0]>1)) $duration_text.="Days";
        if (($values[$i][1]=="H") && ($values[$i][0]==1)) $duration_text.="Hour";
        if (($values[$i][1]=="H") && ($values[$i][0]>1)) $duration_text.="Hours";
        if (($values[$i][1]=="M") && ($values[$i][0]==1)) $duration_text.="Minute";
        if (($values[$i][1]=="M") && ($values[$i][0]>1)) $duration_text.="Minutes";
        if (($values[$i][1]=="S") && ($values[$i][0]==1)) $duration_text.="Second";
        if (($values[$i][1]=="S") && ($values[$i][0]>1)) $duration_text.="Seconds";
       }
       echo($duration_text."<br>");
      }
 
 if ($freq=="daily")
 {
  if ($interval==1) $interval_text="Every day<br>";
   else $interval_text="Every ".$interval." days.<br>";
 }
 
 if ($freq=="weekly")
 {
  if ($interval==1) $interval_text="Every week<br>";
   else $interval_text="Every ".$interval." weeks.<br>";
  if ($byday) $interval_text.="&nbsp;&nbsp;&nbsp;- By Day : ".$byday."<br>";
 }
 
 if ($freq=="monthly")
 {
  if ($interval==1) $interval_text="Every month<br>";
   else $interval_text="Every ".$interval." months.<br>";
  if ($byday) $interval_text.="&nbsp;&nbsp;&nbsp;- By Day : ".$byday."<br>";
  if ($bymonthday) $interval_text.="&nbsp;&nbsp;&nbsp;- By Month Day : ".$bymonthday."<br>";
 }
 
 if ($freq=="yearly")
 {
  if ($interval==1) $interval_text="Every year<br>";
   else $interval_text="Every ".$interval." years.<br>";
  if ($byday) $interval_text.="&nbsp;&nbsp;&nbsp;- By Day : ".$byday."<br>";
  if ($bymonthday) $interval_text.="&nbsp;&nbsp;&nbsp;- By Month Day : ".$bymonthday."<br>";
  if ($byyearday) $interval_text.="&nbsp;&nbsp;&nbsp;- By Year Day : ".$byyearday."<br>";
  if ($byweekno) $interval_text.="&nbsp;&nbsp;&nbsp;- By Week No : ".$byweekno."<br>";
  if ($bymonth) $interval_text.="&nbsp;&nbsp;&nbsp;- By Month : ".$bymonth."<br>";
 }
 
 echo("<b>&nbsp;&middot;&nbsp;</b>Interval: ".$interval_text);

 if ($until) echo("<b>&nbsp;&middot;&nbsp;</b>Bound of recurrence: ".substr($until,9,2).":".substr($until,11,2).":".substr($until,13,2)." ".substr($until,6,2)."/".substr($until,4,2)."/".substr($until,0,4)."<br>");
  else echo("<b>&nbsp;&middot;&nbsp;</b>Bound of recurrence: None<br>");
}

function parse_gwlist($gwlist_string)
{
 $string=str_replace("|",",",$gwlist_string);
 $string=str_replace(";",",",$string);
 $buffer=explode(",",$string);
 $string_return="";
 for($i=0;$i<sizeof($buffer);$i++)
 {
  $temp = explode ("=",$buffer[$i]);
  $gatewayid = $temp[0];
  $string_return.=' <a href="gateways.php?action=details&gwid='.$gatewayid.'" class="gwList">'.$buffer[$i].'</a>';
  $len_val=strlen($buffer[$i]);
  $gwlist_string=substr($gwlist_string,$len_val,strlen($gwlist_string));
  $string_return.=substr($gwlist_string,0,1);
  $gwlist_string=substr($gwlist_string,1,strlen($gwlist_string));
 }
 return($string_return);
}

function parse_list($gwlist_string)
{
 $string = 0+ substr($gwlist_string,-1);
 $string_return=' <a href="lists.php?action=details&id='.$string.'" class="gwList">'.$string.'</a>';
 return($string_return);
 }



?>
