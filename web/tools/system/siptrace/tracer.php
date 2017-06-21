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
 
 require("template/header.php");
 require("lib/".$page_id.".main.js");
 include("lib/db_connect.php");
 
 require("../../../../config/tools/system/siptrace/local.inc.php");
 require("../../../common/mi_comm.php");
 require("../../../common/cfg_comm.php");
 require("../../../../config/db.inc.php");

 global $config;	
 $table=$config->table_trace;
 $current_page="current_page_tracer";
 
 if (isset($_POST['action'])) $action=$_POST['action'];
 else if (isset($_GET['action'])) $action=$_GET['action'];
      else $action="";
 
 if (isset($_GET['page'])) $_SESSION[$current_page]=$_GET['page'];
 else if (!isset($_SESSION[$current_page])) $_SESSION[$current_page]=1;
 
 
if ($action=="toggle") {

	$toggle_button=	$_GET['toggle_button'];
	
	if ($toggle_button=="enable") {	
		$sip_trace	= "on" ;
	} else if ($toggle_button=="disable") {	
		$sip_trace	= "off" ;
	}

	$command="sip_trace"." ".$sip_trace;
	$mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);

	for ($i=0;$i<count($mi_connectors);$i++){	
		mi_command($command,$mi_connectors[$i],$errors,$status);
	}

}


// get the current status of the tracing engine
$mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);
$msg = mi_command( "sip_trace", $mi_connectors[0], $errors, $status);
$msg = json_decode($msg, TRUE);
$state = $msg['global'];

if ($state == "on")
	$toggle_button = "disable";
else if ($state == "off")
	$toggle_button = "enable";
else 
	$toggle_button=null;


################
# start search #
################

if ($action=="search")
 {
  $_SESSION[$current_page]=1;
  extract($_POST);

  if ($delete=="Delete") {
  		
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
