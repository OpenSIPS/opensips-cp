<!--
 * $Id: lists.details.php,v 1.1.1.1 2006-08-30 10:43:09 bogdan Exp $
 -->

<table width="400" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td class="dataTitle">Detailed view for List #<?=$_GET['id']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>List ID:</b> <?=$row['id']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Gateway List:</b> <?=$row['gwlist']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Description:</b> <?=$row['description']?></td>
 </tr>
 <tr height="10">
  <td class="dataTitle"><img src="images/spacer.gif" width="5" height="5"></td>
 </tr>
</table>
<br>
<?=$back_link?>
