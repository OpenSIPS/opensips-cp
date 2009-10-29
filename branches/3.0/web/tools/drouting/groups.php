<?php
/*
 * $Id$
 */
 
 require("template/header.php");
 include("lib/db_connect.php");
 $table=$config->table_groups;
 $current_page="current_page_groups";
 
 if (isset($_POST['action'])) $action=$_POST['action'];
 else if (isset($_GET['action'])) $action=$_GET['action'];
      else $action="";

 if (isset($_GET['page'])) $_SESSION[$current_page]=$_GET['page'];
 else if (!isset($_SESSION[$current_page])) $_SESSION[$current_page]=1;

 if (isset($_GET['id'])) {
                          $temp=explode("@",$_GET['id']);
                          $id_username=$temp[0];
                          $id_domain=$temp[1];
                         }

#################
# start details #
#################
 if ($action=="details")
 {
  $sql = "select * from ".$table." where username='".$id_username."' and domain='".$id_domain."' limit 1";
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
                    $sql = "update ".$table." set username='".$username."', domain='".$domain."', groupid='".$groupid."', description='".$description."' where username='".$id_username."' and domain='".$id_domain."'";
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
  $sql = "select * from ".$table." where username='".$id_username."' and domain='".$id_domain."' limit 1";
  $resultset = $link->queryAll($sql);
  if(PEAR::isError($resultset)) {
 	 die('Failed to issue query, error message : ' . $resultset->getMessage());
  }
  $link->disconnect();
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
                    $_SESSION['groups_search_username']="";
                    $_SESSION['groups_search_domain']="";
                    $_SESSION['groups_search_groupid']="";
                    $_SESSION['groups_search_description']="";
                    $sql = "insert into ".$table." (username, domain, groupid, description) values ('".$username."', '".$domain."', '".$groupid."', '".$description."')";
		    $resultset = $link->prepare($sql);
		    $resultset->execute();
		    $resultset->free();	
                    $link->disconnect();
                    $page_no=1;
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
         $groupid="0";
         $domain=$config->default_domain;
        }
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
  $sql = "delete from ".$table." where username='".$id_username."' and domain='".$id_domain."'";
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
  extract($_POST);
  if ($show_all=="Show All") {
                              $_SESSION['groups_search_username']="";
                              $_SESSION['groups_search_domain']="";
                              $_SESSION['groups_search_groupid']="";
                              $_SESSION['groups_search_description']="";
                             }
   else {
         $_SESSION['groups_search_username']=$search_username;
         $_SESSION['groups_search_domain']=$search_domain;
         $_SESSION['groups_search_groupid']=$search_groupid;
         $_SESSION['groups_search_description']=$search_description;
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
