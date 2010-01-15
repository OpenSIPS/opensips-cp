<?php
/*
 * $Id: charts.main.php 61 2009-06-03 13:53:26Z iulia_bublea $
 */
?>

<form action="<?=$page_name?>" method="post">
<table width="100%" cellspacing="2" cellpadding="2" border="0">
 <tr>
  <td align="center" class="Title">Click a statistic to see its chart</td>
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
  $from_time=date('j M Y, H:i:s',$result[$j]['time']);
  ?>
   <tr>
    <td class="rowOdd">
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
      <tr><td><img src="images/spacer.gif"></td></tr>
     <?php
    }
  $i++;
 }
}
$link->disconnect();
?>
 <tr>
  <td colspan="2" class="Title"><img src="images/spacer.gif" width="5" height="5"></td>
 </tr>
</table>
<br>
<?php
 if ((!$_read_only) && ($data_no!=0)) echo('<input type="submit" name="flush" value="Clear Statistics Logs" class="Button">');
?>
</form>
