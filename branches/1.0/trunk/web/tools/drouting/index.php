<?php
/*
 * $Id: index.php,v 1.2 2007-04-03 13:10:52 daniel Exp $
 */ 

 require("lib/functions.inc.php");
 session_start();
 get_priv();
 header("Location: gateways.php");
 
?>