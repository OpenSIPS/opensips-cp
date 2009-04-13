<?php
/*
* $Id:$
* Copyright (C) 2008 Voice Sistem SRL
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
require ("../../common/mi_comm.php");
$table=$config->table_domains;

#################
# start add new #
#################
if ($_GET['action']=="add")
{
	$info="";
	$error="";
	$domain=$_POST['new_domain'];
	if (strpos($domain,".")===false) $error="Invalid domain name";
	if ($error=="") {
		db_connect();
		$result=mysql_query("SELECT * FROM ".$table." WHERE domain='".$domain."' ") or die(mysql_error());
		if (mysql_num_rows($result)>0) $error="Duplicate domain";
		else {
			mysql_query("INSERT INTO ".$table." (domain, last_modified) VALUES ('".$domain."', NOW())") or die(mysql_error());
			$info="The new domain was added";
		}
		db_close();
	}
}
###############
# end add new #
###############

##############
# start save #
##############
if ($_GET['action']=="save")
{
	$info="";
	$error="";
	$domain=$_POST['new_domain'];
	$old_domain=$_POST['old_domain'];
	if (strpos($domain,".")===false) $error="Invalid domain name";
	if ($error=="") {
		db_connect();
		$result=mysql_query("SELECT * FROM ".$table." WHERE domain='".$domain."' AND domain!='".$old_domain."' ") or die(mysql_error());
		if (mysql_num_rows($result)>0) $error="Duplicate domain";
		else {
			mysql_query("UPDATE ".$table." SET domain='".$domain."', last_modified=NOW() WHERE domain='".$old_domain."' ") or die(mysql_error());
			$info="The domain name was modified";
		}
		db_close();
	}
}
############
# end save #
############

##############
# start edit #
##############
if ($_GET['action']=="edit")
{
	$form_domain=$_GET['domain'];
}
if (($old_domain!="") && ($error!=""))
{
	$form_domain=$old_domain;
}
############
# end edit #
############

################
# start delete #
################
if ($_GET['action']=="delete")
{
	db_connect();
	$del_id=$_GET['domain'];
	mysql_query("DELETE FROM ".$table." WHERE domain='".$del_id."' LIMIT 1") or die(mysql_error());
	db_close();
}
##############
# end delete #
##############

require("template/".$page_id.".main.php");
require("template/footer.php");
exit();

?>