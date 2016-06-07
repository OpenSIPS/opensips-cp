<?php
/*
* $Id$
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


function print_domains($type,$value)
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
        foreach ($result as $k=>$v) {
                $options[]=array("label"=>$v['domain'],"value"=>$v['domain']);
        }

        if ($value=='ANY') {
                array_unshift($options,array("label"=>"ANY","value"=>"ANY"));
                $value='';
        }
        $start_index = 0;
        $end_index = sizeof($options);

?>
	<select <?php if (isset($_SESSION['fromusrmgmt'])) if ($_SESSION['fromusrmgmt']) echo "readonly "; ?> name=<?=$type?> id=<?=$type?> size="1" style="width: 190px" class="dataSelect">
	 <?php
           if ($value!=NULL) {
             echo('<option value="'.$value. '" selected > '.$value.'</option>');
             $temp = $value;
             $value = '';
           }
	  for ($i=$start_index;$i<$end_index;$i++)
	  {
           if ($options[$i]['value'] == $temp) {
                continue;
	   } else { 	
	     echo('<option value="'.$options[$i]['value']. '"> '.$options[$i]['value'].'</option>');
	   }
	  }
	 ?>
	 </select>
	<?php
}

function print_aliasType($value)
{
        global $config;
        require("../../../../config/globals.php");
        foreach ($config->table_aliases as $k=>$v) {
                $options[]=array("label"=>$k,"value"=>$v);
        }
        if ($value=='ANY') {
                array_unshift($options,array("label"=>"ANY","value"=>"ANY"));
                $value=0;
        }
        $start_index = 0;
        $end_index = sizeof($options);
?>
        <select name="alias_type" id="alias_type" size="1" style="width: 190px" class="dataSelect">
         <?php
           if ($value!=NULL) {
             echo('<option value="'.$value. '" selected > '.$value.'</option>');
             $temp = $value;
             $value = '';
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
