<?php
/*
 * $Id: groups.test.inc.php 57 2009-06-03 13:48:46Z iulia_bublea $
 */ 

  extract($_POST);
  global $config;
  $form_valid=true;
  if ($form_valid)
   if ($listfname=="") {
                       $form_valid=false;
                       $form_error="- invalid <b>First Name</b> field -";
                      }
  if ($form_valid)
   if ($listlname=="") {
                     $form_valid=false;
                     $form_error="- invalid <b>Last Name</b> field -";
                    }
  if ($form_valid)
   if ($listuname=="") {
                      $form_valid=false;
                      $form_error="- invalid <b>Username</b> field -";
                     }

  if ($form_valid) {
	if ($listpasswd != $conf_passwd) {
		$form_valid=false;
		$form_error="- <b>Passwords do not match!<b> -";
	}
  
       if ($config->passwd_mode==0) {
  	     $ha1  = "";
             $ha1b = "";	 	
       } else if ($config->passwd_mode==1) {
             $ha1 = md5($listuname.":".$domain.":".$listpasswd);
             $ha1b = md5($listuname."@".$domain.":".$domain.":".$listpasswd);
       } else {
	     $form_valid = false;
             $form_error = "- <b> Unknow value for password mode!<b> -";
       }

	
  }	


  if ($form_valid) {
                    $sql="select * from ".$table." where username='".$listuname."'";
		    $resultset = $link->queryAll($sql);
                    if(PEAR::isError($resultset)) {
                    	die('Failed to issue query, error message : ' . $resultset->getMessage());
                    }
                    $data_rows=count($resultset);
                    if (($data_rows>0) && ($resultset[0]['username']==$listuname) && ($resultset[0]['first_name']==$listfname) && ($resultset[0]['last_name']==$listlname) &&($resultset[0]['password']==$listpasswd) )
                    {
                     $form_valid=false;
                     $new_id=$uname;
                     $form_error="- <b>".$new_id."</b> is already a valid admin -";
                    }
                   }

?>
