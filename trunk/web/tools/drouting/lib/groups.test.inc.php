<?php
/*
 * $Id: groups.test.inc.php,v 1.1 2007-04-19 14:06:54 bogdan Exp $
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
                    db_connect();
                    $result=mysql_query("select * from ".$table." where username='".$username."' and domain='".$domain."'") or die(mysql_error());
                    $data_rows=mysql_num_rows($result);
                    $rows=mysql_fetch_array($result);
                    if (($data_rows>0) && (($rows['username']!=$id_username) || ($rows['domain']!=$id_domain)))
                    {
                     $form_valid=false;
                     $new_id=$username."@".$domain;
                     $form_error="- <b>".$new_id."</b> is already a valid user -";
                    }
                    db_close();
                   }

?>
