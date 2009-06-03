<?
require("../../../config/tools/siptrace/local.inc.php");
require("../../../config/tools/siptrace/db.inc.php");
require("../../../config/db.inc.php");
require("../siptrace/lib/functions.inc.php");
include("lib/db_connect.php");

session_start();
get_priv();
$_SESSION['detailed_callid']=array();
$_SESSION['grouped_results']=true;


$_SESSION['user_active_tool'] = "siptrace";


$callid	=	$_GET['callid'];

// get the id from siptrace table .

$sql = "select id from ".$config->table_trace." where callid='".$callid."'";

$row = $link->queryAll($sql);
if(PEAR::isError($row)) {
	die('Failed to issue query, error message : ' . $row->getMessage());
}

$siptraceid = $row[0]['id'];

if (!(is_numeric($siptraceid))) {
	echo('<tr><td colspan="5" class="rowEven" align="center"><br>Sorry , sip trace for this call is unavailable<br><br></td></tr>');
	$link->disconnect();
	exit();
}


$sql = "select distinct callid from (select * from ".$config->table_trace." where id < ".$siptraceid.") as foo" ;

$resultset=$link->queryAll($sql);
if(PEAR::isError($resultset)) {
	die('Failed to issue query, error message : ' . $resultset->getMessage());
}

$data_no=count($resultset);

$link->disconnect();


$page_no = ceil($data_no/$config->results_per_page)  ;

$_SESSION['tracer_search_regexp']="";
$_SESSION['tracer_search_callid']="";
$_SESSION['tracer_search_start']="";
$_SESSION['tracer_search_end']="";
$_SESSION['tracer_search_traced_user']="";


header ('Location: ../siptrace/tracer.php?id='.$siptraceid."&page=".$page_no);
?>
