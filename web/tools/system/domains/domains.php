<?php
/*
* Copyright (C) 2011-2017 OpenSIPS Project
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
session_load();

csrfguard_validate();

$table=get_settings_value("table_domains");
$has_attrs=(get_settings_value("attributes") == "1");

include("lib/db_connect.php");

if (isset($_POST['action'])) $action=$_POST['action'];
else if (isset($_GET['action'])) $action=$_GET['action'];
else $action="";

$info="";
$errors="";

#################
# start add new #
#################
if ($action=="add")
{
	$domain=$_POST['domain'];
	$sql = "INSERT INTO ".$table." (domain" .($has_attrs?",attrs":""). ", last_modified) VALUES (?".($has_attrs?", ?":"").", NOW())";
	$stm = $link->prepare($sql);
	if ($stm === false) {
		die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
	}
	$vals = array($domain);
	if ($has_attrs)
		$vals[] = $_POST['attrs'];
	if ($stm->execute($vals)==FALSE) {
        	$errors = "Add/Insert to DB failed with: ". print_r($stm->errorInfo(), true);
	} else {
		$info="Domain Name has been inserted";
	}
}
###############
# end add new #
###############

##############
# start save #
##############
if ($action=="save")
{
	$id=$_GET['id'];
	$domain=$_POST['domain'];
	$sql = "UPDATE ".$table." SET domain=?".($has_attrs?", attrs=?":""). ", last_modified=NOW() WHERE id=?";
	$stm = $link->prepare($sql);
	if ($stm === false) {
		die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
	}
	$vals = array($domain);
	if ($has_attrs)
		$vals[] = $_POST['attrs'];
	$vals[] = $id;
	if ($stm->execute($vals)==FALSE) {
        	$errors = "Update to DB failed with: ". print_r($stm->errorInfo(), true);
	} else {
		$info="Domain name has been modified";
	}
}
############
# end save #
############


##############
# start edit #
##############
if ($action=="edit")
{
	## nothing to do here at the moment
}
############
# end edit #
############


################
# start delete #
################
if ($action=="delete")
{
	$id=$_GET['id'];
	$sql = "DELETE FROM ".$table." WHERE id=?";
	$stm = $link->prepare($sql);
	if ($stm === false) {
		die('Failed to issue query ['.$sql.'], error message : ' . print_r($stm->errorInfo(), true));
	}
	$stm->execute( array($id) );
	$info = "Domain name has been deleted";
}
##############
# end delete #
##############

require("template/".$page_id.".main.php");
if ($errors!="") echo('<tr><td align="center"><div class="formError">'.$errors.'</div></td></tr>');
if ($info!="") echo('<tr><td  align="center"><div class="formInfo">'.$info.'</div></td></tr>');
require("template/footer.php");
exit();

?>
