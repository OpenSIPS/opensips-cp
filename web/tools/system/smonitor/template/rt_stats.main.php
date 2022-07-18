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

<form action="<?=$page_name?>" method="post"> 
<?php csrfguard_generate(); ?>
<table class="ttable" width="300" cellspacing="2" cellpadding="2" border="0">
 <tr>
  <th colspan="2" align="center" class="mainTitle">Click a module to see its statistics</th>
 </tr>
<?php
 $var_index=0;
 for($i=0; $i<$_SESSION['modules_no']; $i++)
 {
  $module=$_SESSION['module_name'][$i];
  $module_img="../../../images/share/right.png";
  $no_vars=$_SESSION['module_vars'][$i];
  $reset_flag="&nbsp;";
  $var_string="";
  if ($_SESSION["module_open"][$i]=="yes")
  {
   $module_img="../../../images/share/down.png";
   if (!$_read_only) $reset_flag="reset";
   $vars=get_vars($module,$_SESSION['smon_current_box']);
   $var_string.='<table width="100%" cellspacing="0" cellpadding="0" border="0">';
   for ($k=0; $k<sizeof($vars[0]); $k++)
   {
    $var_name=$module.":".$vars[0][$k];
    $var_checked=""; $bold_=""; $_bold="";
    $sql = "SELECT * FROM ".$table." WHERE name = ? AND box_id = ?";
	$stm = $link->prepare($sql);
	if ($stm->execute(array($var_name, $box_id)) === false)
		die('Failed to issue query, error message : ' . print_r($stm->errorInfo(), true));
	$resultset = $stm->fetchAll(PDO::FETCH_ASSOC);
    if (count($resultset)>0) {
                                    $var_checked="checked"; $bold_="<b>"; $_bold="</b>";
                                   }
    $var_string.=' <tr>';
    $var_string.='  <td align="left">';
    if (!$_read_only) $var_string.='   &nbsp;<input type="checkbox" name="var[]" '.$var_checked.' onchange="document.location.href=\''.$page_name.'?var='.$var_name.'\' ">'.$bold_.$vars[0][$k].$_bold.' = '.$vars[1][$k];
    else $var_string.='   &nbsp;'.$bold_.$vars[0][$k].$_bold.' = '.$vars[1][$k];
    $var_string.='  </td>';
    $var_string.='  <td align="right">';
    if (!$_read_only) $var_string.='   <input type="checkbox" name="reset[]" value="'.$vars[0][$k].'">&nbsp;';
    else $var_string.='   &nbsp;';
    $var_string.='  </td>';
    $var_string.=' </tr>';
    $var_index++;
   }
   $var_string.='</table>';
  }
 ?>
 <tr>
  <td colspan="2" class="rowOdd">
   <table width="100%" cellspacing="0" cellpadding="0" border="0">
   <tr>
     <div style="float: left; width: 85%; "  id="modul_<?=$module?>" class="Data" onMouseOver="this.style.cursor='pointer'" onClick="document.location.href='<?=$page_name?>?module_id=<?=$i?>'">
      <img src="<?=$module_img?>"> &nbsp; Module: <?=$module?> (<?=$no_vars?>)
     </div>
    <div style="float: left;padding: 5px;"><?=$reset_flag?></div>
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
 <tr>
  <th colspan="2" class="smonitorTitle"><img src="../../../images/share/spacer.gif" width="5" height="5"></th>
 </tr>
 



 <tr>
  <th colspan="2" align="center" class="mainTitle">Custom statistics</th>
 </tr>
 <?php
 $var_index=0;
 for($i=0; $i<$_SESSION['custom_modules_no']; $i++)
 {
  $module=$_SESSION['custom_module_name'][$i];
  
  $module_img="../../../images/share/right.png";
  $no_vars=$_SESSION['custom_module_vars'][$i];
  $reset_flag="&nbsp;";
  $var_string="";
  if ($_SESSION["custom_module_open"][$i]=="yes")
  {
   $module_img="../../../images/share/down.png";
   if (!$_read_only) $reset_flag="reset";
   $vars=get_custom_vars($module,$_SESSION['smon_current_box']);
   $var_string.='<table width="100%" cellspacing="0" cellpadding="0" border="0">';
   for ($k=0; $k<sizeof($vars[0]); $k++)
   {
    $var_name=$vars[0][$k];
    $var_checked=""; $bold_=""; $_bold="";
    $sql = "SELECT * FROM ".$table." WHERE name = ? AND box_id = ?";
	$stm = $link->prepare($sql);
	if ($stm->execute(array($var_name, $box_id)) === false)
		die('Failed to issue query, error message : ' . print_r($stm->errorInfo(), true));
	$resultset = $stm->fetchAll(PDO::FETCH_ASSOC);
    if (count($resultset)>0) {
                                    $var_checked="checked"; $bold_="<b>"; $_bold="</b>";
                                   }
    $var_string.=' <tr>';
    $var_string.='  <td align="left">';
    if (!$_read_only) $var_string.='   &nbsp;<input type="checkbox" name="var[]" '.$var_checked.' onchange="document.location.href=\''.$page_name.'?var='.$var_name.'\' ">'.$bold_.$vars[0][$k].$_bold.' = '.$vars[1][$k];
    else $var_string.='   &nbsp;'.$bold_.$vars[0][$k].$_bold.' = '.$vars[1][$k];
    $var_string.='  </td>';
    $var_string.='  <td align="right">';
    if (!$_read_only) $var_string.='   <input type="checkbox" name="reset[]" value="'.$vars[0][$k].'">&nbsp;';
    else $var_string.='   &nbsp;';
    $var_string.='  </td>';
    $var_string.=' </tr>';
    $var_index++;
   }
   $var_string.='</table>';
  }
 ?>
 <tr>
  <td colspan="2" class="rowOdd">
   <table width="100%" cellspacing="0" cellpadding="0" border="0">
   <tr>
     <div style="float: left; width: 85%; "  id="modul_<?=$module?>" class="Data" onMouseOver="this.style.cursor='pointer'" onClick="document.location.href='<?=$page_name?>?custom_module_id=<?=$i?>'">
      <img src="<?=$module_img?>"> &nbsp; Tool: <?=$module?> (<?=$no_vars?>)
     </div>
    <div style="float: left;padding: 5px;"><?=$reset_flag?></div>
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
 <tr>
  <th colspan="2" class="smonitorTitle"><img src="../../../images/share/spacer.gif" width="5" height="5"></th>
 </tr>
 


 
</table>
<br>
<?php
 if ((!$_read_only) && ($expanded)) echo('<input type="submit" name="reset_stats" value="Reset Checked" class="formButton">');
?>
</form>
