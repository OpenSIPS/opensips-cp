<?
require("template/header.php");
require("../../../../config/tools/system/cdrviewer/local.inc.php");
include("lib/db_connect.php");

global $config;

$current_page="current_page_cdrviewer";

if (isset($_POST['action'])) $action=$_POST['action'];
else if (isset($_GET['action'])) $action=$_GET['action'];
else $action="";

if (isset($_GET['page'])) $_SESSION[$current_page]=$_GET['page'];
else if (!isset($_SESSION[$current_page])) $_SESSION[$current_page]=1;




if ($action=="search")
{
	$_SESSION[$current_page]=1;
	extract($_POST);

	if ($show_all=="Show All") {

		$_SESSION['cdrviewer_search_val']="";
		$_SESSION['cdrviewer_search_cdr_field']="";
		$_SESSION['cdrviewer_search_start']="";
		$_SESSION['cdrviewer_search_end']="";

	}

	else {
		$_SESSION['cdrviewer_search_val']=$search_regexp;
		$_SESSION['cdrviewer_search_cdr_field'] = $cdr_field ;
		if ($set_start=="set") $_SESSION['cdrviewer_search_start']=$start_year."-".$start_month."-".$start_day." ".$start_hour.":".$start_minute.":".$start_second;
		else $_SESSION['cdrviewer_search_start']="";
		if ($set_end=="set") $_SESSION['cdrviewer_search_end']=$end_year."-".$end_month."-".$end_day." ".$end_hour.":".$end_minute.":".$end_second;
		else $_SESSION['cdrviewer_search_end']="";
	}
}

if ($export == "Export") {

	$search_regexp=$_SESSION['cdrviewer_search_val'];
	$cdr_field = $_SESSION['cdrviewer_search_cdr_field'];


	if (($cdr_field!="") && ($search_regexp!="")) $sql_search.=" and ".$cdr_field.'="'.$search_regexp.'"' ;


	$search_start=$_SESSION['cdrviewer_search_start'];
	$search_end=$_SESSION['cdrviewer_search_end'];

	if (($search_start != "" ) ||  ($search_start != "" ) || ($sql_search!="" ))  {
		cdr_put_to_download($search_start,$search_end,$sql_search,"cdr-temp.csv");
		$link->disconnect();
	}
	exit();
}


require("lib/".$page_id.".main.js");
?>

<html>

<head>
 <link href="style/style.css" type="text/css" rel="StyleSheet">
</head>

<body bgcolor="#e9ecef">
<center>

<table width="705" cellpadding="5" cellspacing="5" border="0">
 <tr valign="top" align="center"> 
  <td>
   
   


<?
require("template/".$page_id.".main.php");
require("template/footer.php");
exit();

?> 
