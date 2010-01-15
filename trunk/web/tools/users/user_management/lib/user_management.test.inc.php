<?php
/*
 * $Id: user_management.test.inc.php 57 2009-06-03 13:48:46Z iulia_bublea $
 */ 

  extract($_POST);
  global $config;
  $form_valid=true;
  /*if ($form_valid)
   if ($email=="") {
                      $form_valid=false;
                      $form_error="- invalid <b>Email</b> field -";
                     }*/
  if ($form_valid)
   if ($uname=="") {
                      $form_valid=false;
                      $form_error="- invalid <b>Username</b> field -";
                     }
  if ($form_valid)
   if ($alias=="") {
                    $form_valid=false;
                    $form_error="- invalid <b>Alias</b> field -";
                   }

  if ($form_valid)
   if ($email=="") {
                    $form_valid=false;
                    $form_error="- invalid <b>Email Address</b> field -";
                   }

  if ($form_valid)
   if ($domain=='') {
                    $form_valid=false;
                    $form_error="- invalid <b>Domain</b> field -";
                   }

  if ($form_valid)
	if ($passwd=="") {
		    $form_valid=false;
		    $form_error="- invalid <b>Password</b> field -";
		   }
	
  if ($form_valid) {
	if ($confirm_passwd=="") {
		    $form_valid=false;
		    $form_error="- invalid <b>Confirm Password</b> field-";	
	}
  }

  if ($form_valid) {
		if ($passwd != $confirm_passwd) {
			$form_valid=false;
			$form_error="- <b>Passwords do not match!<b> -";
		}
  }
  
  if ($form_valid) {
	       if ($config->passwd_mode==1) {
  		     $ha1  = "";
	             $ha1b = "";	 	
	       } else if ($config->passwd_mode==0) {
        	     $ha1 = md5($uname.":".$domain.":".$passwd);
	             $ha1b = md5($uname."@".$domain.":".$domain.":".$passwd);
       	       } else {
		     $form_valid = false;
        	     $form_error = "- <b> Unknow value for password mode!<b> -";
	       }
	}	
	

  if ($form_valid) {
                    $sql="select * from ".$table." where username='".$uname."' and domain='".$domain."'";
		    $resultset = $link->queryAll($sql);
                    if(PEAR::isError($resultset)) {
                    	die('Failed to issue query, error message : ' . $resultset->getMessage());
                    }
                    $data_rows=count($resultset);
                    if (($data_rows>0) && (($resultset[0]['username']==$uname) || ($resultset[0]['domain']==$domain)))
                    {
                     $form_valid=false;
                     $new_id=$uname."@".$domain;
                     $form_error="- <b>".$new_id."</b> is already a valid user -";
                    }
                   }

 if ($form_valid) {
                   $sql = 'select * from '.$alias_type.' where alias_username="'.$alias.'" and alias_domain="'.$domain.'"';
                   $resultset = $link->queryAll($sql);
                   if(PEAR::isError($resultset)) {
                       die('Failed to issue query, error message : ' . $resultset->getMessage());
                   }
                   $data_rows=count($resultset);
                   if (($data_rows>0) && (($resultset[0]['alias_username']==$alias) || ($resultset[0]['alias_domain']==$domain)))
                    {
                     $form_valid=false;
                     $new_id=$alias."@".$domain;
                     $form_error="- <b>".$new_id."</b> is already a valid alias -";
                    }

	}

?>
