<?php
/*
 * $Id$
 * Copyright (C) 2008-2010 Voice Sistem SRL
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

 require("template/header.php");
 include("lib/db_connect.php");
 require("../../../../config/db.inc.php");
 $table=$config->table_gateways;
 $current_page="current_page_gateways";
 
 if (isset($_POST['action'])) $action=$_POST['action'];
 else if (isset($_GET['action'])) $action=$_GET['action'];
      else $action="";

 if (isset($_GET['page'])) $_SESSION[$current_page]=$_GET['page'];
 else if (!isset($_SESSION[$current_page])) $_SESSION[$current_page]=1;

#################
# start details #
#################
 if ($action=="details")
 {
  $sql = "select * from ".$table." where gwid='".$_GET['id']."'";
  $resultset = $link->queryAll($sql);
  if(PEAR::isError($resultset)) {
  	die('Failed to issue query, error message : ' . $resultset->getMessage());
  }
  $link->disconnect();
  require("lib/".$page_id.".functions.inc.php");
  require("template/".$page_id.".details.php");
  require("template/footer.php");
  exit();
 }
###############
# end details #
###############

################
# start modify #
################
 if ($action=="modify")
 {
  require("lib/".$page_id.".test.inc.php");
  if ($form_valid) {
                $sql = "update ".$table." set type='".$type."', address='".$address."', strip='".$strip."', pri_prefix='".$pri_prefix."', description='".$description."' where gwid='".$_GET['id']."'";
		$resultset = $link->prepare($sql);
		$resultset->execute();
		$resultset->free();		
                $link->disconnect();
                   }
  if ($form_valid) $action="";
   else $action="edit";
 }
##############
# end modify #
##############

##############
# start edit # 
##############
 if ($action=="edit")
 {
  $sql = "select * from ".$table." where gwid='".$_GET['id']."' limit 1";
  $resultset = $link->queryAll($sql);
  if(PEAR::isError($resultset)) {
  	die('Failed to issue query, error message : ' . $resultset->getMessage());
  }
  $link->disconnect();
  require("lib/".$page_id.".functions.inc.php");
  require("template/".$page_id.".edit.php");
  require("template/footer.php");
  exit();
 }
############
# end edit #
############

####################
# start add verify #
####################
 if ($action=="add_verify")
 {
  require("lib/".$page_id.".test.inc.php");
  if ($form_valid) {
                    $_SESSION['gateways_search_type']="";
                    $_SESSION['gateways_search_address']="";
                    $_SESSION['gateways_search_pri_prefix']="";
                    $_SESSION['gateways_search_description']="";
                    $sql = "insert into ".$table." (type, address, strip, pri_prefix, description) values ('".$type."', '".$address."', '".$strip."', '".$pri_prefix."', '".$description."')";
		    $resultset = $link->prepare($sql);
		    $resultset->execute();
		    $resultset->free();			

                    $sql = "select * from ".$table." where (1=1)";
                    $resultset = $link->queryAll($sql);
                    if(PEAR::isError($resultset)) {
                             die('Failed to issue query, error message : ' . $resultset->getMessage());
                    }	
                    $data_no=count($resultset);
                    $link->disconnect();
                    $page_no=ceil($data_no/10);
                    $_SESSION[$current_page]=$page_no;
                   }
  if ($form_valid) $action="";
   else $action="add";
 }
##################
# end add verify #
##################

#################
# start add new # 
#################
 if ($action=="add")
 {
  if ($_POST['add']=="Add") extract($_POST);
   else $strip="0";
  require("lib/".$page_id.".functions.inc.php");
  require("template/".$page_id.".add.php");
  require("template/footer.php");
  exit();
 }
###############
# end add new #
###############

################
# start delete #
################
 if ($action=="delete")
 {
  $del_id=$_GET['id'];
  $sql = "delete from ".$table." where gwid='".$del_id."'";
  $link->exec($sql);	

 if ($config->db_driver == "mysql")
	  $sql = "select * from ".$config->table_rules." where gwlist regexp '(^".$del_id."$)|(^".$del_id."[,;|])|([,;|]".$del_id."[,;|])|([,;|]".$del_id."$)'";
 else if ($config->db_driver == "pgsql")
	  $sql = "select * from ".$config->table_rules." where gwlist ~* '(^".$del_id."$)|(^".$del_id."[,;|])|([,;|]".$del_id."[,;|])|([,;|]".$del_id."$)'";

  $resultset = $link->queryAll($sql);
  if(PEAR::isError($resultset)) {
 	 die('Failed to issue query, error message : ' . $resultset->getMessage());
  }
  for($i=0;count($resultset)>$i;$i++)
  {
   $list=$resultset[$i]['gwlist'];
   // first gw
   if ($list==$del_id) $list="";
   if (strpos($list,$del_id.",")==0) $list=str_replace($del_id.",", "", $list);
   if (strpos($list,$del_id.";")==0) $list=str_replace($del_id.";", "", $list);
   // middle gw
   $list=str_replace(",".$del_id.",", "," ,$list);
   $list=str_replace(",".$del_id.";", ";" ,$list);
   $list=str_replace(";".$del_id.",", ";" ,$list);
   $list=str_replace(";".$del_id.";", ";" ,$list);
   //last gw
   $list=str_replace(",".$del_id, "" ,$list);
   $list=str_replace(";".$del_id, "" ,$list);
   if ($list!=$resultset[$i]['gwlist']) 
	{
	 $sql = "update ".$config->table_rules." set gwlist='".$list."' where ruleid='".$resultset[$i]['ruleid']."' limit 1";
         $resultset_ = $link->queryAll($sql);
         if(PEAR::isError($resultset_)) {
	         die('Failed to issue query, error message : ' . $resultset_->getMessage());
         }

	}
  }
  $link->disconnect();
 }
##############
# end delete #
##############

################
# start search #
################
 if ($action=="search")
 {
  $_SESSION[$current_page]=1;
  extract($_POST);
  if ($show_all=="Show All") {
                              $_SESSION['gateways_search_type']="";
                              $_SESSION['gateways_search_address']="";
                              $_SESSION['gateways_search_pri_prefix']="";
                              $_SESSION['gateways_search_description']="";
                             }
   else {
         $_SESSION['gateways_search_type']=$search_type;
         $_SESSION['gateways_search_address']=$search_address;
         $_SESSION['gateways_search_pri_prefix']=$search_pri_prefix;
         $_SESSION['gateways_search_description']=$search_description;
        }
 }
##############
# end search #
##############

##############
# start main #
##############
 require("lib/".$page_id.".functions.inc.php");
 require("template/".$page_id.".main.php");
 require("template/footer.php");
 exit();
############
# end main #
############
?>
