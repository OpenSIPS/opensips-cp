<?php
/*
 * $Id$
 */

 require("template/header.php");
 $table=$config->table_rules;
 $current_page="current_page_rules";
 
 if (isset($_POST['action'])) $action=$_POST['action'];
 else if (isset($_GET['action'])) $action=$_GET['action'];
      else $action="";

 if (isset($_GET['page'])) $_SESSION[$current_page]=$_GET['page'];
 else if (!isset($_SESSION[$current_page])) $_SESSION[$current_page]=1;
 
 require("lib/".$page_id.".functions.inc.php");
 include("lib/db_connect.php");
#################
# start details #
#################
 if ($action=="details")
 {

  $sql = "select * from ".$table." where ruleid='".$_GET['id']."' limit 1";
  $resultset = $link->queryAll($sql);
  if(PEAR::isError($resultset)) {
	  die('Failed to issue query, error message : ' . $resultset->getMessage());
  }
  $link->disconnect();
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
                    $sql = "update ".$table." set groupid='".$groupid."', prefix='".$prefix."', timerec='".$timerec."', priority='".$priority."', routeid='".$routeid."', gwlist='".$gwlist."', description='".$description."' where ruleid='".$_GET['id']."'";
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
  $sql = "select * from ".$table." where ruleid='".$_GET['id']."' limit 1";
  $resultset = $link->queryAll($sql);
  if(PEAR::isError($resultset)) {
	  die('Failed to issue query, error message : ' . $resultset->getMessage());
  }  
  $link->disconnect();
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
                    $_SESSION['rules_search_groupid']="";
                    $_SESSION['rules_search_prefix']="";
                    $_SESSION['rules_search_priority']="";
                    $_SESSION['rules_search_routeid']="";
                    $_SESSION['rules_search_gwlist']="";
                    $_SESSION['rules_search_description']="";
                    $sql = "insert into ".$table." (groupid, prefix, timerec, priority, routeid, gwlist, description) values ('".$groupid."', '".$prefix."', '".$timerec."', '".$priority."', '".$routeid."', '".$gwlist."', '".$description."')";
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
   else {
         $priority="0";
         $routeid="0";
        }
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
  $sql = "delete from ".$table." where ruleid='".$_GET['id']."'";
  $link->exec($sql);
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
  if ($_POST['show_all']=="Show All") {
                                       $_SESSION['rules_search_groupid']="";
                                       $_SESSION['rules_search_prefix']="";
                                       $_SESSION['rules_search_priority']="";
                                       $_SESSION['rules_search_routeid']="";
                                       $_SESSION['rules_search_gwlist']="";
                                       $_SESSION['rules_search_description']="";
                                       $sql_search="";
                                      }
  else {
        extract($_POST);
        if ($search=="Search") {
                                $_SESSION['rules_search_groupid']=$search_groupid;
                                $_SESSION['rules_search_prefix']=$search_prefix;
                                $_SESSION['rules_search_priority']=$search_priority;
                                $_SESSION['rules_search_routeid']=$search_routeid;
                                $_SESSION['rules_search_gwlist']=$search_gwlist;
                                $_SESSION['rules_search_description']=$search_description;
                               }
        if ($delete=="Delete Matching") {
                                         $sql_search="";
                                         $search_groupid=$_SESSION['rules_search_groupid'];
                                         if ($search_groupid!="") $sql_search.=" and groupid like '%".$search_groupid."%'";
                                         $search_prefix=$_SESSION['rules_search_prefix'];
                                         if ($search_prefix!="") {
                                                                  $pos=strpos($search_prefix,"*");
                                                                  if ($pos===false) $sql_search.=" and prefix='".$search_prefix."'";
                                                                   else $sql_search.=" and prefix like '".str_replace("*","%",$search_prefix)."'";
                                                                 }
                                         $search_priority=$_SESSION['rules_search_priority'];
                                         if ($search_priority!="") $sql_search.=" and priority='".$search_priority."'";
                                         $search_routeid=$_SESSION['rules_search_routeid'];
                                         if ($search_routeid!="") $sql_search.=" and routeid='".$search_routeid."'";
                                         $search_gwlist=$_SESSION['rules_search_gwlist'];
                                         if ($search_gwlist!="") $sql_search.=" and gwlist like '%".$search_gwlist."%'";
                                         $search_description=$_SESSION['rules_search_description'];
                                         if ($search_description!="") $sql_search.=" and description like '%".$search_description."%'";
                                         $sql = "delete from ".$table." where (1=1) ".$sql_search;
					 $link->exec($sql);
					 $link->disconnect();
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
