<!--
 * $Id$
 -->

<table width="400" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td class="dataTitle">Detailed view for '<?=$_GET['id']?>'</td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Username:</b> <?=$row['username']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Domain:</b> <?=$row['domain']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Group ID:</b> <?=$row['groupid']?></td>
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