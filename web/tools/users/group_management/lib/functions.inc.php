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
        $sql="select domain from $table_domains";
        $stm = $link->query($sql);
	if ($stm === FALSE)
		die('Failed to issue query, error message : ' . print_r($link->errorInfo(), true));
	$result = $stm->fetchAll(PDO::FETCH_ASSOC);

	if ($has_any)
	        $options[]=array("label"=>"ANY","value"=>"ANY");
        foreach ($result as $k=>$v) {
                $options[]=array("label"=>$v['domain'],"value"=>$v['domain']);
        }

        $start_index = 0;
	$temp = '';
        $end_index = sizeof($options);

		echo('<select name="'.$type.'" id="'.$type.'" size="1" style="width: 200px" class="dataSelect">');
		if ($value!=NULL) {
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
	 ?>
	 </select>
	<?php
}

function print_groups($type,$value,$has_any){
	session_load();
	?>
	<select name=<?=$type?> id=<?=$type?> size="1" style="width: 190px" class="dataSelect">
	<?php
	if ($value!=NULL) 
		echo('<option value="'.$value. '" selected > '.$value.'</option>');
	if ($has_any)
		echo('<option value="ANY" selected >ANY</option>');
	
	foreach (get_settings_value("grps") as $grp){
		if (strcmp($grp,$value)==0) 
			continue;
		else
			echo("<option value=".$grp."> ".$grp."</option>");						
	 }
	 ?>
	 </select>
	 <?php
}

?>
