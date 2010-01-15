<?php
/*
 * $Id: alias_management.test.inc.php 57 2009-06-03 13:48:46Z iulia_bublea $
 */ 

  extract($_POST);
  global $config;
  $form_valid=true;
  if ($form_valid)
  if ($username=="") {
                      $form_valid=false;
                      $form_error="- invalid <b>Username</b> field -";
                     }

  
  if ($domain=="ANY") {
                      $form_valid=false;
                      $form_error="- invalid <b>domain</b> field -\n Choose a domain from the dropdown list!";
                     }

  if ($alias_username=="") {
                      $form_valid=false;
                      $form_error="- invalid <b>alias username</b> field -";
                     }

  if ($alias_domain=="ANY") {
                      $form_valid=false;
                      $form_error="- invalid <b>alias domain</b> field -\n Choose a domain from the dropdown list!";
                     }

  if ($alias_type=="ANY") {
                      $form_valid=false;
                      $form_error="- invalid <b>alias type</b> field -\n Choose a type from the dropdown list!";
                     }



?>
