<?php
/*
 * $Id$
 */

 require("template/header.php");
 $table=$config->table_lists;
 $current_page="current_page_lists";
 
 if (isset($_POST['action'])) $action=$_POST['action'];
 else if (isset($_GET['action'])) $action=$_GET['action'];
      else $action="";

 if (isset($_GET['page'])) $_SESSION[$current_page]=$_GET['page'];
 else if (!isset($_SESSION[$current_page])) $_SESSION[$current_page]=1;
 
 require("lib/".$page_id.".functions.inc.php");

#################
# start details #
#################
 if ($action=="details")
 {
  db_connect();
  $result=mysql_query("select * from ".$table." where id='".$id."' limit 1") or die(mysql_error());
  $row=mysql_fetch_array($result);
  db_close();
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
                    mysql_query("update ".$table." set gwlist='".$gwlist."', description='".$description."' where id='".$_GET['id']."' limit 1") or die(mysql_error());
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
  $result=mysql_query("select * from ".$table." where id='".$_GET['id']."' limit 1") or die(mysql_error());
  $row=mysql_fetch_array($result);
  db_close();
  require("lib/".$page_id.".add.edit.js");
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
                    $_SESSION['rules_search_gwlist']="";
                    $_SESSION['rules_search_description']="";
                    db_connect();
                    mysql_query("insert into ".$table." (gwlist, description) values ('".$gwlist."', '".$description."')") or die(mysql_error());
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
  require("lib/".$page_id.".add.edit.js");
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
  mysql_query("delete from ".$table." where id='".$_GET['id']."' limit 1") or die(mysql_error());
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
  if ($_POST['show_all']=="Show All") {
                                       $_SESSION['rules_search_gwlist']="";
                                       $_SESSION['rules_search_description']="";
                                       $sql_search="";
                                      }
  else {
        extract($_POST);
        if ($search=="Search") {
                                $_SESSION['rules_search_gwlist']=$search_gwlist;
                                $_SESSION['rules_search_description']=$search_description;
                               }
        if ($delete=="Delete Matching") {
                                         $sql_search="";
                                         $search_gwlist=$_SESSION['rules_search_gwlist'];
                                         if ($search_gwlist!="") $sql_search.=" and gwlist like '%".$search_gwlist."%'";
                                         $search_description=$_SESSION['rules_search_description'];
                                         if ($search_description!="") $sql_search.=" and description like '%".$search_description."%'";
                                         db_connect();
                                         mysql_query("delete from ".$table." where 1 ".$sql_search) or die(mysql_error());
                                         db_close();
                                        }
       }
 }
##############
# end search #
##############

##############
# start main #
##############
 require("template/".$page_id.".main.php");
 require("template/footer.php");
 exit();
############
# end main #
############
?>
