<?php

################
# start delete #
################
if ($action=="delete"){
	if(!$_SESSION['read_only']){

		$id=$_GET['id'];

		$account_id = $link->queryOne("select account_id from ".$table." WHERE ".$custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table_primary_key']."=".$id);

		$sql = "DELETE FROM ".$table." WHERE ".$custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table_primary_key']."=".$id;
		$link->exec($sql);

		

		//put credit 0 in cassandra	
		$query_credit = "delete from ppcredit where KEY='ba_credit_".$account_id."';";
		$query_fm = "delete from ppcredit where KEY='ba_fm_".$account_id."';";

		try {
			$res = $link_cass->exec($query_credit);
			$res = $link_cass->exec($query_fm);
		}
		catch (PDOException $e){
			$errors = "Unable to update cassandra DB";
		}

	}else{

		$errors= "User with Read-Only Rights";
	}
}
##############
# end delete #
##############

?>
