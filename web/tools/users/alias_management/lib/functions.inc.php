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


function print_domains($type,$value,$has_any)
{

	global $config;

	require("../../../../config/db.inc.php");
        require("../../../../config/tools/system/domains/db.inc.php");
        session_load_from_tool("domains");
        require("db_connect.php");

        $table_domains=get_settings_value_from_tool("table_domains", "domains");

        $sql="select domain from ".$table_domains;
        $stm= $link->query($sql);
	if ($stm === FALSE)
		die('Failed to issue query, error message : ' . print_r($link->errorInfo(), true));
	$result = $stm->fetchAll(PDO::FETCH_ASSOC);

	if ($has_any)
	        $options[]=array("label"=>"ANY","value"=>"ANY");
        foreach ($result as $k=>$v) {
                $options[]=array("label"=>$v['domain'],"value"=>$v['domain']);
        }

        $start_index = 0;
	$temp ='';
        $end_index = sizeof($options);

		echo('<select ');
		if (isset($_SESSION['fromusrmgmt']) && ($_SESSION['fromusrmgmt'])) echo "disabled ";
		echo('name='.$type.' id='.$type.' size="1" style="width: 205px" class="dataSelect">');
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

function print_aliasType($value, $has_any)
{
        global $config;
	require("../../../../config/globals.php");
	if ($has_any)
	        $options[]=array("label"=>"ANY","value"=>"ANY");
        foreach (get_settings_value("table_aliases") as $k=>$v) {
                $options[]=array("label"=>$k,"value"=>$v);
        }
        $start_index = 0;
        $end_index = sizeof($options);
?>
        <select name="alias_type" id="alias_type" size="1" style="width: 190px" class="dataSelect">
         <?php
	   $temp = '';
           if ($value!=NULL) {
             echo('<option value="'.$value. '" selected > '.$value.'</option>');
             $temp = $value;
             $value = '';
           }

          for ($i=$start_index;$i<$end_index;$i++)
          {
           if ($options[$i]['label'] == $temp) {
                continue;
           } else {
             echo('<option value="'.$options[$i]['label']. '"> '.$options[$i]['label'].'</option>');
           }
          }
         ?>
         </select>
        <?php

}

function get_time_zones(){
    global $config;

    @$fp=fopen($config->zonetab_file, "r");
    if (!$fp) {$errors[]="Cannot open zone.tab file"; return array();}
    $out=array();

    while (!feof($fp)){
       $line=FgetS($fp, 512);
       if (substr($line,0,1)=="#") continue; //skip comments
       if (!$line) continue; //skip blank lines

       $line_a=explode("\t", $line);

       $line_a[2]=trim($line_a[2]);
       if ($line_a[2]) $out[]=$line_a[2];
    }

    fclose($fp);
    sort($out);
    return $out;
}

?>
