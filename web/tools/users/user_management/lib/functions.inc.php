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


function print_aliasType($value)
{

        global $config;
        $domain = $value;
        require("../../../../config/globals.php");
        foreach ($config->table_aliases as $key=>$value) {
                $options[]=array("label"=>$key,"value"=>$value);
        }
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

        global $config;

        require("../../../../config/tools/system/domains/local.inc.php");
        require("../../../../config/db.inc.php");
        require("../../../../config/tools/system/domains/db.inc.php");
        require("db_connect.php");

        $table_domains=$config->table_domains;

        $sql="select domain from $table_domains";
        $result = $link->queryAll($sql);
        if(PEAR::isError($result)) {
                die('Failed to issue query, error message : ' . $result->getMessage());
        }

	if ($has_any)
	        $options[]=array("label"=>"ANY","value"=>"ANY");
        foreach ($result as $k=>$v) {
                $options[]=array("label"=>$v['domain'],"value"=>$v['domain']);
        }

        $start_index = 0;
        $end_index = sizeof($options);

		echo('<select name='.$type.' id='.$type.' size="1" style="width: 205px" class="dataSelect">');
		if ($value!=NULL && $value!="") {
			echo('<option value="'.$value. '" selected > '.$value.'</option>');
			$temp = $value;
			$value = '';
		}
		for ($i=$start_index;$i<$end_index;$i++) {
			if ($options[$i]['value'] == $temp) {
				continue;
			} else {
				echo('<option value="'.$options[$i]['value']. '"> '.$options[$i]['value'].'</option>');
			}
		}
		echo('</select>');
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
