<?php
session_start();

require("../../../../config/tools/system/blacklist/local.inc.php");
require("../../../common/mi_comm.php");
require("../../../common/cfg_comm.php");

$command= "reload_blacklist"

?>
<fieldset><legend>Sending MI command: <?=$command?></legend>
<br>
<?php

$mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);

for ($i=0;$i<count($mi_connectors);$i++){
	echo "Sending to <b>".$mi_connectors[$i]."</b> : ";

	$message=mi_command($command, $mi_connectors[$i], $mi_type, $errors, $status);
	if (!$errors) {
		echo "<font color='green'><b>Success</b></font>";
	}
	echo "<br>";
}

?>

</fieldset>