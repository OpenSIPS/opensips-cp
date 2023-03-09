<?php
/*
* Copyright (C) 2011 OpenSIPS Project
*
* This file is part of opensips-cp, a free Web Control Panel Application for
* OpenSIPS SIP server.
*
* opensips-cp is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* opensips-cp is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*/

require_once("../../../common/cfg_comm.php");
require_once("../../../common/mi_comm.php");
require_once("template/functions.inc.php");
require("../../../../config/db.inc.php");
require("template/header.php");
require("../../../../config/boxes.global.inc.php");
require_once("template/functions.inc.js");
require("../../../../config/tools/system/dashboard/db.inc.php");
require("../../../../config/tools/system/dashboard/settings.inc.php");
include("lib/db_connect.php");
require("../../../../config/globals.php");
require_once("../../../common/forms.php");
session_load();

csrfguard_validate();

$widgets = load_widgets();
$table=get_settings_value("custom_table");
if (isset($_GET['box_id']) && $_GET['box_id'] != '')
	$box_id = $_GET['box_id'];
else
	$box_id = null;

if (isset($_GET['page'])) $_SESSION[$current_page]=$_GET['page'];
else if (!isset($_SESSION[$current_page])) $_SESSION[$current_page]=1;

if (isset($_POST['action'])) $action=$_POST['action'];
else if (isset($_GET['action'])) $action=$_GET['action'];
else $action="";

if ($default == -1 && $action == "") {
	$action = "edit_panel";
} else if ($action == "") {
	$action = "display_panel";
}

if ($action=="edit_panel")
{  	
    require("template/".$page_id.".edit_panel.php");
	require("template/footer.php");
	exit();
}

if ($action =="import_widget") {
	$panel_id = $_GET['panel_id'];
	require("template/".$page_id.".import_widget.php");
	require("template/footer.php");
	exit();
}


if ($action =="import_widget_true") {
	$panel_id = $_GET['panel_id'];
	$widget_dir = $_GET['widget_dir'];
	require_once($widget_dir."/def.php");
	$widget_params = json_decode($widget_def['json'], true);
	$widget_params['panel_id'] = $panel_id;
	$widget_params['widget_id'] = "panel_".$panel_id."_widget_".($_SESSION['config']['panels'][$panel_id]['widget_no'] + 1);	
	$new_widget = new $widget_params['widget_type']($widget_params);
	$new_widget->set_id($widget_params['widget_id']);
	$widget_array = $new_widget->get_as_array();
	$stored_widgets = json_decode($_SESSION['config']['panels'][$panel_id]['content'], true);
	$stored_widgets[$widget_params['widget_id']] = json_encode($widget_params);

	$stored_positions = json_decode($_SESSION['config']['panels'][$panel_id]['positions']);
	$new_widget_position['id'] = $widget_params['widget_id'];
	$new_widget_position['size_x'] = $widget_array[1]; //size_x
	$new_widget_position['size_y'] = $widget_array[2]; //size_y
	$stored_positions[] = $new_widget_position;

	if(!$_SESSION['read_only']){
		
		$sql = 'UPDATE '.$table.' SET content = ?, positions = ? WHERE id = ? ';
		$stm = $link->prepare($sql);
		if ($stm === false) {
			die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
		}

		if ($stm->execute( array(json_encode($stored_widgets), json_encode($stored_positions), $panel_id)) == false)
			echo('<tr><td align="center"><div class="formError">'.print_r($stm->errorInfo(), true).'</div></td></tr>');

		load_panels();

		require("template/".$page_id.".main.php");
		require("template/footer.php");
		exit();
  
   } else {
	   $errors= "User with Read-Only Rights";
	  }

}

if ($action == "import_details") {
	$widget_dir = $_GET['widget_dir'];
	require_once($widget_dir."/def.php");
	require("template/dashboard.import_details.php");
	require("template/footer.php");
	exit();
}

if ($action=="edit_widget")
{  	
	$widget_id = $_GET['widget_id'];
	$panel_id = $_GET['panel_id'];
	$widget_content = json_decode($_SESSION['config']['panels'][$panel_id]['widgets'][$widget_id]['content'], true);
    require("template/widget/".$page_id.".edit_widget.php");
	require("template/footer.php");
	exit();
}

if ($action=="edit_widget_verify")
{  	
	$widget_params = $_POST;
	foreach ($widget_params as $key => $value) {
	    if (substr($key, 0, 4) === "CSRF")
		    unset($widget_params[$key]);
	}
	$widget_id = $_GET['widget_id'];
	$widget_type = $_GET['widget_type'];
	$panel_id = $_GET['panel_id'];
	$widget_params['panel_id'] = $panel_id;
	$widget_params['widget_type'] = $widget_type;
	$widget_params['widget_id'] = $widget_id;
	$stored_widgets = json_decode($_SESSION['config']['panels'][$panel_id]['content'], true);
	$stored_widgets[$widget_id] = json_encode($widget_params);
	$sql = 'UPDATE '.$table.' SET content = ? WHERE id = ? ';
	$stm = $link->prepare($sql);
	if ($stm === false) {
		die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
	}

	if ($stm->execute( array(json_encode($stored_widgets), $panel_id)) == false)
		echo('<tr><td align="center"><div class="formError">'.print_r($stm->errorInfo(), true).'</div></td></tr>');
	load_panels();
}

