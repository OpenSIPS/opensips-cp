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
<form action="<?=$page_name?>?action=profile_list" method="post">
<table width="95%" cellspacing="2" cellpadding="2" border="0">
  <tr height="10">
          <td align="right"><b>Select profile from list: </b></td>
          <td align="left"><?php print_profile();?></td>
  </tr>
  <tr height="10">
          <td align="right"><b>Parameter: </b></td>
          <td align="left"><input name="profile_param" type="text" class="newParam"></td>
  </tr>
  <tr height="10"> </tr>
 <tr height="10">
        <td align="center" colspan="2"><input type="checkbox" name="sizes" checked> List number of dialogs in the selected profile</td>
 </tr>
 <!--tr height="10">
        <td align="center" colspan="2"><input type="checkbox" name="dialogs"> List the dialogs in the selected profile</td>
 </tr-->
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
		if (isset($_POST['profile_param']))
                	$profile_param = $_POST['profile_param'];
        	else
		        $profile_param = "";

		$profile = $_POST['profile'];
                $mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);
                for ($i=0;$i<count($mi_connectors);$i++){
                        // get status from the first one only
                        $comm_type=params($mi_connectors[0]);
			if ($profile_param == "")
	                        $msg=mi_command("profile_get_size $profile" , $errors , $status);
			else
				$msg=mi_command("profile_get_size $profile $profile_param" , $errors , $status);
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


                $temp = explode ("dialog:: ",$message);
                $recno = count($temp);
                for ($i=1;$i<$recno;$i++) {
                        preg_match_all('/hash=\d+:\d+\s+/',$temp[$i],$hash);
                        $temp[$i] = substr($temp[$i],strlen($hash[0][0]),strlen($temp[$i]));
                        $temptemp = explode ("\n",$temp[$i]);

                        for ($j=0;$j<count($temptemp);$j++){
                                $tmp = explode (":: ",$temptemp[$j]);
                                $res[trim($tmp[0])]=$tmp[1];
                        }


                //unset($temp);
                unset($temptemp);
        //get h_id & h_entry

                $hashtemp = explode ("=",$hash[0][0]);
                $hashie = explode(":",$hashtemp[1]);
                $entry[$i]['h_entry'] = $hashie[0];
                $entry[$i]['h_id'] = $hashie[1];

                if(!$_SESSION['read_only']){
                        $delete_link='<a href="'.$page_name.'?action=delete&h_id='.$entry[$i]['h_id'].'&h_entry='.$entry[$i]['h_entry'].'" onclick="return confirmDelete()"><img src="images/trash.gif" border="0"></a>';
                }

echo '<tr>';


if ($res['state']==1) $entry[$i]['state']="Unconfirmed Call";
                else if ($res['state']==2) $entry[$i]['state']="Early Call";
                else if ($res['state']==3) $entry[$i]['state']="Confirmed Not Acknoledged Call";
                else if ($res['state']==4) $entry[$i]['state']="Confirmed Call";
                else if ($res['state']==5) $entry[$i]['state']="Deleted Call";


        //timestart

                $entry[$i]['start_time'] = date("Y-m-d H:i:s",$res['timestart']);

        //toURI

                $entry[$i]['toURI']=$res['to_uri'];

        //fromURI

                $entry[$i]['fromURI']=$res['from_uri'];

        //callID

                $entry[$i]['callID']=$res['callid'];

                unset($res);

 if ($i%2==1) $row_style="rowOdd";
 else $row_style="rowEven";


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
}
unset($entry);

?>
 <tr>
<td colspan="6" class="dialogTitle">
  </td>
 </tr>
    </td>
 </tr>
 </table>
</form>
<?php
 }
?>
</form>
<br>
