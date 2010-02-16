<?php
require("../../../../../config/tools/users/alias_management/local.inc.php");

  extract($_GET);
  //global $config;
  
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
						
						 $form_error="alias_username_format";
						echo $form_error;
					  exit();
					}
				}
				else{
						$form_error="alias_username_format";
						echo $form_error;
					  exit();
				}
	}

?>
