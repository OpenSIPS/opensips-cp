<?php
/*
 * $Id$
 */
 
 require("template/header.php");
 
 if (isset($_POST['action'])) $action=$_POST['action'];
 else if (isset($_GET['action'])) $action=$_GET['action'];
      else $action="";
 
 
 if ($action=="gw_types")
 {
  extract($_POST);
  $gw_error="";
  $rows=explode("\n",$data);
  if ((sizeof($rows)==1) && (trim($rows[0])=="")) $gw_error="no Gateway Types defined";
   else
   {
    for($i=0;$i<sizeof($rows);$i++)
    {
     $pos=strpos($rows[$i]," ");
     if ($pos===false) $gw_error="invalid Gateway Types Format";
     $value[$i]=trim(substr($rows[$i],0,$pos));
     $content[$i]=trim(substr($rows[$i],$pos,strlen($rows[$i])));
     if ((!is_numeric($value[$i])) || ($content[$i]=="")) $gw_error="invalid Gateway Types Format";
    }
    $result=array_unique($value);
    if (sizeof($result)!=sizeof($value)) $gw_error="duplicate Gateway Types"; 
   }
  if ($gw_error=="") {
                      $filename="../../../config/tools/drouting/gw_types.txt";
                      $handle=fopen($filename,"w");
                      fwrite($handle,$data);
                      fclose($handle);
                     }
 }

 if ($action=="groups")
 {
  extract($_POST);
  $groups_error="";
  $rows=explode("\n",$data);
  if ((sizeof($rows)==1) && (trim($rows[0])=="")) $groups_error="no Group IDs defined";
   else
   {
    for($i=0;$i<sizeof($rows);$i++)
    {
     $pos=strpos($rows[$i]," ");
     if ($pos===false) $groups_error="invalid Group ID Format";
     $value[$i]=trim(substr($rows[$i],0,$pos));
     $content[$i]=trim(substr($rows[$i],$pos,strlen($rows[$i])));
     if ((!is_numeric($value[$i])) || ($content[$i]=="")) $groups_error="invalid Group ID Format";
    }
    $result=array_unique($value);
    if (sizeof($result)!=sizeof($value)) $groups_error="duplicate Group ID"; 
   }
  if ($groups_error=="") {
   $filename="../../../config/tools/drouting/group_ids.txt";
   $handle=fopen($filename,"w");
   fwrite($handle,$data);
   fclose($handle);
  }
 }
 
 require("template/".$page_id.".main.php");
 require("template/footer.php");
 exit();
?>
