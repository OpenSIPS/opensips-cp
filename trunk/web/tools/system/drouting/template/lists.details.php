<!--
 * $Id: lists.details.php 57 2009-06-03 13:48:46Z iulia_bublea $
 -->

<table width="400" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td class="dataTitle">Detailed view for List #<?=$_GET['id']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>List ID:</b> <?=$resultset[0]['id']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Gateway List:</b> <?=$resultset[0]['gwlist']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Description:</b> <?=$resultset[0]['description']?></td>
 </tr>
 <tr height="10">
  <td class="dataTitle"><img src="images/spacer.gif" width="5" height="5"></td>
 </tr>
</table>
<br>
<?=$back_link?>
