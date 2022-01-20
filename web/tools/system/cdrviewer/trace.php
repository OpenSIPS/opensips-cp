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


require_once("../../../../config/session.inc.php");
require("../../../../config/tools/system/siptrace/db.inc.php");
require("../../../../config/db.inc.php");
require("../../../common/cfg_comm.php");
require("../siptrace/lib/functions.inc.php");
include("lib/db_connect.php");
session_load_from_tool("siptrace");

session_start();

$_SESSION['detailed_callid']=array();
$_SESSION['grouped_results']=true;

$callid	=	$_GET['callid'];
$tracer	=	$_GET['tracer'];

if ($tracer=="homer") {

	header ('Location: ../homer/homer.php?callid='.$callid);

} else
if ($tracer=="siptrace") {

	// get the id from siptrace table .
	$sql = "select id from ".$config->table_trace." where callid=?";
	$stm = $link->prepare($sql);
	if ($stm === false) {
		die('Failed to issue query, error message : ' . print_r($link->errorInfo(), true));
	}
	$stm->execute( array($callid) );
	$row = $stm->fetchAll(PDO::FETCH_ASSOC)[0];

	$siptraceid = $row['id'];

	if (!(is_numeric($siptraceid))) {
		echo('<tr><td colspan="5" class="rowEven" align="center"><br>Sorry , sip trace for this call is unavailable<br><br></td></tr>');
		exit();
	}

	$_SESSION['tracer_search_regexp']="";
	$_SESSION['tracer_search_callid']=$callid;
	$_SESSION['tracer_search_start']="";
	$_SESSION['tracer_search_end']="";
	$_SESSION['tracer_search_traced_user']="";

	header ('Location: ../siptrace/tracer.php?id='.$siptraceid);

} else {

	echo('<tr><td colspan="5" class="rowEven" align="center"><br>Unknown '.$tracer.' tracing tool :( <br><br></td></tr>');

}
?>
