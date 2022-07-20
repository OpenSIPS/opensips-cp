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

  //consoole_log(mi_command("sr_list_reports", array("group" => "drouting", "identifier" => "Default"), $boxes[0]['mi_conn'], $errors));
?>

<form action="<?=$page_name?>" method="post"> 
<?php csrfguard_generate(); ?>
<table class="ttable" width="300" cellspacing="2" cellpadding="2" border="0">
 <tr>
  <th colspan="2" align="center" class="mainTitle">Click an identifier to see its report<br><br></th>
 </tr>
<?php
 $var_index=0;
 for($i=0; $i<$_SESSION['identifiers_no']; $i++)
 {
	$identifier=$_SESSION['identifier_name'][$i];
	$identifier_img="../../../images/share/right.png";
	$reset_flag="&nbsp;";
	$var_string = "";
	if ($_SESSION["identifier_open"][$i]=="yes")
	{	
		$identifier_img="../../../images/share/down.png";
		if (!$_read_only) $reset_flag="reset";
		preg_match('/(?<group>.*)\: (?<identifier>.*)/', $identifier, $matches);
		$reports = get_report($current_box, $matches['group'], $matches['identifier']);

		$var_string = '
		<table class="ttable" width="95%" cellspacing="1" cellpadding="1" border="1" align="right">
		<tr align="center">
		<th class="listTitle" align="center">Date</th>
		<th class="listTitle" align="center">Log</th>
		</tr>
		';
		$k = 0;
		foreach($reports as $report) {
			if ( $k%2 == 0 ) $row_style="rowOdd";
			else $row_style="rowEven";
			$var_string .= '<tr align="center">
			<td  class="'.$row_style.'">'.$report['Date'].'</td>
			<td class="'.$row_style.'">'.$report['Log'].'</td>
			</tr>
			';
			$k++;
		}
		$var_string .= "</table>";
		if (is_null($reports) || count($reports) == 0) {
			$var_string = "<div style='font-weight: bold;'>Empty report</div>";
		}
  	}
 ?>
 <tr>
  <td colspan="2" class="rowOdd">
   <table width="100%" cellspacing="0" cellpadding="0" border="0">
   <tr>
     <div style="float: left; width: 85%; "  id="identifier_<?=$identifier?>" class="Data" onMouseOver="this.style.cursor='pointer'" onClick="document.location.href='<?=$page_name?>?identifier_id=<?=$i?>'">
      <img src="<?=$identifier_img?>"> &nbsp; &nbsp; &nbsp;<?=$identifier?> 
     </div>
   </tr>
   </table>
  </td>
 </tr>
 <?php
  if ($var_string!="")
  {
   ?>
   <tr>
    <td class="rowEven"><?=$var_string?></td>
   </tr>
   <tr>
    <td colspan="2" align="center"><img src="../../../images/share/spacer.gif"></td>
   </tr>
   <?php
  }
 }
?>
</table>
<br>
</form>
