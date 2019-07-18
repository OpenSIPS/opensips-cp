<?php

#################
# start add new #
#################

if ($action=="add")
{
	extract($_POST);
	if(!$_SESSION['read_only'])
	{
		require("template/".$page_id.".add.php");
		require("template/footer.php");
		exit();
	}else {
		$errors= "User with Read-Only Rights";
	}
}

#################
# end add new   #
#################


####################
# start add verify #
####################
if ($action=="add_verify")
{
	$success="";
	$form_error="";
	$fields="";
	$values="";

	foreach ($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table_column_defs'] as $key => $value)
		$_SESSION[$key] = $_POST[$key];	

	// Check Primary, Unique and Multiple Keys 
	list ($query, $qvalues) = build_unique_check_query($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']],$table,$_POST,NULL);

	if ($query != NULL){
		$stm = $link->prepare($query);
		if ($stm->execute($qvalues) === false) {
			error_log(print_r($stm->errorInfo(), true));
			$form_error=print_r($stm->errorInfo(), true);
			require("template/".$page_id.".add.php");
			require("template/footer.php");
			exit();
		}

		if ($stm->fetchColumn(0) > 0){
			$form_error="Key Constraint violation - Record with same key(s) already exists";
			require("template/".$page_id.".add.php");
			require("template/footer.php");
			exit();
		}
	}

	$values_arr = array();
	foreach ($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table_column_defs'] as $key => $value){
		if (isset($_POST[$key])){
			$fields.=$key.",";
			$values.="?,";
			$values_arr[] = $_POST[$key];
		}
	}
	//chop the commma at the end :D	
	$fields = substr($fields,0,-1);
	$values = substr($values,0,-1);
	
	if(!$_SESSION['read_only']){
		if ($form_error=="") {

				$sql = "INSERT INTO ".$table."(".$fields.") VALUES(".$values.") ";
				$stm = $link->prepare($sql);
				if ($stm->execute($values_arr) === false) {
                	$form_error=print_r($stm->errorInfo(), true);
					require("template/".$page_id.".add.php");
					require("template/footer.php");
                	exit();
				}

				$success="The new entry has been successfully added";
				foreach ($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table_column_defs'] as $key => $value)
			                unset($_SESSION[$key]);
		}
	}else{
		$errors= "User with Read-Only Rights";
	}
	
	require("template/".$page_id.".add.php");
        require("template/footer.php");
        exit();

}

##################
# end add verify #
##################
?>
