<!--
 * $Id: gateways.edit.php 57 2009-06-03 13:48:46Z iulia_bublea $
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
  <td class="dataRecord" width="275"><?=get_types("type",$resultset[0]['type'])?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Address:</b></td>
  <td class="dataRecord" width="275"><input type="text" name="address" value="<?=$resultset[0]['address']?>" maxlength="128" class="dataInput"></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Strip:</b></td>
  <td class="dataRecord"><input type="text" name="strip" value="<?=$resultset[0]['strip']?>" maxlength="11" class="dataInput"></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>PRI Prefix:</b></td>
  <td class="dataRecord"><input type="text" name="pri_prefix" value="<?=$resultset[0]['pri_prefix']?>" maxlength="16" class="dataInput"></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Description:</b></td>
  <td class="dataRecord"><input type="text" name="description" value="<?=$resultset[0]['description']?>" maxlength="128" class="dataInput"></td>
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
