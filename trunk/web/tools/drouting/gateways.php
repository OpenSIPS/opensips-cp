<?php
/*
 * $Id$
 */
 
 require("template/header.php");
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
  db_connect();
  $result=mysql_query("select * from ".$table." where gwid='".$_GET['id']."' limit 1") or die(mysql_error());
  $row=mysql_fetch_array($result);
  db_close();
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
                    db_connect();
                    mysql_query("update ".$table." set type='".$type."', address='".$address."', strip='".$strip."', pri_prefix='".$pri_prefix."', description='".$description."' where gwid='".$_GET['id']."' limit 1") or die(mysql_error());
                    db_close();
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
  db_connect();
  $result=mysql_query("select * from ".$table." where gwid='".$_GET['id']."' limit 1") or die(mysql_error());
  $row=mysql_fetch_array($result);
  db_close();
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
                    db_connect();
                    mysql_query("insert into ".$table." (type, address, strip, pri_prefix, description) values ('".$type."', '".$address."', '".$strip."', '".$pri_prefix."', '".$description."')") or die(mysql_error());
                    $result=mysql_query("select * from ".$table." where 1") or die(mysql_error());
                    $data_no=mysql_num_rows($result);
                    db_close();
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
  db_connect();
  $del_id=$_GET['id'];
  mysql_query("delete from ".$table." where gwid='".$del_id."' limit 1") or die(mysql_error());
  $result=mysql_query("select * from ".$config->table_rules." where gwlist regexp '(^".$del_id."$)|(^".$del_id."[,;|])|([,;|]".$del_id."[,;|])|([,;|]".$del_id."$)'") or die(mysql_error());
  while($row=mysql_fetch_array($result))
  {
   $list=$row['gwlist'];
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
   if ($list!=$row['gwlist']) mysql_query("update ".$config->table_rules." set gwlist='".$list."' where ruleid='".$row['ruleid']."' limit 1") or die(mysql_error());
  }
  db_close();
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