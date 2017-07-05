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

		echo('<select name="'.$type.'" id="'.$type.'" size="1" style="width: 190px" class="dataSelect">');
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
	require("../../../../config/tools/users/acl_management/local.inc.php");
	?>
	<select name=<?=$type?> id=<?=$type?> size="1" style="width: 190px" class="dataSelect">
	<?php
	if ($value!=NULL) 
		echo('<option value="'.$value. '" selected > '.$value.'</option>');
	if ($has_any)
		echo('<option value="ANY" selected >ANY</option>');

	foreach ($config->grps as $grp){
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
