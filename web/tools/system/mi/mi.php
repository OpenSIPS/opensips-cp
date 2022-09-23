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

require("../../../common/mi_comm.php");
require("../../../common/cfg_comm.php");
require("lib/functions.inc.php");

session_start();

csrfguard_validate();

if ($_GET['action']=="change_box" && !empty($_POST['box_val'])) {
	$current_box=$_POST['box_val'];
	$_SESSION['mi_current_box']=$current_box ;
	unset($_SESSION['mi_command_list']);
} else if (!empty($_SESSION['mi_current_box'])) {
	$current_box=$_SESSION['mi_current_box'];
} else {
	$current_box="";
}

require("template/header.php");
get_priv("mi");

if (empty($_SESSION['mi_command_list']))
	get_command_list( $current_box );

if ($_GET['action']=="execute")
{
	$mi_func_exception = array (
		"fs_subscribe" => array(1, "events"),
		"fs_unsubscribe" => array(1, "events"),
		"b2b_trigger_scenario" => array(1, "scenario_params"),
		"dlg_push_var" => array(2, "DID"),
		"get_statistics" => array(0, "statistics"),
		"trace_start" => array(0, "filter")
	);

	$error=false;
	$input=$_POST['mi_cmd'];
	$input = preg_replace("/\s+/", " ", $input);
	$input = preg_replace("/\s+=\s+/", "=", $input);

	$tokens = explode(" ", $input);

	$command = array_shift( $tokens );

	if (!empty($command)) {

		$has_name=FALSE;
		$params = array();

		for( $i=0 ; $i<count($tokens) ; $i++ ) {
			
			if (strpos( $tokens[$i], '=') !== false) {
				// param with name
				if (!$has_name) {
					if ($i!=0) {
						$errors[]="You cannot mix parameters with and without name";
						break;
					}
					$has_name=TRUE;
				}
				$p = explode("=",$tokens[$i]);
				if ( array_key_exists( $command, $mi_func_exception) && $p[0]=$mi_func_exception[$command][1]) {
					$ar = array( $p[1] );
					$params[ $p[0] ] = array_merge( $ar, array_slice($tokens, $i) ); 
					$i = count($tokens);
				} else {
					$params[ $p[0] ] = $p[1]; 
				}
			} else {
				// param without name
				if ($has_name) {
					if ($i!=0) {
						$errors[]="You cannot mix parameters with and without name";
						break;
					}
				}
				array_push($params,$tokens[$i]);
			}	
		}

		if ( array_key_exists( $command, $mi_func_exception) ) {
			if ($has_name==FALSE) {
				// positional params, no names
				$new_params = array_slice($params, 0, $mi_func_exception[$command][0]);
				$params = array_merge( $new_params, array(array_slice($params, $mi_func_exception[$command][0])));
			}
		}

		if (empty($params))
			$params = NULL;	

		if (!empty($errors)) {
			echo "<font color='red'>".$errors[0]."</font>";
		} else {
			$message=mi_command($command,$params,$current_box,$errors);

			if (!empty($message)) {

				$_SESSION['mi_time'][]=date("H:i:s");
				$_SESSION['mi_command'][]=$input;
				$_SESSION['mi_box'][]=$current_box ;

				if (count($message) == 0){
					$_SESSION['mi_response'][]="Successfully executed, no output generated";
				} else {
					$_SESSION['mi_response'][]=json_encode($message,JSON_PRETTY_PRINT);
				}
			}
		}
	}
}

if ($_GET['action']=="clear_history")
{
	unset($_SESSION['mi_time']);
	unset($_SESSION['mi_command']);
	unset($_SESSION['mi_box']);
	unset($_SESSION['mi_response']);
}

require("template/".$page_id.".main.php");
require("template/footer.php");
exit();

?>
