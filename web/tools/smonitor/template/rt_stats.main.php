<?php
/*
 * $Id: rt_stats.main.php,v 1.2 2007-04-03 13:10:53 daniel Exp $
 */
?>

<form action="<?=$page_name?>" method="post"> 
<table width="300" cellspacing="2" cellpadding="2" border="0">
 <tr>
  <td colspan="2" align="center" class="Title">Click a module to see its statistics</td>
 </tr>
<?php
 db_connect();
 $var_index=0;
 for($i=0; $i<$_SESSION['modules_no']; $i++)
 {
  $module=$_SESSION['module_name'][$i];
  $module_img="images/module.gif";
  $no_vars=$_SESSION['module_vars'][$i];
  $reset_flag="&nbsp;";
  $var_string="";
  if ($_SESSION["module_open"][$i]=="yes")
  {
   $module_img="images/module-open.gif";
   if (!$_read_only) $reset_flag="reset";
   $vars=get_vars($module);
   for ($k=0; $k<sizeof($vars[0]); $k++)
   {
    $var_name=$module.":".$vars[0][$k];
    $var_checked=""; $bold_=""; $_bold="";
    $result=mysql_query("SELECT * FROM ".$table." WHERE name='".$var_name."' AND box_id=".$box_id) or die(mysql_error());
    if (mysql_num_rows($result)>0) {
                                    $var_checked="checked"; $bold_="<b>"; $_bold="</b>";
                                   }
    $var_string.='<table width="100%" cellspacing="0" cellpadding="0" border="0">';
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
    $var_string.='</table>';
    $var_index++;
   }
  }
 ?>
 <tr>
  <td colspan="2" class="rowOdd">
   <table width="100%" cellspacing="0" cellpadding="0" border="0">
   <tr>
    <td align="left">
     <div id="modul_<?=$module?>" class="Data" onMouseOver="this.style.cursor='pointer'" onClick="document.location.href='<?=$page_name?>?module_id=<?=$i?>'">
      <img src="<?=$module_img?>" width="16" height="16"> Module: <?=$module?> (<?=$no_vars?>)
     </div>
    </td>
    <td align="right"><?=$reset_flag?></td>
   </tr>
   </table>
  </td>
 </tr>
 <?php
  if ($var_string!="")
  {
   ?>
   <tr>
    <td width="5">&nbsp;</td>
    <td class="rowEven"><?=$var_string?></td>
   </tr>
   <tr>
    <td colspan="2" align="center"><img src="images/spacer.gif"></td>
   </tr>
   <?php
  }
 }
 db_close();
?>
 <tr>
  <td colspan="2" class="Title"><img src="images/spacer.gif" width="5" height="5"></td>
 </tr>
</table>
<br>
<?php
 if ((!$_read_only) && ($expanded)) echo('<input type="submit" name="reset_stats" value="Reset Checked" class="Button">');
?>
</form>