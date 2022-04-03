<?php
/*
 * Copyright (C) 2011 OpenSIPS Project
 *
 * This file is part of opensips-cp, a free Web Control Panel Application for 
 * OpenSIPS SIP server.
 *
 * opensips-cp is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * opensips-cp is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

?>

<form action="<?=$page_name?>" method="post">
<table width="100%" cellspacing="2" cellpadding="2" border="0">
 <tr>
  <td align="center" class="mainTitle">Click a statistic to see its chart</td>
 </tr>
</table>

<table width="100%" class="ttable" cellspacing="2" cellpadding="2" border="0">
<?php 

$sql = "SELECT DISTINCT name FROM ".$table." WHERE box_id = ? ORDER BY name ASC";
$stm = $link->prepare($sql);
if ($stm->execute(array($box_id)) === false)
	die('Failed to issue query, error message : ' . print_r($stm->errorInfo(), true));
$resultset = $stm->fetchAll(PDO::FETCH_ASSOC);
$data_no=count($resultset);
if ($data_no==0) echo ('<tr><td class="rowEven" align="center"><br>'.$no_result.'<br><br></td></tr>');
else
{
 $i=0;

 $sampling_time=get_settings_value('sampling_time', $box_id);
 
 foreach(get_settings_value("groups", $box_id) as $key=>$group_attr) {
  $boxes= [];
  $groupElements = $group_attr['stats'];
  $scale = $group_attr['scale'];
  $gName = "";
  $matches = false;
  $group = [];
  foreach ($groupElements as $g) {
    $boxes[] = $g['box_id'];
   if( preg_match("/^\/.+\/[a-z]*$/i",$g['name'])) {
     foreach ($monitored_stats as $name => $id) {
       if (preg_match($g['name'], $name, $matches))
           $group[] = $name;
     }
   }
   else $group[] = $g['name'];
   $sql = "REPLACE INTO ".$config->table_monitored." (name, extra, box_id) VALUES (?, '', ?)";
    $stm = $link->prepare($sql);
    if ($stm->execute(array($g['name'], $g['box_id'])) === false)
		die('Failed to issue query, error message : ' . print_r($stm->errorInfo(), true));
  
  }

 foreach($group as $gr) {
   $gName.=$gr.", ";
 }
 $stat_chart=false;
 $stat_img="../../../images/share/chart.png";
 if ($_SESSION["group_open"][$key]=="yes") $stat_chart=true;
 $sql = "SELECT * FROM ".$table." WHERE name = ? AND box_id = ? ORDER BY time ASC LIMIT 1";
 $stm = $link->prepare($sql);
 if ($stm->execute(array($group[0], $box_id)) === false)
   die('Failed to issue query, error message : ' . print_r($stm->errorInfo(), true));
 $result = $stm->fetchAll(PDO::FETCH_ASSOC);
 $from_time=date('j M Y, H:i:s',$result[0]['time']);
 echo $result['time'];
 ?>
  <tr>
   <td class="searchRecord">
    <div id="stat_<?=$gName?>" class="Data"  onMouseOver="this.style.cursor='pointer'" onClick="document.location.href='<?=$page_name?>?group_id=<?=$key?>'">
     <img src="<?=$stat_img?>"><b><?=$gName?></b> - monitored from <?=$from_time?> every <?=$sampling_time?> minute(s)
    </div>
   </td>
  </tr>
  <?php
   if ($stat_chart)
   { 
    ?>
     <tr><td class="rowEven"><?php show_graphs($group,$boxes,$scale); ?></td></tr>
     <tr><td><img src="../../../images/share/spacer.gif"></td></tr>
   <?php 
     
   }
}
 for($j=0;count($resultset)>$j;$j++)
 {
  $stat_chart=false;
  $stat=$resultset[$j]['name'];
  $stat_img="../../../images/share/chart.png";
  if ($_SESSION["stat_open"][$i]=="yes") $stat_chart=true;
  $sql = "SELECT * FROM ".$table." WHERE name = ? AND box_id = ? ORDER BY time ASC LIMIT 1";
  $stm = $link->prepare($sql);
  if ($stm->execute(array($stat, $box_id)) === false)
    die('Failed to issue query, error message : ' . print_r($stm->errorInfo(), true));
  $result = $stm->fetchAll(PDO::FETCH_ASSOC);
  $from_time=date('j M Y, H:i:s',$result[0]['time']);
  echo $result['time'];
  ?>
   <tr>
    <td class="searchRecord">
     <div id="stat_<?=$stat?>" class="Data"  onMouseOver="this.style.cursor='pointer'" onClick="document.location.href='<?=$page_name?>?stat_id=<?=$i?>'">
      <img src="<?=$stat_img?>"><b><?=$stat?></b> - monitored from <?=$from_time?> every <?=$sampling_time?> minute(s)
     </div>
    </td>
   </tr>
   <?php
    if ($stat_chart)
    {
     ?>
      <tr><td class="rowEven"><?php show_graph($stat,$box_id); ?></td></tr>
      <tr><td><img src="../../../images/share/spacer.gif"></td></tr>
     <?php
    }
  $i++;
 }
}
?>
 </tr>
</table>
<br>
<?php
 if ((!$_read_only) && ($data_no!=0)) echo('<input type="submit" name="flush" value="Clear Statistics Logs" class="formButton">');
?>
</form>
