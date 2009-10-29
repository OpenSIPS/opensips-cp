<?php
/*
 * $Id$
 */

 require("../../../config/tools/smonitor/local.inc.php");
 require("../../../config/db.inc.php");
 require("lib/functions.inc.php");
 
 session_start(); 
 require("lib/put_select_boxes.php");
 
 $box_id=get_box_id($current_box); 	

 require("template/header.php");
 $table=$config->table_monitored;
 
 if ($_POST['set']!=null)
 {
  extract($_POST);
  db_connect();
  $row=mysql_fetch_array(mysql_query("SELECT * FROM ".$table." WHERE name='sampling_time' AND box_id=".$box_id." LIMIT 1")) or die(mysql_error());
  if ($sampling_time!=$row['extra'])
  {
   mysql_query("UPDATE ".$table." SET extra='".$sampling_time."' WHERE name='sampling_time' AND box_id=".$box_id." LIMIT 1") or die(mysql_error());
   mysql_query("TRUNCATE TABLE ".$config->table_monitoring) or die(mysql_error());
  }
  mysql_query("UPDATE ".$table." SET extra='".$chart_size."' WHERE name='chart_size' AND box_id=".$box_id." LIMIT 1") or die(mysql_error());
  if ($chart_history=="auto") $chart_history_value="auto";
  mysql_query("UPDATE ".$table." SET extra='".$chart_history_value."' WHERE name='chart_history' AND box_id=".$box_id." LIMIT 1") or die(mysql_error());
  db_close();
 }
 
 db_connect();
 $result=mysql_query("SELECT * FROM ".$table." WHERE extra!='' AND box_id=".$box_id) or die(mysql_error());
 while($row=mysql_fetch_array($result))
 {
  if ($row['name']=="sampling_time") $sampling_time=$row['extra'];
  if ($row['name']=="chart_size") $chart_size=$row['extra'];
  if ($row['name']=="chart_history") $chart_history=$row['extra'];
 }
 if ($sampling_time==null) {
                            $sampling_time=$config->sampling_time;
                            mysql_query("INSERT INTO ".$table." (name,extra,box_id) VALUES ('sampling_time','".$sampling_time."',".$box_id.")") or die(mysql_error());
                           }
 if ($chart_size==null) {
                         $chart_size=$config->chart_size;
                         mysql_query("INSERT INTO ".$table." (name,extra,box_id) VALUES ('chart_size','".$chart_size."',".$box_id.")") or die(mysql_error());
                        }
 if ($chart_history==null) {
                            $chart_history=$config->chart_history;
                            mysql_query("INSERT INTO ".$table." (name,extra,box_id) VALUES ('chart_history','".$chart_history."',".$box_id.")") or die(mysql_error());
                           }
 db_close();
 
 require("template/".$page_id.".main.php");
 require("template/footer.php");
 exit();
 
?>
