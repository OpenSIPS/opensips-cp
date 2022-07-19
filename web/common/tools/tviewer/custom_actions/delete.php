<?php

################
# start delete #
################
if ($action=="delete"){
	if(!$_SESSION['read_only']){
		if (isset($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['pre_delete_hook']))
			$custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['pre_delete_hook']($id);

		$id=$_GET[$custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table_primary_key']];
		$sql = "DELETE FROM ".$table." WHERE ".$custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table_primary_key']."=?";

		$stm = $link->prepare($sql);
		$ret = $stm->execute(array($id));
		if (isset($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['post_delete_hook']))
			$custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['post_delete_hook']($id, $stm, $ret);
	}else{
		$errors= "User with Read-Only Rights";
	}
}
##############
# end delete #
##############

?>
