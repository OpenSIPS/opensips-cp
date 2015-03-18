<!--
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
-->
<div align="right">
	<form name="refreshform" action="<?=$page_name?>?action=refresh" method="post">
		<input type="submit" name="refresh" value="Refresh Dialog List" class="ButtonLink">
	</form>
</div>
<br>

<?php
$mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);

for ($i=0;$i<count($mi_connectors);$i++){
	$mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);

// get status from the first one only
	$comm_type=params($mi_connectors[0]);
	$comm = "dlg_list ".$start_limit." ".$config->results_per_page;
	$message=mi_command($comm , $errors , $status);
	print_r($errors);
	$status = trim($status);

	if ($comm_type != "json"){
		$tempmess = explode("dlg_counter:: ",$message);
		$pos = strpos($message, "\n",0);
		$data_no = substr($message,14,$pos-14);
		$message = substr($message,$pos);
	}
	else {
		$message = json_decode($message,true);
		$data_no = $message['dlg_counter'][0]['value'];
		$message = $message['dlg_counter'];
	}
}


echo '<table class="ttable" width="95%" cellspacing="2" cellpadding="2" border="0">';
echo '<tr align="center">';
echo '<th class="dialogTitle">Call ID</th>';
echo '<th class="dialogTitle">From URI</th>';
echo '<th class="dialogTitle">To URI</th>';
echo '<th class="dialogTitle">Start Time</th>';
echo '<th class="dialogTitle">State</th>';

  unset($entry);
  if(!$_SESSION['read_only']){

    echo('<th class="dialogTitle">Stop Call</th>');
  }

 echo '</tr>';

