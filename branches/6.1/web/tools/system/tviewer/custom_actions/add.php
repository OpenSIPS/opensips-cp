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

	//here goes validation
	foreach ($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table_column_defs'] as $key => $value){
		if (isset($value['show_in_add_form']) && $value['show_in_add_form'] == true){
			if (isset($value['validation_regex'])){
				if (!preg_match($value['validation_regex'],$_POST[$key])){
					$form_error = $value['validation_err'];
					require("template/".$page_id.".add.php");
					require("template/footer.php");
					exit();
				}	
			}
		}
	}
	
	// Check Primary, Unique and Multiple Keys 
	$query = build_unique_check_query($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']],$table,$_POST,NULL);

	if ($query != NULL){
		$count = $link->queryOne($query);
		if(PEAR::isError($count)) {
			$form_error=$count->getMessage();
			require("template/".$page_id.".add.php");
			require("template/footer.php");
			exit();
		}

		if ($count > 0){
			$form_error="Key Constraint violation - Record with same key(s) already exists";
			require("template/".$page_id.".add.php");
			require("template/footer.php");
			exit();
		}
	}

	
	foreach ($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table_column_defs'] as $key => $value){
		if (isset($_POST[$key])){
			$fields.=$key.",";
			$values.="'".$_POST[$key]."',";
		}
	}
	//chop the commma at the end :D	
	$fields = substr($fields,0,-1);
	$values = substr($values,0,-1);
	
	if(!$_SESSION['read_only']){
		if ($form_error=="") {

				$sql = "INSERT INTO ".$table."
				(".$fields.") VALUES
				(".$values.") ";
				$result = $link->exec($sql);
				if(PEAR::isError($result)) {
                	$form_error=$result->getMessage();
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
