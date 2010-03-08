<?php
/*
* $Id$
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
require ("../../../common/mi_comm.php");
include("lib/db_connect.php");
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
		$sql = "SELECT * FROM ".$table." WHERE domain='".$domain."' ";
		$resultset = $link->query($sql);
		if(PEAR::isError($resultset)) {
			    die('Failed to issue query, error message : ' . $resultset->getMessage());
		}

		if ( $resultset->numRows() > 0 ) $error="Duplicate domain";
		else {
			$sql = 'INSERT INTO '.$table.' (domain, last_modified) VALUES (:domain, NOW())';
			$resultset = $link->prepare($sql);
			$resultset->bindParam('domain', $domain);

			$resultset->execute();
			$resultset->free();

		    }
	}
	$link->disconnect();
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
		$sql = "SELECT * FROM ".$table." WHERE domain='".$domain."' AND domain!='".$old_domain."' ";
		$resultset = $link->query($sql);
		if(PEAR::isError($resultset)) {
                            die('Failed to issue query, error message : ' . $resultset->getMessage());
                }
		if ( $resultset->numRows() > 0 ) $error="Duplicate domain";
		else {
			$sql = "UPDATE ".$table." SET domain='".$domain."', last_modified=NOW() WHERE domain='".$old_domain."' ";
			$info="The domain name was modified";

                        $resultset = $link->prepare($sql);

                        $resultset->execute();
                        $resultset->free();
		}
	$link->disconnect();
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
	$del_id=$_GET['domain'];	
	//$sql = "DELETE FROM ".$table." WHERE domain='".$del_id."' LIMIT 1";
	$sql = "DELETE FROM ".$table." WHERE domain='".$del_id."'";
	$link->exec($sql);	
	$link->disconnect();
}
##############
# end delete #
##############

require("template/".$page_id.".main.php");
require("template/footer.php");
exit();

?>
