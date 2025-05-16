<?php

#################
# start flush	#
#################
if ($action=="flush")
{
require("../../../common/mi_comm.php");
$success = 0;
$mi_connectors=get_all_proxys_by_assoc_id(get_settings_value('talk_to_this_assoc_id'));
for ($i=0;$i<count($mi_connectors);$i++){

	$message=mi_command("config_flush", NULL, $mi_connectors[$i], $errors);

	if (empty($errors)) {
		$success++;
	} else {
		echo "<script>alert('ERROR: ".implode(",",$errors).");</script>";
	}
	echo "<br>";
}

if ($success > 0)
	echo "<script>alert('Successfully flushed on $success servers');</script>";
}
#################
# end flush	#
#################

?>
