<?php
/*
 * $Id$
 */ 

 require("lib/functions.inc.php");
 //include("lib/db_connect");
 session_start();
 get_priv();
 header("Location: gateways.php");
 
?>
