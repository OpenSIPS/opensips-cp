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


function display_dialog_table($message){

	unset($entry);

	for ($i=0;$i<count($message);$i++) {
	
		$row_style = ($i%2==1)?"rowOdd":"rowEven";

		if(!$_SESSION['read_only']){
			if ($message[$i]['state']<5)
       			     	$delete_link='<a href="'.$page_name.'?action=delete&id='.$message[$i]['ID'].'" onclick="return confirmDelete()"><img src="../../../images/share/delete.png" border="0"></a>';
			else
				$delete_link = "n/a";
        	}

		echo '<tr>';

		$state_values = array(1 => "Unconfirmed Call", 2 => "Early Call", 3 => "Confirmed Not Acknoledged Call", 4 => "Confirmed Call", 5 => "Deleted Call");
		$entry[$i]['state'] = $state_values[$message[$i]['state']];

		//timestart and duration
		if ($message[$i]['timestart'] == 0) {
			$entry[$i]['start_time'] = "n/a";
			$entry[$i]['duration'] = 0;
		} else {
			$entry[$i]['start_time'] = date("Y-m-d H:i:s",$message[$i]['timestart']);

			if ((time() - $message[$i]['timestart']) < 3600)
				$entry[$i]['duration'] = gmdate("i:s",(time()-$message[$i]['timestart']));
			else
				$entry[$i]['duration'] = gmdate("H:i:s",(time()-$message[$i]['timestart']));
		}

		//expire time
		if ($message[$i]['timeout'] == 0)
			$entry[$i]['expire_time'] = "n/a";
		else
			$entry[$i]['expire_time'] = date("Y-m-d H:i:s",$message[$i]['timeout']);

		//toURI
		$entry[$i]['toURI']=$message[$i]['to_uri'];

		//fromURI
		$entry[$i]['fromURI']=$message[$i]['from_uri'];

		//callID
		$entry[$i]['callID']=$message[$i]['callid'];

		unset($res);

		echo "<td class=".$row_style.">&nbsp;".$entry[$i]["callID"]."</td>";
		echo "<td class=".$row_style.">&nbsp;".$entry[$i]["fromURI"]."</td>";
		echo "<td class=".$row_style.">&nbsp;".$entry[$i]["toURI"]."</td>";
		echo "<td class=".$row_style.">&nbsp;".$entry[$i]["start_time"]."</td>";
		echo "<td class=".$row_style.">&nbsp;".$entry[$i]["expire_time"]."</td>";
		echo "<td class=".$row_style.">&nbsp;".$entry[$i]["duration"]."</td>";
		echo "<td class=".$row_style.">&nbsp;".$entry[$i]["state"]."</td>";

		if(!$_SESSION['read_only']){
			echo('<td class="'.$row_style.'Img" align="center">'.$delete_link.'</td>');
		}
  		echo '</tr>';
	}
	unset($entry);
}

?>

