<?php
/*
* $Id$
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

require("../../../common/mi_comm.php");
require("template/header.php");
require("../../../../config/tools/system/pdt/local.inc.php");
include("lib/db_connect.php");

$current_page="current_page_pdt";
$table=$config->table_pdts;

if (isset($_POST['action'])) $action=$_POST['action'];
else if (isset($_GET['action'])) $action=$_GET['action'];
else $action="";

if (isset($_GET['page'])) $_SESSION[$current_page]=$_GET['page'];
else if (!isset($_SESSION[$current_page])) $_SESSION[$current_page]=1;

$xmlrpc_host="";
$xmlrpc_port="";
$fifo_file="";
$comm_type="";

################
# start modify #
################
if ($action=="modify")
{
	require("lib/".$page_id.".test.inc.php");
	if ($form_valid) {
		del_pdt_multiple($old_prefix, $old_sdomain);
		add_pdt_multiple($config->start_prefix.$prefix, $sdomain, $domain);
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
	if ($config->sdomain) $sql="SELECT * FROM ".$table." WHERE prefix='".$_GET['prefix']."' AND sdomain='".$_GET['sdomain']."' LIMIT 1";
	else $sql="SELECT * FROM ".$table." WHERE prefix='".$_GET['prefix']."' LIMIT 1";
	$row_e = $link->queryAll($sql);
	if(PEAR::isError($row_e)) {
		die('Failed to issue query, error message : ' . $row_e->getMessage());
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
		$_SESSION['pdt_search_prefix']="";
		$_SESSION['pdt_search_sdomain']="";
		$_SESSION['pdt_search_domain']="";
		add_pdt_multiple($config->start_prefix.$prefix, $sdomain, $domain);
		$sql = "SELECT * FROM ".$table." WHERE (1=1)";
		$resultset = $link->queryAll($sql);
		if(PEAR::isError($resultset)) {
			die('Failed to issue query, error message : ' . $resultset->getMessage());
		}	
		$data_no=count($resultset);
		$link->disconnect;
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
if ($_GET['action']=="delete")
{
	$del_p=$_GET['prefix'];
	$del_sd=$_GET['sdomain'];
	del_pdt_multiple($del_p, $del_sd);
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
		$_SESSION['pdt_search_prefix']="";
		$_SESSION['pdt_search_sdomain']="";
		$_SESSION['pdt_search_domain']="";
	}
	else {
		$_SESSION['pdt_search_prefix']=$search_prefix;
		$_SESSION['pdt_search_sdomain']=$search_sdomain;
		$_SESSION['pdt_search_domain']=$search_domain;
	}
}
##############
# end search #
##############

##############
# start main #
##############
require("lib/".$page_id.".main.js");
require("template/".$page_id.".main.php");
require("template/footer.php");
exit();
############
# end main #
############

?>
