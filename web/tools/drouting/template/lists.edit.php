<!--
 * $Id$
 -->

<form action="<?=$page_name?>?action=modify&id=<?=$_GET['id']?>" method="post">
<table width="465" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" class="dataTitle">Edit Rule #<?=$_GET['id']?></td>
 </tr>
<?php
 if (isset($form_error)) {
                          echo(' <tr align="center">');
                          echo('  <td colspan="2" class="dataRecord"><div class="formError">'.$form_error.'</div></td>');
                          echo(' </tr>');
                         }
?>
 <tr>
  <td class="dataRecord"><b>Gateway List:</b></td>
  <td class="dataRecord">
   <input type="text" name="gwlist" id="gwlist" value="<?=$row['gwlist']?>" maxlength="255" readonly class="dataInput">
   <input type="button" name="clear_gwlist" value="Clear" class="formButton" onclick="clearObject('gwlist')"><br>
   <input type="button" name="add_gwlist" value="Add" class="formButton" onclick="addElementToObject('gwlist')"><?=print_gwlist()?>&nbsp;|&nbsp;
   <input type="button" name="end_group_gwlist" value="End Group" class="formButton" onclick="endGroupGwList('gwlist')"><br>
  </td>
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
<br>
<?=$back_link?>
