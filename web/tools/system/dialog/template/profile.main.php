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
?>

<form action="<?=$page_name?>?action=profile_list" method="post">
<?php csrfguard_generate(); ?>
<table width="350" cellspacing="2" cellpadding="2" border="0">

 <tr align="center">
  <td colspan="2" height="10" class="dialogTitle"></td>
 </tr>
  <tr height="10">
          <td class="searchRecord" align="right"><b>Profile Name: </b></td>
          <td class="searchRecord" align="left"><?php print_profile();?></td>
  </tr>
  <tr height="10">
          <td align="right" class="searchRecord" ><b>Value (optional):</b></td>
          <td align="left" class="searchRecord" ><input name="profile_param" type="text"></td>
  </tr>
 <tr height="10">
        <td class="searchRecord" align="center" colspan="2"><input type="checkbox" name="dialogs" > List dialogs in the selected profile</td>
 </tr>
 <!--tr height="10">
        <td align="center" colspan="2"><input type="checkbox" name="dialogs"> List the dialogs in the selected profile</td>
 </tr-->
  <tr align="center" colspan="2" class="searchRecord" align="center">
         <td align="center" colspan="2"><input type="submit" name="submit" value="Get size" class="searchButton">&nbsp;&nbsp;&nbsp;</td>
  </tr>
  <tr height="10">
        <td colspan="2" class="dialogTitle"><img src="../../../images/share/spacer.gif" width="5" height="5"></td>
  </tr>
<br>
</table>
<br><br>
<?php
if (isset($_POST['submit'])) {
?>
	<table width="95%" cellspacing="2" cellpadding="2" border="0">
	<tr align="center">
	<?php
		if (isset($_POST['profile_param']))
			$profile_param = $_POST['profile_param'];
		else
			$profile_param = "";

		$profile = $_POST['profile'];
		$mi_connectors=get_proxys_by_assoc_id(get_settings_value('talk_to_this_assoc_id'));
		// get status from the first one only
		$params = array("profile"=>$profile);
		if (!empty($profile_param))
			$params["value"] = $profile_param;
		$msg=mi_command("profile_get_size", $params, $mi_connectors[0], $errors);

		if (!empty($msg)) {
			$profile_size = $msg["Profile"]["count"];
			echo ('Number of dialogs in profile <b>'.$profile. '</b> is <b>' . $profile_size .'</b>');
			unset($_SESSION['profile_size']);
		}
	?>
	</tr>
	</table>
<br/>
<?php
}
if (isset($_POST['dialogs'])) {
	include "dialog_table.inc.php";

	echo '<table class="ttable" width="95%" cellspacing="2" cellpadding="2" border="0">';
	echo '<tr align="center">';
	echo '<th class="listTitle">Call ID</th>';
	echo '<th class="listTitle">From URI</th>';
	echo '<th class="listTitle">To URI</th>';
	echo '<th class="listTitle">Start Time</th>';
	echo '<th class="listTitle">Timeout Time</th>';
	echo '<th class="listTitle">Duration</th>';
	echo '<th class="listTitle">State</th>';
	if(!$_SESSION['read_only'])
		echo('<th class="listTitle">Stop Call</th>');
	echo '</tr>';

	if ($profile_size=="0")
		echo('<tr><td colspan="7" class="rowEven" align="center"><br>'.$no_result.'<br><br></td></tr>');
	else {
		$mi_connectors=get_proxys_by_assoc_id(get_settings_value('talk_to_this_assoc_id'));
		// get status from the first one only
		$message=mi_command("profile_list_dlgs", array("profile"=>$profile), $mi_connectors[0], $errors);

		if (!empty($msg)) {
			$dialogs = $message['Dialogs'];
			display_dialog_table($dialogs);
		} else {
			echo('<tr><td colspan="7" class="rowEven" align="center"><br>'.$no_result.'<br><br></td></tr>');
		}
	}

	echo '<tr>';
	echo '<th colspan="7" class="listTitle">Total Records: '.$profile_size.'&nbsp;</th>';
	echo '</table>';
 }
?>
</form>
<br>
