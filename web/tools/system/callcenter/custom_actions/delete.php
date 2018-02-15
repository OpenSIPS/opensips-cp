<?php

################
# start delete #
################
if ($action=="delete"){
	if(!$_SESSION['read_only']){

		$id=$_GET[$custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table_primary_key']];

		$account_id = $link->queryOne("select account_id from ".$table." WHERE ".$custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table_primary_key']."=".$id);

		$sql = "DELETE FROM ".$table." WHERE ".$custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table_primary_key']."=".$id;
		$link->exec($sql);

	}else{

		$errors= "User with Read-Only Rights";
	}
}
##############
# end delete #
##############

?>
