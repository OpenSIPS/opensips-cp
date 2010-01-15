<?php
/*
 * $Id: groups.test.inc.php 57 2009-06-03 13:48:46Z iulia_bublea $
 */ 

  extract($_POST);
  $form_valid=true;
  if ($form_valid)
   if ($username=="") {
                       $form_valid=false;
                       $form_error="- invalid <b>Username</b> field -";
                      }
  if ($form_valid)
   if ($domain=="") {
                     $form_valid=false;
                     $form_error="- invalid <b>Domain</b> field -";
                    }
  if ($form_valid)
   if ($groupid=="") {
                      $form_valid=false;
                      $form_error="- invalid <b>Group ID</b> field -";
                     }
  if ($form_valid)
   if (!is_numeric($groupid)) {
                               $form_valid=false;
                               $form_error="- <b>Group ID</b> field must be numeric -";
                              }
  if ($form_valid)
   if ($groupid<0) {
                    $form_valid=false;
                    $form_error="- <b>Group ID</b> field must be a positive number -";
                   }
  if ($form_valid) {
                    $sql="select * from ".$table." where username='".$username."' and domain='".$domain."'";
		    $resultset = $link->queryAll($sql);
                    if(PEAR::isError($resultset)) {
                    	die('Failed to issue query, error message : ' . $resultset->getMessage());
                    }
                    $data_rows=count($resultset);
                    if (($data_rows>0) && (($resultset[0]['username']!=$id_username) || ($resultset[0]['domain']!=$id_domain)))
                    {
                     $form_valid=false;
                     $new_id=$username."@".$domain;
                     $form_error="- <b>".$new_id."</b> is already a valid user -";
                    }
                   }

?>
