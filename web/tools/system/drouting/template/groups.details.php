<!--
 * $Id: groups.details.php 57 2009-06-03 13:48:46Z iulia_bublea $
 -->

<table width="400" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td class="dataTitle">Detailed view for '<?=$_GET['id']?>'</td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Username:</b> <?=$resultset[0]['username']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Domain:</b> <?=$resultset[0]['domain']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Group ID:</b> <?=$resultset[0]['groupid']?></td>
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
