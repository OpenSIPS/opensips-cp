<!--
 * $Id$
 -->

<?php
 if ($resultset[0]['gwlist']=="") $gwlist='<img src="images/inactive.gif" alt="No GW List">';
  else if ( preg_match('/[#][0-9]+/',$resultset[0]['gwlist'])) $gwlist=parse_list($resultset[0]['gwlist']); 
  else $gwlist=parse_gwlist($resultset[0]['gwlist']);
?>
<table width="400" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td class="dataTitle">Detailed view for Rule #<?=$_GET['id']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Rule ID:</b> <?=$resultset[0]['ruleid']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Group ID:</b> 
  <?php 
   if ($config->group_id_method=="static") get_groups($resultset[0]['groupid']);
   if ($config->group_id_method=="dynamic") echo($resultset[0]['groupid']);
  ?>
  </td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Prefix:</b> <?=$resultset[0]['prefix']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Time Recurrence:</b> <?=parse_timerec($resultset[0]['timerec'],1)?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Priority:</b> <?=$resultset[0]['priority']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Route ID:</b> <?=$resultset[0]['routeid']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Gateway List:</b> <?=$gwlist?></td>
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