if ($action=="delete")
{
	if(!$_SESSION['read_only']){

		$id = $_GET['panel_id'];

		$sql = "DELETE FROM ".$table." WHERE id=?";
      		$stm = $link->prepare($sql);
		if ($stm === false) {
			die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
		}
		$stm->execute( array($id) );
		unset($_SESSION['config']['panels'][$id]);
		$action = "edit_panel";
		echo '<script>window.location = "dashboard.php?action=edit_panel";</script>';
	}else{

		$errors= "User with Read-Only Rights";
	}
}

if ($action=="delete_widget")
{
	if(!$_SESSION['read_only']){

		$panel_id = $_GET['panel_id'];
		$widget_id = $_GET['widget_id'];
		
		$stored_widgets = json_decode($_SESSION['config']['panels'][$panel_id]['content'], true);
		unset($stored_widgets[$widget_id]);
		$stored_positions = [];
		foreach($_SESSION['config']['panels'][$panel_id]['widgets'] as $w_id => $w) {
			if ($w_id != $widget_id)
				$stored_positions[] = json_decode($w['positions']);
		}
		$sql = 'UPDATE '.$table.' SET content = ?, positions = ? WHERE id = ? ';
		$stm = $link->prepare($sql);
		if ($stm === false) {
			die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
		}
	
		if ($stm->execute( array(json_encode($stored_widgets), json_encode($stored_positions), $panel_id)) == false)
			echo('<tr><td align="center"><div class="formError">'.print_r($stm->errorInfo(), true).'</div></td></tr>');
		load_panels();

	}else{

		$errors= "User with Read-Only Rights";
	}
}

if ($action == "details") {
	$id = $_GET['box_id'];
	require("template/".$page_id.".details.php");
	require("template/footer.php");
	exit();
}

if ($action == "display_panel" || $action == "view_new_panel") {
	
	if (isset($_GET['panel_id']))
		$panel_id = $_GET['panel_id'];
	else $panel_id = $default;
	
	require("template/".$page_id.".main.php");
	require("template/footer.php");
	exit();
}

if ($action == "add_widget") {
	$panel_id = $_GET['panel_id'];
	require("template/widget/".$page_id.".addWidget.php");
	require("template/footer.php");
	exit();
}

if ($action=="add_blank_panel")
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
if ($action == "add_widget_verify") { //add widget in db 
	if(!$_SESSION['read_only']){
		$widget_params = $_POST; //params from widget form
		foreach ($widget_params as $key => $value) {
			if (substr($key, 0, 4) === "CSRF")
				unset($widget_params[$key]);
		}
		$panel_id = $_GET['panel_id']; //panel to add widget in
		$widget_type = $_GET['widget_type']; //widget type (class)
		$widget_params['widget_type'] = $widget_type; //set widget type inside the params array
		$widget_params['panel_id'] = $panel_id; //set widget panel inside the params array
        //construct the widget id and add it to the params array
		$widget_params['widget_id'] = "panel_".$panel_id."_widget_".($_SESSION['config']['panels'][$panel_id]['widget_no'] + 1);
		//construct new widget object and pass form input to the constructor
        $new_widget = new $widget_type($widget_params);
        //set id of new widget object
		$new_widget->set_id($widget_params['widget_id']);
		$widget_array = $new_widget->get_as_array(); //returns widget html, widget sizes, widget id

        //fetch widgets from db, and add the current widget (in the form of widget_id=>widget_params)
		$stored_widgets = json_decode($_SESSION['config']['panels'][$panel_id]['content'], true);
		$stored_widgets[$widget_params['widget_id']] = json_encode($widget_params);

        //set new widget positions (id, sizeX, sizeY, everything else goes into content column)
        //widget id and size is stored separately because size is reloaded every time the 
        //widget is dragged. 
		$stored_positions = json_decode($_SESSION['config']['panels'][$panel_id]['positions']);
		$new_widget_position['id'] = $widget_params['widget_id'];
		$new_widget_position['size_x'] = $widget_array[1]; //size_x
		$new_widget_position['size_y'] = $widget_array[2]; //size_y
		$stored_positions[] = $new_widget_position;
		
		$sql = 'UPDATE '.$table.' SET content = ?, positions = ?  WHERE id = ?';
		$stm = $link->prepare($sql);
		if ($stm === false) {
			die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
		}

		if ($stm->execute( array(json_encode($stored_widgets), json_encode($stored_positions), $panel_id)) == false)
			echo('<tr><td align="center"><div class="formError">'.print_r($stm->errorInfo(), true).'</div></td></tr>');

		load_panels();

		require("template/".$page_id.".main.php");
		require("template/footer.php");
		exit();
  
   } else {
	   $errors= "User with Read-Only Rights";
	  }
}


