<?php
/*
 * $Id$
 */
  extract($_POST);
  $form_valid=true;
  if ($form_valid)
   if ($gwlist=="") {
                     $form_valid=false;
                     $form_error="- invalid <b>Gateway List</b> field -";
                    }
  
  if ($form_valid) {
                    // make $gwlist
                    if (substr($gwlist,strlen($gwlist)-1,1)==";") $gwlist=substr($gwlist,0,strlen($gwlist)-1);
                    if (substr($gwlist,strlen($gwlist)-1,1)==",") $gwlist=substr($gwlist,0,strlen($gwlist)-1);
  }
                    db_connect();
                    $result=mysql_query("select * from ".$table." where gwlist='".$gwlist."'") or die(mysql_error());
                    $data_rows=mysql_num_rows($result);
                    $rows=mysql_fetch_array($result);
                    if (($data_rows>0) && ($rows['id']!=$_GET['id']))
                    {
                     $form_valid=false;
                     $form_error="- this is already a valid rule -";
                    }
                    db_close();
                   

?>
