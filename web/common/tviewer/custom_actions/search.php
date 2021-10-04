<?php

################
# start search #
################


if ($action=="dp_act")
{

	extract($_GET);
	extract($_POST);
	if (isset($show_all) && $show_all=="Show All") {
		foreach ($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table_column_defs'] as $key => $value){	
			unset($_SESSION[$key]);
		}
	} else if($search=="Search"){
		foreach ($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table_column_defs'] as $key => $value){
			if (isset($_POST[$key]))
	        		$_SESSION[$key]=$_POST[$key];
			else if (isset($_GET[$key]))
				$_SESSION[$key]=$_GET[$key];
		}
        }
}

##############
# end search #
##############

?>
