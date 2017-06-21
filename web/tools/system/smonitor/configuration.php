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


 require("../../../../config/tools/system/smonitor/local.inc.php");
 require("lib/functions.inc.php");
 include("lib/db_connect.php"); 
 session_start(); 
 require("lib/put_select_boxes.php");
 
 $box_id=get_box_id($current_box); 	

 require("template/header.php");
 $table=$config->table_monitored;
 if ($_POST['set']!=null)
 {
  extract($_POST);
  $sql = "SELECT * FROM ".$table." WHERE name='sampling_time' AND box_id=".$box_id." LIMIT 1";
  $resultset = $link->queryAll($sql);
  if(PEAR::isError($resultset)) {
          die('Failed to issue query, error message : ' . $resultset->getMessage());
  }
  if ($sampling_time!=$resultset[0]['extra'])
  {
   $sql =  "UPDATE ".$table." SET extra='".$sampling_time."' WHERE name='sampling_time' AND box_id=".$box_id." LIMIT 1";
   $resultset_ = $link->prepare($sql);
   $resultset_->execute();
   $resultset_->free();	

   $sql = "TRUNCATE TABLE ".$config->table_monitoring;
   $resultset_d = $link->prepare($sql);
   $resultset_d->execute();
   $resultset_d->free();
  }

  $sql = "UPDATE ".$table." SET extra='".$chart_size."' WHERE name='chart_size' AND box_id=".$box_id." LIMIT 1";
  $result = $link->prepare($sql);
  $result->execute();
  $result->free();
 
  if ($chart_history=="auto") $chart_history_value="auto";
  $sql = "UPDATE ".$table." SET extra='".$chart_history_value."' WHERE name='chart_history' AND box_id=".$box_id." LIMIT 1";
  $result_ = $link->prepare($sql);
  $result_->execute();
  $result_->free();

  $link->disconnect();

 }
 
 $sql = "SELECT * FROM ".$table." WHERE extra!='' AND box_id=".$box_id;
 $resultset = $link->queryAll($sql);
 if(PEAR::isError($resultset)) {
          die('Failed to issue query, error message : ' . $resultset->getMessage());
 }
 for($i=0;count($resultset)>$i;$i++)
 {
  if ($resultset[$i]['name']=="sampling_time") $sampling_time=$resultset[$i]['extra'];
  if ($resultset[$i]['name']=="chart_size") $chart_size=$resultset[$i]['extra'];
  if ($resultset[$i]['name']=="chart_history") $chart_history=$resultset[$i]['extra'];
 }
 if ($sampling_time==null) {
                            $sampling_time=$config->sampling_time;
			    $sql = "INSERT INTO ".$table." (name,extra,box_id) VALUES ('sampling_time','".$sampling_time."',".$box_id.")";
			    $resultset = $link->prepare($sql);
			    $resultset->execute();
 			    $resultset->free();
                           }
 if ($chart_size==null) {
                         $chart_size=$config->chart_size;
                         $sql = "INSERT INTO ".$table." (name,extra,box_id) VALUES ('chart_size','".$chart_size."',".$box_id.")";
			 $resultset = $link->prepare($sql);
			 $resultset->execute();
		  	 $resultset->free();
                        }
 if ($chart_history==null) {
                            $chart_history=$config->chart_history;
                            $sql = "INSERT INTO ".$table." (name,extra,box_id) VALUES ('chart_history','".$chart_history."',".$box_id.")";
			    $resultset = $link->prepare($sql);
			    $resultset->execute();
			    $resultset->free();
                           }

 $link->disconnect();

 require("template/".$page_id.".main.php");
 require("template/footer.php");
 exit();
 
?>
