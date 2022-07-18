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

<form action="<?=$page_name?>?action=execute" method="post" name="form">
<?php csrfguard_generate(); ?>
<table width="500" cellspacing="2" cellpadding="2" border="0">
 <tr>
  <td colspan="2" class="mainTitle" align="center">Execute MI Command</td>
 </tr>
 <tr>
  <td class="searchRecord">Command</td>
  <td class="searchRecord"><input name="mi_cmd" type="text" class="dataInput" value="" size="65"></td>
 </tr>
<?php
if (!$_read_only)
{
?> 
 <tr>
  <td colspan="2" class="searchRecord">
   <table width="100%" cellspacing="0" cellpadding="0" border="0">
    <tr>
     <td width="150">&nbsp;</td>
     <td align="center"><input name="execute" type="submit" value="eXecute" class="formButton"></td>
     <td width="150" align="right"><?=print_command_list()?></td>
    </tr>
   </table>
  </td>
 </tr>
<?php
}
?>
</table>
</form>

<table class="ttable" width="450" cellspacing="2" cellpadding="2" border="0">
 <tr>
  <th align="center" class="miTitle"><img src="../../../images/share/spacer.gif" width="52" height="5">History</th>
  <th width="50" align="center" class="miTitle"><button type="button" class="formButton" onClick="window.location='<?=$page_name?>?action=clear_history'">clear</button></th>
 </tr>
 <?php

 $data_no=sizeof($_SESSION['mi_command']);
 if ($data_no==0) echo('<tr><td colspan="2" class="rowEven" align="center"><br>'.$no_result.'<br><br></td></tr>');
 else {
 	$n=$data_no-1;
 	for($j=0;$j<$data_no;$j++)
 	{
 		$i=$n-$j;
 		if ($i%2==0) $row_style="rowOdd";
 		else $row_style="rowEven";
        ?>
         <tr>
          <td colspan="2" class="<?=$row_style?>">
           <i><?=$_SESSION['mi_time'][$i]?></i>&nbsp;&nbsp;|&nbsp;&nbsp;<b><?=$_SESSION['mi_command'][$i]?></b>&nbsp;&nbsp;|&nbsp;&nbsp;<?=$_SESSION['mi_box'][$i]?><br>
			<hr width="100%">
           <pre><?=$_SESSION['mi_response'][$i]?></pre>
          </td>
         </tr>
        <?php
 	}
 }
 ?>
 <tr>
  <th colspan="2" class="miTitle"><img src="../../../images/share/spacer.gif" width="5" height="5"></th>
 </tr>
</table>
