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
  <td align="center" class="smonitorTitle">Click a statistic to see its chart</td>
 </tr>
<?php
$sql = "SELECT DISTINCT name FROM ".$table." WHERE (1=1) AND box_id=".$box_id." ORDER BY name ASC";
$resultset = $link->queryAll($sql);
if(PEAR::isError($resultset)) {
         die('Failed to issue query, error message : ' . $resultset->getMessage());
}
$data_no=count($resultset);
if ($data_no==0) echo ('<tr><td class="rowEven" align="center"><br>'.$no_result.'<br><br></td></tr>');
else
{
 $i=0;
 $sampling_time=get_config_var('sampling_time',$box_id);
 for($j=0;count($resultset)>$j;$j++)
 {
  $stat_chart=false;
  $stat=$resultset[$j]['name'];
  $stat_img="images/variable.gif";
  if ($_SESSION["stat_open"][$i]=="yes") $stat_chart=true;
  $res="SELECT * FROM ".$table." WHERE name='".$stat."' AND box_id=".$box_id." ORDER BY time ASC LIMIT 1";
  $result = $link->queryAll($res); 
  if(PEAR::isError($result)) {
          die('Failed to issue query, error message : ' . $result->getMessage());
  }
  $from_time=date('j M Y, H:i:s',$result[0]['time']);
  echo $result['time'];
  ?>
   <tr>
    <td class="searchRecord">
     <div id="stat_<?=$stat?>" class="Data" onMouseOver="this.style.cursor='pointer'" onClick="document.location.href='<?=$page_name?>?stat_id=<?=$i?>'">
      <img src="<?=$stat_img?>" width="16" height="14"> <b><?=$stat?></b> - monitored from <?=$from_time?> every <?=$sampling_time?> minute(s)
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
$link->disconnect();
?>
 <tr>
  <td colspan="2" class="smonitorTitle"><img src="../../../images/share/spacer.gif" width="5" height="5"></td>
 </tr>
</table>
<br>
<?php
 if ((!$_read_only) && ($data_no!=0)) echo('<input type="submit" name="flush" value="Clear Statistics Logs" class="formButton">');
?>
</form>
