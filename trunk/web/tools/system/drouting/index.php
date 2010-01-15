<?php
/*
 * $Id: index.php 57 2009-06-03 13:48:46Z iulia_bublea $
 */ 

 require("lib/functions.inc.php");
 //include("lib/db_connect");
 session_start();
 get_priv();
 header("Location: gateways.php");
 
?>
