<!--
 * $Id: settings.main.php,v 1.2 2006-08-30 11:03:22 bogdan Exp $
 -->

<form action="<?=$page_name?>?action=gw_types" method="post">
<?php
 $filename="../../../config/tools/drouting/gw_types.txt";
 $handle=fopen($filename,"r");
 $content=fread($handle,filesize($filename));
 fclose($handle);
?>
<table width="300" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td class="searchTitle">Gateway Types File</td>
 </tr>
<?php
 if ($gw_error!="") {
                     echo(' <tr>');
                     echo('  <td class="rowOdd" align="center"><div class="formError">'.$gw_error.'</div></td>');
                     echo(' </tr>');
                    }
?>
 <tr>
  <td class="rowOdd" align="center">
   <textarea name="data" rows="5" class="searchInput"><?=$content?></textarea><br>
  </td>
 </tr>
 <?php
  if (!$_read_only) echo('<tr><td class="rowOdd" align="center"><input type="submit" name="save" value="Save Changes" class="searchButton"></td></tr>');
 ?>
 <tr height="10">
  <td class="searchTitle"><img src="images/spacer.gif" width="5" height="5"></td>
 </tr>
</table>
</form>
<br>

<?php
if ($config->group_id_method=="static")
{ 
?>
<form action="<?=$page_name?>?action=groups" method="post">
<?php
 $filename="../../../config/tools/drouting/group_ids.txt";
 $handle=fopen($filename,"r");
 $content=fread($handle,filesize($filename));
 fclose($handle);
?>
<table width="300" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td class="searchTitle">Group IDs File</td>
 </tr>
<?php
 if ($groups_error!="") {
                         echo(' <tr>');
                         echo('  <td class="rowOdd" align="center"><div class="formError">'.$groups_error.'</div></td>');
                         echo(' </tr>');
                        }
?>
 <tr>
  <td class="rowOdd" align="center">
   <textarea name="data" rows="5" class="searchInput"><?=$content?></textarea><br>
  </td>
 </tr>
 <?php
  if (!$_read_only) echo('<tr><td class="rowOdd" align="center"><input type="submit" name="save" value="Save Changes" class="searchButton"></td></tr>');
 ?>
 <tr height="10">
  <td class="searchTitle"><img src="images/spacer.gif" width="5" height="5"></td>
 </tr>
</table>
</form>
<?php
}
?>
