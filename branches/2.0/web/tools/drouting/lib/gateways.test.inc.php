<?php
/*
 * $Id$
 */

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
                    db_connect();
                    $result=mysql_query("select * from ".$table." where address='".$address."' and type='".$type."' and strip='".$strip."' and pri_prefix='".$pri_prefix."'") or die(mysql_error());
                    $data_rows=mysql_num_rows($result);
                    $rows=mysql_fetch_array($result);
                    if (($data_rows>0) && ($rows['gwid']!=$_GET['id']))
                    {
                     $form_valid=false;
                     $form_error="- this is already a valid gateway -";
                    }
                    db_close();
                   }

?>
