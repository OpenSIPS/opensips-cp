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


require("../../../../../config/tools/users/alias_management/local.inc.php");

require_once("../../../../../config/tools/users/alias_management/db.inc.php");
require_once("../../../../../config/db.inc.php");

global $config;
if (isset($config->db_host_alias_management) && isset($config->db_user_alias_management) && isset($config->db_name_alias_management) ) {
	$config->db_host = $config->db_host_alias_management;
	$config->db_port = $config->db_port_alias_management;
	$config->db_user = $config->db_user_alias_management;
	$config->db_pass = $config->db_pass_alias_management;
	$config->db_name = $config->db_name_alias_management;
}

$dsn = $config->db_driver . ':host=' . $config->db_host . ';dbname='. $config->db_name;
try {
	$link = new PDO($dsn, $config->db_user, $config->db_pass);
} catch (PDOException $e) {
	error_log(print_r("Failed to connect to: ".$dsn, true));
	print "Error!: " . $e->getMessage() . "<br/>";
	die;
}

foreach ($config->table_aliases as $key=>$value) {
	$options[]=array("label"=>$key,"value"=>$value);
}

  extract($_GET);
  
  
  for ($i=0;count($options)>$i;$i++){
	if($_GET['alias_type']==$options[$i]['label'])
		$table =  $options[$i]['value'];
}

$sql_command = "select * from ".$table." where alias_username = ?";
$stm = $link->prepare($sql_command);
if ($stm === false) {
	die('Failed to issue query ['.$sql_command.'], error message : ' . print_r($link->errorInfo(), true));
}
$stm->execute( array($alias_username) );
$resultset = $stm->fetchAll(PDO::FETCH_ASSOC);

$aliasexists=0;

if (count($resultset)>0) {
$aliasexists=1;
}

  
  if ($username=="") {
                     
                      $form_error="username";
					  echo $form_error;
					  exit();
                     }

  
  if ($domain=="ANY") {
                      
                      $form_error="domain";
					  echo $form_error;
					  exit();
                     }

  

  if ($alias_domain=="ANY") {
                       $form_error="alias_domain";
					  echo $form_error;
					  exit();
                     }

  if ($alias_type=="ANY") {
                      $form_error="alias_type";
					  echo $form_error;
					  exit();
                     }


					 
	if ($alias_username=="") {
                       $form_error="alias_username_empty";
						echo $form_error;
					  exit();
                     }
	else{
				preg_match($config->alias_format,$alias_username,$matches,PREG_OFFSET_CAPTURE);
				if (!empty($matches) ){
					if (!in_array($alias_username,$matches[0])) {
						if ($aliasexists){	
							$form_error="alias_username_format_exists";
							echo $form_error;
							exit();
						}
						else{
							$form_error="alias_username_format";
							echo $form_error;
							exit();
						}
					}
					else{
						if ($aliasexists){	
							$form_error="alias_username_exists";
							echo $form_error;
							exit();
						}
					}
				}
				else{
					if ($aliasexists){	
							$form_error="alias_username_format_exists";
							echo $form_error;
							exit();
						}
						else{
							$form_error="alias_username_format";
							echo $form_error;
							exit();
						}
						
				}
	}

?>
