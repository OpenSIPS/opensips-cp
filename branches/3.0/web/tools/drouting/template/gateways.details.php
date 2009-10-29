<!--
 * $Id$
-->
 
<table width="400" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td class="dataTitle">Detailed view for Gateway #<?=$_GET['id']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Gateway ID:</b> <?=$resultset[0]['gwid']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Type:</b> <?=get_type($resultset[0]['type'])?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Address:</b> <?=$resultset[0]['address']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Strip:</b> <?=$resultset[0]['strip']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>PRI Prefix:</b> <?=$resultset[0]['pri_prefix']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Description:</b> <?=$resultset[0]['description']?></td>
 </tr>
 <tr height="10">
  <td class="dataTitle"><img src="images/spacer.gif" width="5" height="5"></td>
 </tr>
</table>
<br>
<?php
 if (strpos($_SERVER['HTTP_REFERER'],"rules.php")!==false)
  echo('<a href="rules.php" class="backLink">Go Back</a>&nbsp;|&nbsp;');
?>
<?=$back_link?>
