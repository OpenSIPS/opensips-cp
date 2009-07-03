<? 
require("lib/functions.inc.php");
require("../../../config/db.inc.php");
require("../../../config/tools/cdrviewer/db.inc.php");
//include("lib/db_connect.php");
session_start();
get_priv();
$_SESSION['detailed_callid']=array();
$_SESSION['grouped_results']=true;
header("Location: cdrviewer.php");
?> 
