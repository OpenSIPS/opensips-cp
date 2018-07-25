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

require("template/header.php");
require("lib/".$page_id.".main.js");
require("../../../common/mi_comm.php");
require("../../../common/cfg_comm.php");
include("lib/db_connect.php");
$table=$config->table_domains;

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
	$sql = "INSERT INTO ".$table." (domain, last_modified) VALUES (? , NOW())";
	$stm = $link->prepare($sql);
	if ($stm === false) {
		die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
	}
	if ($stm->execute( array($domain) )==FALSE ) {
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
	$sql = "UPDATE ".$table." SET domain=?, last_modified=NOW() WHERE id=?";
	$stm = $link->prepare($sql);
	if ($stm === false) {
		die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
	}
	if ($stm->execute( array($domain,$id) )==FALSE ) {
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
