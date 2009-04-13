<?php
/*
 * $Id:$
 */

 require_once("../../../config/session.inc.php");
 require_once("../../../config/tools/drouting/db.inc.php");
 require_once("../../../config/tools/drouting/menu.inc.php");
 require_once("../../../config/tools/drouting/local.inc.php");
 require_once("lib/functions.inc.php");
 
 $page_name = basename($_SERVER['PHP_SELF']);
 $page_id = substr($page_name, 0, strlen($page_name) - 4);
 $no_result = "No Data Found.";
 $back_link = '<a href="'.$page_name.'" class="backLink">Go Main</a>';
?>

<html>

<head>
 <link href="style/style.css" type="text/css" rel="StyleSheet">
</head>

<body bgcolor="#e9ecef">
<center>
<table width="705" cellpadding="5" cellspacing="5" border="0">
 <tr  valign="top" height="20">
  <td><?php require("template/menu.php") ?></td>
 </tr>
 <tr valign="top" align="center"> 
  <td>
   <img src="images/spacer.gif" width="10" height="5"><br>
