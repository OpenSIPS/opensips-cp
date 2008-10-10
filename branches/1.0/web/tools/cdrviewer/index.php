<? 

require("lib/functions.inc.php");
session_start();
get_priv();
$_SESSION['detailed_callid']=array();
$_SESSION['grouped_results']=true;
header("Location: cdrviewer.php");

?> 