<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
	<tr>
    	<td class="breadcrumb">
        	<?php print "System / ".$custom_config[$module_id]['custom_name']." / ".$_SESSION['permission'];
        	?>
			<td align=right style="border-bottom: 1px solid #ccc!important">
				<a  onclick="top.frames['main_body'].location.href='../../../tools/admin/tools_config/tools_config.php?action=edit_tools';" href="#"   id="config_admin"></a>
			</td 
    	</td>
	</tr>
  	<tr>
    	<td align="center" valign="middle">
			<div class="menuItems">
        	<?php
				if (isset($_GET['submenu_item_id'])){
				    $_SESSION[$module_id]['submenu_item_id'] = $_GET['submenu_item_id'];
				}
					
				if (!isset($custom_config[$module_id]['submenu_items']) || count($custom_config[$module_id]['submenu_items']) == 0) {
					$_SESSION[$module_id]['submenu_item_id'] = 0;
					echo('&nbsp;&nbsp;&nbsp;&nbsp;');
				}
				else {
					if (!isset($_SESSION[$module_id]['submenu_item_id'])){
						$_SESSION[$module_id]['submenu_item_id'] = 0;
					}

					$menu_string = "";


					foreach ($custom_config[$module_id]['submenu_items'] as $menu_item_id => $menu_item_name ){
						if ($menu_item_id == $_SESSION[$module_id]['submenu_item_id']){
								$menu_string .= 	($menu_string == "") ?
													'<a href="tviewer.php?submenu_item_id='.$menu_item_id.'" class="menuItemSelect">'.$menu_item_name.'</a>' :
													'&nbsp;&nbsp;|&nbsp;&nbsp;'.'<a href="tviewer.php?submenu_item_id='.$menu_item_id.'" class="menuItemSelect">'.$menu_item_name.'</a>';
						}
						else {
								$menu_string .= 	($menu_string == "") ?
													'<a href="tviewer.php?submenu_item_id='.$menu_item_id.'" class="menuItem">'.$menu_item_name.'</a>' :
													'&nbsp;&nbsp;|&nbsp;&nbsp;'.'<a href="tviewer.php?submenu_item_id='.$menu_item_id.'" class="menuItem">'.$menu_item_name.'</a>';
						}
					}

					echo $menu_string;
				}
        	?>
			</div>
		</td> 
	</tr>
</table>

