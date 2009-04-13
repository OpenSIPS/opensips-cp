<!--
 * $Id$
-->

<form action="<?=$page_name?>?action=modify&id=<?=$_GET['id']?>" method="post">
<table width="400" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" class="dataTitle">Edit Gateway #<?=$_GET['id']?></td>
 </tr>
<?php
 if (isset($form_error)) {
                          echo(' <tr align="center">');
                          echo('  <td colspan="2" class="dataRecord"><div class="formError">'.$form_error.'</div></td>');
                          echo(' </tr>');
                         }
?>
 <tr>
  <td class="dataRecord"><b>Type:</b></td>
  <td class="dataRecord" width="275"><?=get_types("type",$row['type'])?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Address:</b></td>
  <td class="dataRecord" width="275"><input type="text" name="address" value="<?=$row['address']?>" maxlength="128" class="dataInput"></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Strip:</b></td>
  <td class="dataRecord"><input type="text" name="strip" value="<?=$row['strip']?>" maxlength="11" class="dataInput"></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>PRI Prefix:</b></td>
  <td class="dataRecord"><input type="text" name="pri_prefix" value="<?=$row['pri_prefix']?>" maxlength="16" class="dataInput"></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Description:</b></td>
  <td class="dataRecord"><input type="text" name="description" value="<?=$row['description']?>" maxlength="128" class="dataInput"></td>
 </tr>
 <tr>
  <td colspan="2" class="dataRecord" align="center"><input type="submit" name="edit" value="Save" class="formButton"></td>
 </tr>
 <tr height="10">
  <td colspan="2" class="dataTitle"><img src="images/spacer.gif" width="5" height="5"></td>
 </tr>
</table>
</form>
<?=$back_link?>