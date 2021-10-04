<?php

################
# start delete #
################
if ($action=="delete"){
	if(!$_SESSION['read_only']){

		$id=$_GET[$custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table_primary_key']];
		$sql = "DELETE FROM ".$table." WHERE ".$custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table_primary_key']."=?";

		$stm = $link->prepare($sql);
		$stm->execute(array($id));
	}else{
		$errors= "User with Read-Only Rights";
	}
}
##############
# end delete #
##############

?>
