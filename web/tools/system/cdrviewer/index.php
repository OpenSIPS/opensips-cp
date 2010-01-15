<? 
require("lib/functions.inc.php");
require("../../../../config/db.inc.php");
require("../../../../config/tools/system/cdrviewer/db.inc.php");
//include("lib/db_connect.php");
unset($_SESSION['read_only']);
session_start();
$_SESSION['user_active_tool']="cdrviewer";
get_priv();
$_SESSION['detailed_callid']=array();
$_SESSION['grouped_results']=true;
header("Location: cdrviewer.php");
?> 