if ($action == "add_verify") { 
	if(!$_SESSION['read_only']){
		extract($_POST);
		$sql = 'INSERT INTO '.$table.' (`name`, `order`) VALUES (?, ?) ';
		$stm = $link->prepare($sql);
		if ($stm === false) {
			die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
		}
		$errors=null;
		if ($stm->execute( array($_POST['panel_name'], $_SESSION['config']['panels_max_order'] + 1)) == false) {
			$errors= "Inserting record into DB failed: ".print_r($stm->errorInfo(), true);
		}
		if (!$errors) {
			print "New Panel added!";
			$action="edit_panel";
		} else {
			require("template/".$page_id.".add.php");
			require("template/footer.php");
			exit();
		}
	} else {
		$errors= "User with Read-Only Rights";
	}
	echo '<script>window.location = "dashboard.php?action=view_new_panel";</script>';
}

if ($action == "clone_panel") {
	if(!$_SESSION['read_only'])
	{
		$panel_id = $_GET['panel_id'];
		require("template/".$page_id.".clone.php");
		require("template/footer.php");
		exit();
	}else {
		$errors= "User with Read-Only Rights";
	}
}


if ($action == "clone_panel_verify") { 
	if(!$_SESSION['read_only']){
		extract($_POST);
		$panel_id = $_GET['panel_id'];

		$sql = 'INSERT INTO '.$table.' () VALUES () ';
				$stm = $link->prepare($sql);
		if ($stm === false) {
			die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
		}
		if ($stm->execute( array()) == false) {
			$errors= "Inserting record into DB failed: ".print_r($stm->errorInfo(), true);
			$form_valid=false;
		} 
		
		$sql = 'select max(id) from '.$table;
				$stm = $link->prepare($sql);
		if ($stm === false) {
			die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
		}
		if ($stm->execute( array()) == false) {
			$errors= "DB operation failed: ".print_r($stm->errorInfo(), true);
			$form_valid=false;
		} 
		$resultset = $stm->fetchAll(PDO::FETCH_ASSOC);
		$latest_panel = $resultset[0]["max(id)"];

		$widget_contents = json_decode($_SESSION['config']['panels'][$panel_id]['content'], true);
		foreach($widget_contents as $key => $widget_content) {
			$widget_content_decoded = json_decode($widget_content, true);
			$widget_content_decoded['panel_id'] = $latest_panel;
			$new_widget_content = json_encode($widget_content_decoded);
			$widget_contents[$key] = $new_widget_content;
		}
		$widget_contents_json = json_encode($widget_contents);

		$sql = 'UPDATE '.$table.' SET `name`=?, content=?, `order`=?, positions=? where id = ?';
				$stm = $link->prepare($sql);
		if ($stm === false) {
			die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
		}
		if ($stm->execute( array($panel_name, $widget_contents_json , $_SESSION['config']['panels_max_order'] + 1,
		$_SESSION['config']['panels'][$panel_id]['positions'], $latest_panel)) == false) {
			$errors= "Inserting record into DB failed: ".print_r($stm->errorInfo(), true);
			$form_valid=false;
		} 
		if ($form_valid) {
		  print "New Panel added!";
		  $action="edit_panel";
		} else {
		  print $form_error;
		  $action="add_verify";
		}
  
   } else {
	   $errors= "User with Read-Only Rights";
	  }
	echo '<script>window.location = "dashboard.php?action=edit_panel";</script>';
}

if ($action == "move_panels") {
	$order_id1 = $_GET['order_id1'];
	$order_id2 = $_GET['order_id2'];

	if ($order_id1 != -1 && $order_id2 != -1) {
		swap_panels($order_id1, $order_id2, $table);
	}
	echo '<script>window.location = "dashboard.php?action=edit_panel";</script>';
}

if ($action == "change_panel_name") {
	
	extract($_POST);
	$panel_id = $_GET['panel_id'];
	if(!$_SESSION['read_only'])
	{
			require("template/".$page_id.".change_name.php");
			require("template/footer.php");
			exit();
	} else {
			$errors= "User with Read-Only Rights";
	}
}


if ($action == "change_name_verify") { 
	if(!$_SESSION['read_only']){
		extract($_POST);
		$sql = 'UPDATE '.$table.' SET name = ? WHERE id = ? ';
				$stm = $link->prepare($sql);
		if ($stm === false) {
			die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
		}
		if ($stm->execute( array($_POST['panel_name'], $_GET['panel_id'])) == false) {
			$errors= "Changing record into DB failed: ".print_r($stm->errorInfo(), true);
			$form_valid=false;
		} 
		if ($form_valid) {
		  print "Panel name changed!";
		  $action="edit_panel";
		} else {
		  print $form_error;
		}
   } else {
	   $errors= "User with Read-Only Rights";
	  }
	  echo '<script>window.location = "dashboard.php?action=edit_panel";</script>';
}


require("template/".$page_id.".main.php");
if(isset($errors)) echo($errors);
require("template/footer.php");
exit();

?>
