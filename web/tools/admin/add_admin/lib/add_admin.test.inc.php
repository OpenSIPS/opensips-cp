<?php
/*
 * $Id: groups.test.inc.php 57 2009-06-03 13:48:46Z iulia_bublea $
 */ 

  extract($_POST);
  global $config;
  $form_valid=true;
  if ($form_valid)
   if ($add_fname=="") {
                       $form_valid=false;
                       $form_error="- invalid <b>First Name</b> field -";
                      }
  if ($form_valid)
   if ($add_lname=="") {
                     $form_valid=false;
                     $form_error="- invalid <b>Last Name</b> field -";
                    }
  if ($form_valid)
   if ($add_uname=="") {
                      $form_valid=false;
                      $form_error="- invalid <b>Username</b> field -";
                     }

  if ($form_valid)
	if ($add_passwd=="") {
		$form_valid=false;
		$form_error="=invalid <b>Password</b> field -";
	}
 
  if ($form_valid) 
	if ($confirm_passwd=="") {
		$form_valid=false;
		$form_error="=invalid <b>Password</b> field -";
	} 

  if ($form_valid) {
	if ($add_passwd != $confirm_passwd) {
		$form_valid=false;
		$form_error="- <b>Passwords do not match!<b> -";
	}
 }	
 
  if ($form_valid) {
		if ($config->admin_passwd_mode==0) {
  		     $ha1  = "";
	        } else if ($config->admin_passwd_mode==1) {
       	 	     $ha1 = md5($uname.":".$add_passwd);
 	        } else {
	  	   $form_valid = false;
                  $form_error = "- <b> Unknow value for password mode!<b> -";
	        }
	}


  if ($form_valid) {
                    $sql="select * from ".$table." where username='".$add_uname."'";
		    $resultset = $link->queryAll($sql);
                    if(PEAR::isError($resultset)) {
                    	die('Failed to issue query, error message : ' . $resultset->getMessage());
                    }
                    $data_rows=count($resultset);
                    if (($data_rows>0) && ($resultset[0]['username']==$add_uname) )
                    {
                     $form_valid=false;
                     $new_id=$add_uname;
                     $form_error="- <b>".$new_id."</b> is already a valid admin -";
                    }
                   }

?>
