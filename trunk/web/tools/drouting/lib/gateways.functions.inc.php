<?php
/*
 * $Id$
 */ 

function get_status($id)
{
 include("db_connect.php");
 require_once("../../../config/db.inc.php");
 global $config;
 if ($config->db_driver == "mysql") 
 	$sql="select ruleid from ".$config->table_rules." where gwlist regexp '(^".$id."$)|(^".$id."[,;|])|([,;|]".$id."[,;|])|([,;|]".$id."$)|(^#".$id."$)'";
 else if ($config->db_driver == "pgsql")
	 $sql="select ruleid from ".$config->table_rules." where gwlist ~* '(^".$id."$)|(^".$id."[,;|])|([,;|]".$id."[,;|])|([,;|]".$id."$)|(^#".$id."$)'";

 $result=$link->queryAll($sql);
 if(PEAR::isError($result)) {
	 die('Failed to issue query, error message : ' . $result->getMessage());
 }
 $data_no = count($result);
 return($data_no);
}

function get_types($name, $set)
{
 $filename = "../../../config/tools/drouting/gw_types.txt";
 $handle = fopen($filename, "r");
 while (!feof($handle))
 {
  $buffer = fgets($handle, 4096);
  $pos = strpos($buffer, " ");
  $values[] = trim(substr($buffer, 0, $pos));
  $content[] = trim(substr($buffer, $pos, strlen($buffer)));
 }
 fclose($handle);
 echo('<select name="'.$name.'" id="'.$name.'" size="1" class="dataSelect">');
 if ($name=="search_type") echo('<option value="">- all types -</option>');
 for ($i=0; $i<sizeof($values); $i++)
 {
  if ($set==$values[$i]) $xtra = 'selected';
   else $xtra ='';
  if(!empty($values[$i]))
	echo('<option value="'.$values[$i].'" '.$xtra.'>'.$values[$i].' - '.$content[$i].'</option>');
 }
 echo('</select>');
 return;
}

function get_type($id)
{
 $filename = "../../../config/tools/drouting/gw_types.txt";
 $handle = fopen($filename, "r");
 while (!feof($handle))
 {
  $buffer = fgets($handle, 4096);
  $pos = strpos($buffer, " ");
  $value = trim(substr($buffer, 0, $pos));
  $content = trim(substr($buffer, $pos, strlen($buffer))); 
  if ($value!="" && $value==$id) echo($value." - ".$content); 
 }
 fclose($handle);
 return;
}

?>
