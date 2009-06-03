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
                    $sql="select * from ".$table." where gwlist='".$gwlist."'";
		    $result = $link->queryAll($sql);
		    if(PEAR::isError($resultset)) {
                                die('Failed to issue query, error message : ' . $resultset->getMessage());
                    }	
                    $data_rows=count($result);
                    if (($data_rows>0) && ($result[0]['id']!=$_GET['id']))
                    {
                     $form_valid=false;
                     $form_error="- this is already a valid rule -";
                    }
                   

?>
