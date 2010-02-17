<?php
require("../../../../../config/tools/users/alias_management/local.inc.php");

require_once("../../../../../config/tools/users/alias_management/db.inc.php");
require_once("../../../../../config/db.inc.php");
	require_once("MDB2.php");

        global $config;
        if (isset($config->db_host_alias_management) && isset($config->db_user_alias_management) && isset($config->db_name_alias_management) ) {
                $config->db_host = $config->db_host_alias_management;
                $config->db_port = $config->db_port_alias_management;
                $config->db_user = $config->db_user_alias_management;
                $config->db_pass = $config->db_pass_alias_management;
                $config->db_name = $config->db_name_alias_management;
        }
        $dsn = $config->db_driver.'://' . $config->db_user.':'.$config->db_pass . '@' . $config->db_host . '/'. $config->db_name.'';
        $link = & MDB2::connect($dsn);
        $link->setFetchMode(MDB2_FETCHMODE_ASSOC);
        if(PEAR::isError($link)) {
            die("Error while connecting : " . $link->getMessage());
        }


foreach ($config->table_aliases as $key=>$value) {
	$options[]=array("label"=>$key,"value"=>$value);
}

  extract($_GET);
  
  
  for ($i=0;count($options)>$i;$i++){
	if($_GET['alias_type']==$options[$i]['label'])
		$table =  $options[$i]['value'];
}

$sql_command = "select * from ".$table." where alias_username = '".$alias_username."'";
$resultset = $link->queryAll($sql_command);
if(PEAR::isError($resultset)) {
    die('Failed to issue query, error message : ' . $resultset->getMessage());
}	
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
