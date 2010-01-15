<?php
/*
 * $Id: apply_changes.php 40 2009-04-13 14:59:22Z iulia_bublea $
 */

?>


<HTML>

<HEAD>
 <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
 <META HTTP-EQUIV="Expires" CONTENT="-1">
</HEAD>

<BODY>
<?php

require("../../../../config/tools/system/drouting/local.inc.php");
require("../../../common/mi_comm.php");
require("lib/functions.inc.php");

$xmlrpc_host="";
$xmlrpc_port="";
$fifo_file="";
$comm_type=""; 

$mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);
$command="dr_reload";

for ($i=0;$i<count($mi_connectors);$i++){


	$comm_type=params($mi_connectors[$i]);
	
	$message=mi_command($command, $errors, $status);

}

if ($errors) {

    echo($errors[0]);
    
	
} else {
	    
    echo "Command successfully executed.";
		
		    
}
		
return;    
    
?>
</BODY>

</HTML>
