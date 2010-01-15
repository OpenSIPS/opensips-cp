<!--
 /*
 * $Id: profile.main.php 74 2009-07-03 13:39:50Z iulia_bublea $
 * Copyright (C) 2008 Voice Sistem SRL
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
<form action="<?=$page_name?>?action=profile_list" method="post">
<table width="95%" cellspacing="2" cellpadding="2" border="0">
  <tr height="10">
	  <td align="right"><b>Select profile from list: </b></td>
  	  <td align="left"><?php print_profile();?></td>		
  </tr>
  <tr height="10"> </tr>
 <tr height="10">
	<td align="center" colspan="2"><input type="checkbox" name="sizes"> List number of dialogs in the selected profile</td>
 </tr>
 <tr height="10">
	<td align="center" colspan="2"><input type="checkbox" name="dialogs"> List the dialogs in the selected profile</td>
 </tr>
  <tr align="center" colspan="2" class="searchRecord" align="center">
 	 <td align="center" colspan="2"><input type="submit" name="submit" value="Submit" class="searchButton">&nbsp;&nbsp;&nbsp;</td>
  </tr>
  <tr height="10">
   	<td colspan="2" class="dialogTitle"><img src="images/spacer.gif" width="5" height="5"></td>
  </tr>
<br>
</table>
<br><br>
<?php 
if (isset($_POST['sizes'])) {
?>
	<table width="95%" cellspacing="2" cellpadding="2" border="0">
	<tr align="center">
	  <?php
	        $mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);
        	for ($i=0;$i<count($mi_connectors);$i++){
	                // get status from the first one only
        	        $comm_type=params($mi_connectors[0]);
                	$msg=mi_command("profile_get_size $profile" , $errors , $status);
             	        print_r($errors);
                 	$status = trim($status);
        }

		if (!empty($msg)) {
			preg_match('/count=\d+/',$msg,$matches);
			$profile_size=explode("=",$matches[0]);
			echo ('Number of dialogs in profile <b>'.$profile. '</b> is <b>' . $profile_size[1] .'</b>');
			unset($_SESSION['profile_size']);
		}
  	?>
	</tr>
	</table>
<br>
<?php
}
if (isset($_POST['dialogs'])) {
?>
<form action="<?=$page_name?>?action=load" method="post">
 <table width="95%" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td class="dialogTitle">Call ID</td>
  <td class="dialogTitle">From URI</td>
  <td class="dialogTitle">To URI</td>
  <td class="dialogTitle">Start Time</td>
  <td class="dialogTitle">State</td>
  <?
  unset($entry);
  if(!$_SESSION['read_only']){

        echo('<td class="dialogTitle">Stop Call</td>');
  }
  ?>
 </tr>
 <?php	
 $message=$_SESSION['message'];
 $data_no=count($message);
 if ($data_no==0) echo('<tr><td colspan="6" class="rowEven" align="center"><br>'.$no_result.'<br><br></td></tr>');
 else {
	$entry = array();
          //hash
 	preg_match_all('/hash=\d+:\d+\s+/',$message,$hash);
        preg_match_all('/timestart::\s+\d+\s+/',$message,$timestart);
        preg_match_all('/state::\s+\d+\s+/',$message,$st);
        preg_match_all('/callid::\s+[0-9a-zA-z-]+\@[0-9a-z.]+[a-z]*\s+/',$message,$callid);
        preg_match_all('/to_uri::\s+sip\:[a-z0-9.]+@[0-9a-z.]+[a-z]*\:[0-9]+\s+/',$message,$to_uri);
        preg_match_all('/from_uri::\s+sip\:[a-z0-9.]+@[0-9a-z.]+[a-z]*\:[0-9]+\s+/',$message,$from_uri);

        for($i=0;$i<count($hash[0]);$i++) {
                $temp1=explode("=",$hash[0][$i]);
                $temp2=explode(":",$temp1[1]);
                $entry[$i]['h_id']=$temp2[1];
                $entry[$i]['h_entry']=$temp2[0];

                if(!$_SESSION['read_only']){
                        $delete_link='<a href="'.$page_name.'?action=delete&h_id='.$entry[$i]['h_id'].'&h_entry='.$entry[$i]['h_entry'].'" onclick="return confirmDelete()"><img src="images/trash.gif" border="0"></a>';
                }

?>
 <tr>
<?php
//         $details='<a href="details.php?callid='.$result[$i]['callid'].'"><img src="images/trace.png" border="0" onClick="window.open(\'details.php?callid='.$result[$i]['callid'].'&regexp='.$search_regexp.'\',\'info\',\'scrollbars=1,width=550,height=300\');return false;"></a>';

        //state
//        for($i=0;$i<count($st[0]);$i++) {
                $temp=explode("::",$st[0][$i]);
		if ($temp[1]==1) $entry[$i]['state']="Unconfirmed Call";
		else if ($temp[1]==2) $entry[$i]['state']="Early Call";
		else if ($temp[1]==3) $entry[$i]['state']="Confirmed Not Acknoledged Call";
		else if ($temp[1]==4) $entry[$i]['state']="Confirmed Call";
		else if ($temp[1]==5) $entry[$i]['state']="Deleted Call";
//        }

        //timestart
//        for($i=0;$i<count($timestart[0]);$i++) {
                $temp1=explode("::",$timestart[0][$i]);
                $temp2=getdate($temp1[1]);
                $entry[$i]['start_time']=$temp2['mday']."-".$temp2['mon']."-".$temp2['year']." ".$temp2['hours'].":".$temp2['minutes'].":".$temp2['seconds'];
//      }

        //toURI
//        for($i=0;$i<count($to_uri[0]);$i++) {
                $temp=explode("::",$to_uri[0][$i]);
                $entry[$i]['toURI']=$temp[1];
//      }

        //fromURI

 //       for($i=0;$i<count($from_uri[0]);$i++) {
                $temp=explode("::",$from_uri[0][$i]);
                $entry[$i]['fromURI']=$temp[1];
//      }

        //callID
//       for($i=0;$i<count($callid[0]);$i++) {
                $temp=explode("::",$callid[0][$i]);
                $entry[$i]['callID']=$temp[1];
//      }


 if ($i%2==1) $row_style="rowOdd";
 else $row_style="rowEven";
?>
  <td class="<?=$row_style?>">&nbsp;<?php print $entry[$i]['callID']?></td>
  <td class="<?=$row_style?>">&nbsp;<?php print $entry[$i]['fromURI']?></td>
  <td class="<?=$row_style?>">&nbsp;<?php print $entry[$i]['toURI']?></td>
  <td class="<?=$row_style?>">&nbsp;<?php print $entry[$i]['start_time'];?></td>
  <td class="<?=$row_style?>">&nbsp;<?php print $entry[$i]['state']?></td>
   <?php
   if(!$_SESSION['read_only']){
        echo('<td class="'.$row_style.'" align="center">'.$delete_link.'</td>');
   }
        ?>
  </tr>
<?php
        }

}
unset($entry);
?>
 <tr height="10">
  <td colspan="6" class="dialogTitle"><img src="images/spacer.gif" width="5" height="5"></td>
 </tr>
 <tr>
 </table>
</form>
<?php
 }
?>
</form>
<br>


