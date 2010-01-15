<?php
/*
 * $Id: gateways.test.inc.php 57 2009-06-03 13:48:46Z iulia_bublea $
 */
include("db_connect.php");
  extract($_POST);
  $form_valid=true;
  if ($form_valid)
   if ($address=="") {
                      $form_valid=false;
                      $form_error="- invalid <b>Address</b> field -";
                     }
  if ($form_valid)
   if ($strip=="") {
                    $form_valid=false;
                    $form_error="- invalid <b>Strip</b> field -";
                   }
  if ($form_valid)
   if (!is_numeric($strip)) {
                             $form_valid=false;
                             $form_error="- <b>Strip</b> field must be numeric -";
                            }
  if ($form_valid)
   if ($strip<0) {
                  $form_valid=false;
                  $form_error="- <b>Strip</b> field must be a positive number -";
                 }
  if ($form_valid) {
                    $sql="select * from ".$table." where address='".$address."' and type='".$type."' and strip='".$strip."' and pri_prefix='".$pri_prefix."'";
                    $result=$link->queryAll($sql);
		    if(PEAR::isError($result)) {
                    	die('Failed to issue query, error message : ' . $result->getMessage());
                    }
		    $data_rows=count($result); 	
                    if (($data_rows>0) && ($result[0]['gwid']!=$_GET['id']))
                    {
                     $form_valid=false;
                     $form_error="- this is already a valid gateway -";
                    }
                   }

?>