if ($data_no==0) {
	echo('<tr><td colspan="6" class="rowEven" align="center"><br>'.$no_result.'<br><br></td></tr>');
}
else {
	// here goes the paging stuff
	$page=$_SESSION[$current_page];
	$page_no=ceil($data_no/$config->results_per_page);
	if ($page>$page_no) {
		$page=$page_no;
		$_SESSION[$current_page]=$page;
	}
	
	$start_limit=($page-1)*$config->results_per_page;

	if ($comm_type != "json") {
		$temp = explode ("dialog:: ",$message);
		$recno = count($temp);
		for ($i=1;$i<$recno;$i++) {
		
			$row_style = ($i%2==1)?"rowOdd":"rowEven";
		
			preg_match_all('/hash=\d+:\d+\s+/',$temp[$i],$hash);
			$temp[$i] = substr($temp[$i],strlen($hash[0][0]),strlen($temp[$i]));
			$temptemp = explode ("\n",$temp[$i]);

			for ($j=0;$j<count($temptemp);$j++){
				$tmp = explode (":: ",$temptemp[$j]);
				$res[trim($tmp[0])]=$tmp[1];
			}
			unset($temptemp);

			$hashtemp = explode ("=",$hash[0][0]);
			$hashie = explode(":",$hashtemp[1]);
			$entry[$i]['h_entry'] = $hashie[0];
			$entry[$i]['h_id'] = $hashie[1];

			if(!$_SESSION['read_only']){
				if ($res['state']==3 || $res['state']==4)
       		     	$delete_link='<a href="'.$page_name.'?action=delete&h_id='.$entry[$i]['h_id'].'&h_entry='.$entry[$i]['h_entry'].'" onclick="return confirmDelete()"><img src="images/trash.gif" border="0"></a>';
				else
					$delete_link = "n/a";
        	}

			echo '<tr>';

			$state_values = array(1 => "Unconfirmed Call", 2 => "Early Call", 3 => "Confirmed Not Acknoledged Call", 4 => "Confirmed Call", 5 => "Deleted Call");
			$entry[$i]['state'] = $state_values[$res['state']];

			//timestart
			$entry[$i]['start_time'] = date("Y-m-d H:i:s",$res['timestart']);

			//toURI
			$entry[$i]['toURI']=$res['to_uri'];

			//fromURI
			$entry[$i]['fromURI']=$res['from_uri'];

			//callID
			$entry[$i]['callID']=$res['callid'];

			unset($res);

			echo "<td class=".$row_style.">&nbsp;".$entry[$i]["callID"]."</td>";
			echo "<td class=".$row_style.">&nbsp;".$entry[$i]["fromURI"]."</td>";
			echo "<td class=".$row_style.">&nbsp;".$entry[$i]["toURI"]."</td>";
			echo "<td class=".$row_style.">&nbsp;".$entry[$i]["start_time"]."</td>";
			echo "<td class=".$row_style.">&nbsp;".$entry[$i]["state"]."</td>";

			if(!$_SESSION['read_only']){
				echo('<td class="'.$row_style.'" align="center">'.$delete_link.'</td>');
			}
	  		echo '</tr>';
		}
		unset($entry);
	}
	else {
		for ($i=1;$i<count($message);$i++) {
		
			$row_style = ($i%2==1)?"rowOdd":"rowEven";
	
			$temp_hash = explode(":",$message[$i]['attributes']['hash']);

			$entry[$i]['h_entry'] = $temp_hash[0];
			$entry[$i]['h_id'] = $temp_hash[1];

			if(!$_SESSION['read_only']){
				if ($message[$i]['children']['state']<5)
       		     	$delete_link='<a href="'.$page_name.'?action=delete&h_id='.$entry[$i]['h_id'].'&h_entry='.$entry[$i]['h_entry'].'" onclick="return confirmDelete()"><img src="images/trash.gif" border="0"></a>';
				else
					$delete_link = "n/a";
        	}

			echo '<tr>';

			$state_values = array(1 => "Unconfirmed Call", 2 => "Early Call", 3 => "Confirmed Not Acknoledged Call", 4 => "Confirmed Call", 5 => "Deleted Call");
			$entry[$i]['state'] = $state_values[$message[$i]['children']['state']];

			//timestart
			$entry[$i]['start_time'] = date("Y-m-d H:i:s",$message[$i]['children']['timestart']);

			//toURI
			$entry[$i]['toURI']=$message[$i]['children']['to_uri'];

			//fromURI
			$entry[$i]['fromURI']=$message[$i]['children']['from_uri'];

			//callID
			$entry[$i]['callID']=$message[$i]['children']['callid'];

			unset($res);

			echo "<td class=".$row_style.">&nbsp;".$entry[$i]["callID"]."</td>";
			echo "<td class=".$row_style.">&nbsp;".$entry[$i]["fromURI"]."</td>";
			echo "<td class=".$row_style.">&nbsp;".$entry[$i]["toURI"]."</td>";
			echo "<td class=".$row_style.">&nbsp;".$entry[$i]["start_time"]."</td>";
			echo "<td class=".$row_style.">&nbsp;".$entry[$i]["state"]."</td>";

			if(!$_SESSION['read_only']){
				echo('<td class="'.$row_style.'" align="center">'.$delete_link.'</td>');
			}
	  		echo '</tr>';
		}
		unset($entry);
	}
}

?>


<tr>
<th colspan="6" class="dialogTitle">
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
     <tr>
      <th align="left">
       &nbsp;Page:
       <?php
       if ($data_no==0) echo('<font class="pageActive">0</font>&nbsp;');
       else {
        $max_pages = $config->results_page_range;
        // start page
        if ($page % $max_pages == 0) $start_page = $page - $max_pages + 1;
        else $start_page = $page - ($page % $max_pages) + 1;
        // end page
        $end_page = $start_page + $max_pages - 1;
        if ($end_page > $page_no) $end_page = $page_no;
        // back block
        if ($start_page!=1) echo('&nbsp;<a href="'.$page_name.'?page='.($start_page-$max_pages).'" class="menuItem"><b>&lt;&lt;</b></a>&nbsp;');
        // current pages
        for($i=$start_page;$i<=$end_page;$i++)
        if ($i==$page) echo('<font class="pageActive">'.$i.'</font>&nbsp;');
        else echo('<a href="'.$page_name.'?page='.$i.'" class="pageList">'.$i.'</a>&nbsp;');
        // next block
        if ($end_page!=$page_no) echo('&nbsp;<a href="'.$page_name.'?page='.($start_page+$max_pages).'" class="menuItem"><b>&gt;&gt;</b></a>&nbsp;');
       }
       ?>
      </th>
      <th align="right">Total Records: <?=$data_no?>&nbsp;</th>
     </tr>
    </table>
  </th>
 </tr>
    </th>
 </tr>
</table>

<br>

