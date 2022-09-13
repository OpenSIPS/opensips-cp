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

	foreach ($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table_column_defs'] as $key => $value) {
		$_SESSION[$key] = $_POST[$key];
		if ($_POST[$key] == "" && isset($value["is_optional"]) && $value["is_optional"] == "y")
			continue;
		if (isset($value['validation_regex']) && !preg_match("/".$value['validation_regex']."/", $_POST[$key]))
			die("Failed to validate input for ".$key);
	}

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
			if ($value['type'] == "checklist") {
				$val = build_custom_checklist_options($_POST[$key], $value);
			} else {
				$val = $_POST[$key];
			}
			if ($val=="" && !(isset($value['keep_empty_str_val']) && $value['keep_empty_str_val']))
				$values_arr[] = NULL;
			else
				$values_arr[] = $val;
		}
		else if (isset($value["default_value"])){
			$fields.=$key.",";
			$values.="?,";
			$values_arr[] = $value["default_value"];
		}
	}
	//chop the commma at the end :D
	$fields = substr($fields,0,-1);
	$values = substr($values,0,-1);
	
	if(!$_SESSION['read_only']){
		if ($form_error=="") {

			if (isset($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['pre_add_hook']))
				$custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['pre_add_hook']($fields, $values);

			$sql = "INSERT INTO ".$table."(".$fields.") VALUES(".$values.") ";
			$stm = $link->prepare($sql);
			$ret = $stm->execute($values_arr);
			if (isset($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['post_add_hook']))
				$ret = $custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['post_add_hook']($fields, $values, $stm, $ret);
			if ($ret === false) {
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
