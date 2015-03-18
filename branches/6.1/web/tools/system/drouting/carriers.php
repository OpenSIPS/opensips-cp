<?php
/*
 * $Id: lists.php 287 2011-10-17 09:41:35Z untiptun $
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


 require("template/header.php");
 include("lib/db_connect.php");
 require ("../../../common/mi_comm.php");
 $table=$config->table_carriers;
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
  $sql = "select * from ".$table." where carrierid='".$_GET['carrierid']."' limit 1";
  $resultset = $link->queryAll($sql);

  if(PEAR::isError($resultset)) {
	  die('Failed to issue query, error message : ' . $resultset->getMessage());
  }
  $link->disconnect();

  $resultset[0]['useweights']   = (fmt_binary((int)$resultset[0]['flags'],4,4)) ? "Yes" : "No";
  $resultset[0]['useonlyfirst'] = (fmt_binary((int)$resultset[0]['flags'],4,3)) ? "Yes" : "No";

  $mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);
  $command="dr_carrier_status ".$_GET['carrierid'];

    for ($i=0;$i<count($mi_connectors);$i++){
        $comm_type=params($mi_connectors[$i]);
        $message=mi_command($command, $errors, $status);
    }


    $message = explode("\n",trim($message));
    for ($i=0;$i<count($message);$i++){
        preg_match('/(?:Enabled=)?([^ ]+)$/',$message[$i],$matchStatus);
	
		$resultset[0]['status'] = ($matchStatus[1]=="yes") ? "Active" : "Inactive";
		
    }

  require("template/".$page_id.".details.php");
  require("template/footer.php");
  exit();
 }
###############
# end details #
###############


#########################
# start enable carrier  #
#########################
if ($action=="enablecar"){
    $mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);
    $command="dr_carrier_status ".$_GET['carrierid']." 1";

    for ($i=0;$i<count($mi_connectors);$i++){
        $comm_type=params($mi_connectors[$i]);
        $message=mi_command($command, $errors, $status);
    }
    if (substr(trim($status),0,3)!="200")
        echo "Error while enabling carrier ".$_GET['carrierid'];
}
##################
# end enable gw  #
##################


#######################
# start disable gw    #
#######################
if ($action=="disablecar"){
    $mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);
    $command="dr_carrier_status ".$_GET['carrierid']." 0";

    for ($i=0;$i<count($mi_connectors);$i++){
        $comm_type=params($mi_connectors[$i]);
        $message=mi_command($command, $errors, $status);
    }
    if (substr(trim($status),0,3)!="200")
        echo "Error while disabling carrier ".$_GET['carrierid'];
}
##################
# end disable gw  #
##################


################
# start modify #
################
 if ($action=="modify")
 {
  require("lib/".$page_id.".test.inc.php");
  if ($form_valid) {
			$flags = bindec($useonlyfirst.$useweights);
		

            $sql = "update ".$table." set gwlist='".$gwlist."', flags= (flags | ".$flags.") & ".$flags.", state='".$state."', description='".$description."', attrs='".$attrs."' where carrierid='".$_GET['carrierid']."'";
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
  $sql = "select * from ".$table." where carrierid='".$_GET['carrierid']."' limit 1";
  $resultset = $link->queryAll($sql);
  if(PEAR::isError($resultset)) {
	  die('Failed to issue query, error message : ' . $resultset->getMessage());
  }
//  $link->disconnect();
  
  if (is_numeric((int)$resultset[$i]['flags'])) {
        $resultset[0]['useweights']   = (fmt_binary((int)$resultset[0]['flags'],3,3));
        $resultset[0]['useonlyfirst'] = (fmt_binary((int)$resultset[0]['flags'],3,2));
        $resultset[0]['enabled']      = (fmt_binary((int)$resultset[0]['flags'],3,1));
		//print_r($resultset[0]);
    }
    else{
        $resultset[0]['useweights'] = "error";
        $resultset[0]['useonlyfirst'] = "error";
        $resultset[0]['enabled'] = "error";
    }
  
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
  					$flags = bindec($enabled.$useonlyfirst.$useweights);
                    
					$_SESSION['rules_search_gwlist']="";
                    $_SESSION['rules_search_description']="";
                    
					$sql = "insert into ".$table." (carrierid, gwlist, flags, state, description,attrs) values ('".$carrierid."', '".$gwlist."', '".$flags."', '".$state."', '".$description."','".$attrs."')";

					$resultset = $link->prepare($sql);
				    $resultset->execute();
				    $resultset->free();
                    
					$sql = "select count(*) from ".$table." where (1=1)";
                    $result = $link->queryOne($sql);
                    if(PEAR::isError($resultset)) {
	                    die('Failed to issue query, error message : ' . $resultset->getMessage());
                    }	
                    $data_no=$result;
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
 if ($action=="delete"){
  $del_id = $_GET['carrierid'];
  $sql = "delete from ".$table." where carrierid='".$del_id."'";
  $result = $link->exec($sql);
  if(PEAR::isError($result)) {
	die('Failed to issue query, error message : ' . $result->getMessage());
  }

    $sql_regex = "'(^#".$del_id."(=[^,]+)?,)|(,#".$del_id."(=[^,]+)?$)|(^#".$del_id."(=[^,]+)?$)|(,#".$del_id."(=[^,]+)?,)'";

    $preg_exp1 = "'(^#".$del_id."(=[^,]+)?,)|(,#".$del_id."(=[^,]+)?$)|(^#".$del_id."(=[^,]+)?$)'";
    $preg_exp2 = "'(,#".$del_id."(=[^,]+)?,)'";

    //remove Carriers from dr_rules
    if ($config->db_driver == "mysql")
        $sql = "select ruleid,gwlist from ".$config->table_rules." where gwlist regexp ".$sql_regex;
    else if ($config->db_driver == "pgsql")
        $sql = "select ruleid,gwlist from ".$config->table_rules." where gwlist ~* ".$sql_regex;

    $resultset = $link->queryAll($sql);

    if(PEAR::isError($resultset)) {
        die('Failed to issue query, error message : ' . $resultset->getMessage());
    }
    for($i=0;count($resultset)>$i;$i++){
        $list=$resultset[$i]['gwlist'];
        if (preg_match($preg_exp1,$list))
            $list = preg_replace($sql_regex,'',$list);
        else if (preg_match($preg_exp2,$list))
            $list = preg_replace($sql_regex,',',$list);
        $sql = "update ".$config->table_rules." set gwlist='".$list."' where ruleid='".$resultset[$i]['ruleid']."' limit 1";
        $result = $link->exec($sql);
        if(PEAR::isError($result)) {
            die('Failed to issue query, error message : ' . $result->getMessage());
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
                                         $sql = "delete from ".$table." where (1=1) ".$sql_search;
                                         $link->exec($sql);
                                        }
       }
       $link->disconnect();
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
