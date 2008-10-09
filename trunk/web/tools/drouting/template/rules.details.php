<!--
 * $Id: rules.details.php,v 1.1.1.1 2006-08-30 10:43:09 bogdan Exp $
 -->

<?php
 if ($row['gwlist']=="") $gwlist='<img src="images/inactive.gif" alt="No GW List">';
  else $gwlist=parse_gwlist($row['gwlist']);
?>
<table width="400" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td class="dataTitle">Detailed view for Rule #<?=$_GET['id']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Rule ID:</b> <?=$row['ruleid']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Group ID:</b> 
  <?php 
   if ($config->group_id_method=="static") get_groups($row['groupid']);
   if ($config->group_id_method=="dynamic") echo($row['groupid']);
  ?>
  </td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Prefix:</b> <?=$row['prefix']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Time Recurrence:</b> <?=parse_timerec($row['timerec'],1)?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Priority:</b> <?=$row['priority']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Route ID:</b> <?=$row['routeid']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Gateway List:</b> <?=$gwlist?></td>
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