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

function get_alias_types()
{
        global $config;
        require("../../../../config/globals.php");
	$aliases = array();
        foreach (get_settings_values("table_aliases") as $key=>$value)
                $aliases[]=array("label"=>$key,"value"=>$value);
	return $aliases;
}

function print_aliasType($value)
{
        $domain = $value;
	$options = get_alias_types();
        $start_index = 0;
        $end_index = sizeof($options);
?>
        <select name="alias_type" id="alias_type" size="1" style="width: 205px" class="dataSelect">
         <?php
           if ($value!=NULL) {
             echo('<option value="'.$value. '" selected > '.$value.'</option>');
             $temp = $value;
             $value = 0;
           }
          for ($i=$start_index;$i<$end_index;$i++)
          {
           if ($options[$i]['value'] == $temp) {
                continue;
           } else {
             echo('<option value="'.$options[$i]['label']. '"> '.$options[$i]['label'].'</option>');
           }
          }
         ?>
         </select>
        <?php

}

function print_domains($type,$value,$has_any)
{
	require("../../../../web/tools/system/domains/lib/functions.inc.php");
	$domains = get_domains("user_management", $has_any);

	$start_index = 0;
	$temp = '';
	$end_index = sizeof($domains);

	echo('<select name='.$type.' id='.$type.' size="1" style="width: 205px" class="dataSelect">');
	if ($value!=NULL && $value!="") {
		echo('<option value="'.$value. '" selected > '.$value.'</option>');
		$temp = $value;
		$value = '';
	}
	for ($i=$start_index;$i<$end_index;$i++) {
		if ($domains[$i] == $temp) {
			continue;
		} else {
			echo('<option value="'.$domains[$i]. '"> '.$domains[$i].'</option>');
		}
	}
	echo('</select>');
}

function get_total_users() {
	session_load_from_tool("user_management");
        require_once(__DIR__."/db_connect.php");
	$users_table=get_settings_value_from_tool("table_users", "user_management");
        $sql = "select count(*) as no from ".$users_table;
        $stm = $link->prepare($sql);
        $stm->execute();
        $row = $stm->fetchAll(PDO::FETCH_ASSOC);
        return $row[0]['no'];
}

function secs2hms($secs) {
	if ($secs<0) return false;
	$m = (int)($secs / 60); $s = $secs % 60;
	$h = (int)($m / 60); $m = $m % 60;
	
	$time = "";


	if ($h>0){
		$hh="";
		if ($h<10)
			$hh.="0".$h;
		else 
			$hh.=$h;
		$time.$hh.":";
	}
	

	if ($m>0){
		$mm="";
		if ($m<10)
			$mm.="0".$m;
		else
		    $mm.=$m;
	    $time.=$mm.":";
	}

	if ($h>0 && $m==0)
		$time.="00:";

	if ($s>=0){
        $ss="";
        if ($s<10 && $m>0)
	        $ss.="0".$s;
	    else
		    $ss.=$s;
		$time.=$ss;
	}
	return $time;
}

?>
