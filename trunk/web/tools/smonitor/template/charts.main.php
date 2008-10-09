<?php
/*
 * $Id: charts.main.php,v 1.3 2007-04-18 07:39:47 bogdan Exp $
 */
?>

<form action="<?=$page_name?>" method="post">
<table width="100%" cellspacing="2" cellpadding="2" border="0">
 <tr>
  <td align="center" class="Title">Click a statistic to see its chart</td>
 </tr>
<?php
db_connect();
$result=mysql_query("SELECT DISTINCT name FROM ".$table." WHERE 1 AND box_id=".$box_id." ORDER BY name ASC") or die(mysql_error());
$data_no=mysql_num_rows($result);
if ($data_no==0) echo ('<tr><td class="rowEven" align="center"><br>'.$no_result.'<br><br></td></tr>');
else
{
 $i=0;
 $sampling_time=get_config_var('sampling_time',$box_id);
 while($row=mysql_fetch_array($result))
 {
  $stat_chart=false;
  $stat=$row['name'];
  $stat_img="images/variable.gif";
  if ($_SESSION["stat_open"][$i]=="yes") $stat_chart=true;
  $res=mysql_query("SELECT * FROM ".$table." WHERE name='".$stat."' AND box_id=".$box_id." ORDER BY time ASC LIMIT 1") or die(mysql_error());
  $r=mysql_fetch_array($res);
  $from_time=date('j M Y, H:i:s',$r['time']);
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
db_close();
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