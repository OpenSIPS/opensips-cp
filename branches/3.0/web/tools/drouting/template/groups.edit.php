<!--
 * $Id$
 -->

<form action="<?=$page_name?>?action=modify&id=<?=$_GET['id']?>" method="post">
<table width="400" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" class="dataTitle">Edit '<?=$_GET['id']?>'</td>
 </tr>
<?php
 if (isset($form_error)) {
                          echo(' <tr align="center">');
                          echo('  <td colspan="2" class="dataRecord"><div class="formError">'.$form_error.'</div></td>');
                          echo(' </tr>');
                         }
?>
 <tr>
  <td class="dataRecord"><b>Username:</b></td>
  <td class="dataRecord" width="275"><input type="text" name="username" value="<?=$resultset[0]['username']?>" maxlength="128" class="dataInput"></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Domain:</b></td>
  <td class="dataRecord"><input type="text" name="domain" value="<?=$resultset[0]['domain']?>" maxlength="64" class="dataInput"></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Group ID:</b></td>
  <td class="dataRecord"><input type="text" name="groupid" value="<?=$resultset[0]['groupid']?>" maxlength="11" class="dataInput"></td>
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
