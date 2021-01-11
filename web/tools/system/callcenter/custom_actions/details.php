<?php

#################
# start details	#
#################
if ($action=="details")
{

		extract($_POST);
		foreach ($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table_column_defs'] as $key => $value)
			$_SESSION[$key] = $_POST[$key];

		require("template/".$page_id.".details.php");
		require("template/footer.php");
		exit();
}
#############
# end details	#
#############

?>
