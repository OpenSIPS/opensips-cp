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
 
 require("../../../common/cfg_comm.php");
 require("template/header.php");
 require("lib/".$page_id.".main.js");
 require("../../../common/mi_comm.php");
 require("../../../../config/db.inc.php");
 session_load();
 
 csrfguard_validate();

 $table=get_settings_value("table_trace");
 $current_page="current_page_tracer";
 
 include("lib/db_connect.php");

 if (isset($_POST['action'])) $action=$_POST['action'];
 else if (isset($_GET['action'])) $action=$_GET['action'];
      else $action="";
 
 if (isset($_GET['page'])) $_SESSION[$current_page]=$_GET['page'];
 else if (!isset($_SESSION[$current_page])) $_SESSION[$current_page]=1;

 if (isset($_POST['toggle'])) {
	 $action="toggle";
	 $toggle_button=$_POST['toggle'];
 }

 
if ($action=="toggle") {

	if ($toggle_button=="Enable") {	
		$sip_trace	= "on" ;
	} else if ($toggle_button=="Disable") {	
		$sip_trace	= "off" ;
	}

	$command="trace";
	$mi_connectors=get_proxys_by_assoc_id(get_settings_value('talk_to_this_assoc_id'));

	for ($i=0;$i<count($mi_connectors);$i++){	
		mi_command( $command, array("mode"=>$sip_trace) ,$mi_connectors[$i],$errors);
	}

}


// get the current status of the tracing engine
$mi_connectors=get_proxys_by_assoc_id(get_settings_value('talk_to_this_assoc_id'));
$msg = mi_command( "trace", NULL, $mi_connectors[0], $errors);
if (!is_null($msg)) {
	$state = $msg['global'];
} else
	$state="off";

if ($state == "on")
	$toggle_button = "Disable";
else if ($state == "off")
	$toggle_button = "Enable";
else 
	$toggle_button=null;


################
# start search #
################

if ($action=="search")
 {
  $_SESSION[$current_page]=1;
  extract($_POST);

  if ($delete=="Delete Listed") {
  		
  		  	$_SESSION['delete'] = "1";
  }
  
  
  if ($show_all=="Show All") {
                              unset($_SESSION['detailed_callid']);
                              $_SESSION['detailed_callid']=array();
                              $_SESSION['tracer_search_regexp']="";
                              $_SESSION['tracer_search_callid']="";
                              $_SESSION['tracer_search_start']="";
                              $_SESSION['tracer_search_end']="";
                              $_SESSION['tracer_search_traced_user']=""; 		
  					       }
   else {
         unset($_SESSION['detailed_callid']);
         $_SESSION['detailed_callid']=array();
         $_SESSION['tracer_search_regexp']=$search_regexp;
         $_SESSION['tracer_search_callid']=$search_callid;
         $_SESSION['tracer_search_traced_user']=$search_traced_user;
         if ($set_start=="set") $_SESSION['tracer_search_start']=$start_year."-".$start_month."-".$start_day." ".$start_hour.":".$start_minute.":".$start_second;
          else $_SESSION['tracer_search_start']="";
         if ($set_end=="set") $_SESSION['tracer_search_end']=$end_year."-".$end_month."-".$end_day." ".$end_hour.":".$end_minute.":".$end_second;
          else $_SESSION['tracer_search_end']="";
         if ($set_grouped=="set") $_SESSION['grouped_results']=true;
          else $_SESSION['grouped_results']=false;
        }
 }
##############
# end search #
##############
 
 if (isset($_GET['id']))
  if (in_array($_GET['id'],$_SESSION['detailed_callid'])) {
                                                           $key=array_search($_GET['id'],$_SESSION['detailed_callid']);
                                                           unset($_SESSION['detailed_callid'][$key]);
                                                          }
   else $_SESSION['detailed_callid'][]=$_GET['id'];

   
 require("template/".$page_id.".main.php");
 require("template/footer.php");
 exit();
 
?>
